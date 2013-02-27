<?php

App::uses('Event', 'Tournament.Model');
App::uses('EventParticipant', 'Tournament.Model');
App::uses('Match', 'Tournament.Model');
App::uses('DoubleElim', 'Tournament.Lib');
App::uses('SingleElim', 'Tournament.Lib');
App::uses('RoundRobin', 'Tournament.Lib');
App::uses('Swiss', 'Tournament.Lib');
App::uses('Bracket', 'Tournament.Lib');

/**
 * @property Event $Event
 * @property EventParticipant $EventParticipant
 * @property Match $Match
 */
abstract class Tournament {

	/**
	 * Event ID.
	 *
	 * @var int
	 */
	protected $_id;

	/**
	 * Event record.
	 *
	 * @var array
	 */
	protected $_event;

	/**
	 * Whether the event is Team or Player.
	 *
	 * @var string
	 */
	protected $_forModel;
	protected $_forField;

	/**
	 * Fetch event information.
	 *
	 * @param array $event
	 * @throws Exception
	 * @final
	 */
	final public function __construct($event) {
		$this->Event = ClassRegistry::init('Tournament.Event');
		$this->EventParticipant = ClassRegistry::init('Tournament.EventParticipant');
		$this->Match = ClassRegistry::init('Tournament.Match');

		if (!$event) {
			throw new Exception('Invalid event');
		}

		$this->_id = $event['Event']['id'];
		$this->_event = $event['Event'];
		$this->_forModel = ($this->_event['for'] == Event::TEAM) ? 'Team' : 'Player';
		$this->_forField = ($this->_event['for'] == Event::TEAM) ? 'team_id' : 'player_id';

		$this->validate();
	}

	/**
	 * Create a match. Check for missing players and supply a bye.
	 *
	 * @param int $home_id
	 * @param int $away_id
	 * @param int $round
	 * @param int $pool
	 * @return mixed
	 */
	public function createMatch($home_id, $away_id, $round = null, $pool = null) {
		$query = array(
			'league_id' => $this->_event['league_id'],
			'event_id' => $this->_id,
			'home_id' => $home_id,
			'away_id' => $away_id,
			'type' => $this->_event['for'],
			'round' => $round,
			'pool' => $pool,
			'playOn' => null // @TODO
		);

		// If away is null, give home a bye
		if (!$away_id) {
			$query = $query + array(
				'winner' => Match::HOME,
				'homeOutcome' => Match::BYE,
				'homeScore' => 0,
				'awayOutcome' => Match::BYE,
				'awayScore' => 0
			);
		}

		$this->Match->create();

		return $this->Match->save($query);
	}

	/**
	 * End the current event.
	 *
	 * @throws Exception
	 */
	public function endEvent() {
		$this->Event->id = $this->_id;
		$this->Event->save(array(
			'isRunning' => Event::NO,
			'isFinished' => Event::YES
		), false);

		$this->flagStandings();

		throw new Exception('Event finished; Generating standings and winner');
	}

	/**
	 * Return the correct tournament type instance.
	 *
	 * @param int|array $data
	 * @return DoubleElim|RoundRobin|SingleElim|Swiss
	 * @throws Exception
	 */
	public static function factory($data) {
		if (is_numeric($data)) {
			$event = ClassRegistry::init('Tournament.Event')->getById($data);
		} else {
			$event = $data;
		}

		switch ($event['Event']['type']) {
			case Event::SINGLE_ELIM:
				return new SingleElim($event);
			break;
			case Event::DOUBLE_ELIM:
				return new DoubleElim($event);
			break;
			case Event::ROUND_ROBIN:
				return new RoundRobin($event);
			break;
			case Event::SWISS:
				return new Swiss($event);
			break;
			default:
				throw new Exception('Invalid event type');
			break;
		}
	}

	/**
	 * Flag each participants standing in the database.
	 */
	public function flagStandings() {
		$participants = $this->EventParticipant->find('all', array(
			'conditions' => array('EventParticipant.event_id' => $this->_id),
			'order' => array('EventParticipant.points' => 'DESC')
		));

		$currentStanding = 0;
		$lastPoints = 0;

		foreach ($participants as $participant) {
			$query = array();

			if ($participant['EventParticipant']['points'] != $lastPoints) {
				$currentStanding++;
				$lastPoints = $participant['EventParticipant']['points'];
			}

			$query['standing'] = $currentStanding;

			if ($currentStanding == 1) {
				$query['isWinner'] = EventParticipant::YES;
				$query['wonOn'] = date('Y-m-d H:i:s');
			}

			$this->EventParticipant->id = $participant['EventParticipant']['id'];
			$this->EventParticipant->save($query, false);
		}
	}

	/**
	 * Flag a participant with seed and pool data.
	 *
	 * @param int $participant_id
	 * @param int $seed
	 * @param int $pool
	 */
	public function flagParticipant($participant_id, $seed = null, $pool = null) {
		$participant = $this->EventParticipant->find('first', array(
			'conditions' => array(
				'EventParticipant.event_id' => $this->_id,
				'EventParticipant.' . $this->_forField => $participant_id
			)
		));

		$this->EventParticipant->id = $participant['EventParticipant']['id'];
		$this->EventParticipant->save(array(
			'seed' => $seed,
			'pool' => $pool
		));
	}

	/**
	 * Generate matches for the current event.
	 *
	 * @return bool
	 */
	abstract public function generateMatches();

	/**
	 * Get all ready participant IDs for an event. Take into account the event seeding order.
	 *
	 * @param array $query
	 * @param bool $return
	 * @return array
	 * @throws Exception
	 */
	public function getParticipants(array $query = array(), $return = false) {
		$for = $this->_forModel;
		$order = 'RAND()';

		if ($this->_event['seed'] == Event::POINTS) {
			$order = array($for . '.points' => 'ASC');
		}

		if ($for == 'Player') {
			$contain = array($for => array('User'));
		} else {
			$contain = array($for);
		}

		$query = Hash::merge(array(
			'conditions' => array(
				'EventParticipant.event_id' => $this->_id,
				'EventParticipant.isReady' => EventParticipant::YES
			),
			'contain' => $contain,
			'order' => $order
		), $query);

		$participants = $this->EventParticipant->find('all', $query);

		if (!$participants) {
			throw new Exception('There are no participants for this event');
		}

		if ($return) {
			return $participants;
		}

		$participant_ids = array();

		foreach ($participants as $participant) {
			$participant_ids[] = $participant[$for]['id'];
		}

		return $participant_ids;
	}

	/**
	 * Organize a list of matches into the correct match order for brackets.
	 *
	 * @param array $matches
	 * @return array
	 */
	public function organizeBrackets($matches) {
		$participants = $this->getParticipants(array(), true);
		$rounds = array();
		$pools = array();

		foreach ($matches as $match) {
			$round = (int) $match['Match']['round'];
			$pool = (int) $match['Match']['pool'];

			if (empty($pools[$pool][$round])) {
				$pools[$pool][$round] = array();
			}

			$pools[$pool][$round][] = $match['Match']['id'];

			/*// Store match IDs into rounds
			if (empty($rounds[$round])) {
				$rounds[$round] = array();
			}

			$rounds[$round][] = $match['Match']['id'];

			// Store rounds into pools
			if (empty($pools[$pool])) {
				$pools[$pool] = array();
			}

			$pools[$pool][] = $round; */
		}

		debug($pools);

		// Loop through and sort matches
		/*foreach ($rounds as &$m) {
			sort($m, SORT_NUMERIC);
		}

		// Loop through and unique rounds
		foreach ($pools as &$r) {
			$r = array_unique($r);
		} */

		$bracket = new Bracket($this->_event);
		$bracket->setMatches($matches);
		$bracket->setParticipants($participants);
		$bracket->setRounds($rounds);
		$bracket->setPools($pools);

		debug($bracket);

		return $bracket;
	}

	/**
	 * Validate the event is the correct type for the class.
	 */
	abstract public function validate();

}