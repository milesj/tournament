<?php

App::uses('Event', 'Tournament.Model');
App::uses('EventParticipant', 'Tournament.Model');
App::uses('Match', 'Tournament.Model');
App::uses('DoubleElim', 'Tournament.Lib');
App::uses('SingleElim', 'Tournament.Lib');
App::uses('RoundRobin', 'Tournament.Lib');
App::uses('Swiss', 'Tournament.Lib');

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
	 * Fetch event information.
	 *
	 * @param array $event
	 * @throws Exception
	 */
	public function __construct($event) {
		$this->Event = ClassRegistry::init('Tournament.Event');
		$this->EventParticipant = ClassRegistry::init('Tournament.EventParticipant');
		$this->Match = ClassRegistry::init('Tournament.Match');

		if (!$event) {
			throw new Exception('Invalid event');

		} else if ($event['Event']['isRunning'] || $event['Event']['isFinished']) {
			throw new Exception('Event has already started');
		}

		$this->_id = $event['Event']['id'];
		$this->_event = $event['Event'];
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
	 * Return the correct tournament type instance.
	 *
	 * @param int $id
	 * @return DoubleElim|RoundRobin|SingleElim|Swiss
	 * @throws Exception
	 */
	public static function factory($id) {
		$event = ClassRegistry::init('Tournament.Event')->getById($id);

		switch ($event['Event']['type']) {
			case Event::DOUBLE_ELIM:
				return new DoubleElim($event);
			break;
			case Event::SINGLE_ELIM:
				return new SingleElim($event);
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
	 * Generate brackets for the current event.
	 *
	 * @return bool
	 */
	abstract public function generateBrackets();

	/**
	 * Get all ready participant IDs for an event. Take into account the event seeding order.
	 *
	 * @param array $query
	 * @return array
	 * @throws Exception
	 */
	public function getParticipants(array $query = array()) {
		$for = ($this->_event['for'] == Event::TEAM) ? 'Team' : 'Player';
		$order = 'RAND()';

		if ($this->_event['seed'] == Event::POINTS) {
			$order = array($for . '.points' => 'ASC');
		}

		$query = Hash::merge(array(
			'conditions' => array(
				'EventParticipant.event_id' => $this->_id,
				'EventParticipant.isReady' => EventParticipant::YES
			),
			'contain' => array($for),
			'order' => $order
		), $query);

		$participants = $this->EventParticipant->find('all', $query);

		if (!$participants) {
			throw new Exception('There are no participants for this event');
		}

		$participant_ids = array();

		foreach ($participants as $participant) {
			$participant_ids[] = $participant[$for]['id'];
		}

		return $participant_ids;
	}

}