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
	 * Event type.
	 *
	 * @var int
	 */
	protected $_type;

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

		} else if ($event['Event']['type'] != $this->_type) {
			throw new Exception(sprintf('Event is not %s', $this->Event->enum('type', $this->_type)));
		}

		$this->_id = $event['Event']['id'];
		$this->_event = $event['Event'];
		$this->_forModel = ($this->_event['for'] == Event::TEAM) ? 'Team' : 'Player';
		$this->_forField = ($this->_event['for'] == Event::TEAM) ? 'team_id' : 'player_id';
	}

	/**
	 * Create new matches for byes in the next round.
	 *
	 * @param int $round
	 */
	public function advanceByes($round) {
		$this->Match->cacheQueries = false;

		$matches = $this->Match->find('all', array(
			'conditions' => array(
				'Match.event_id' => $this->_id,
				'Match.round' => $round,
				'Match.homeOutcome' => Match::BYE
			),
			'order' => array('Match.order' => 'ASC')
		));

		if ($matches) {
			$nextRound = $round + 1;

			foreach ($matches as $match) {
				$order = ceil($match['Match']['order'] / 2);
				$home_id = $match['Match']['home_id'];
				$away_id = null;

				if ($order < 1) {
					$order = 1;
				}

				// Find a match in the next round in case 2 byes meet
				$target = $this->Match->find('first', array(
					'conditions' => array(
						'Match.event_id' => $this->_id,
						'Match.round' => $nextRound,
						'Match.order' => $order
					)
				));

				if ($target) {
					$this->Match->id = $target['Match']['id'];
					$this->Match->saveField('away_id', $home_id);

				} else {
					$this->createMatch($home_id, $away_id, $order, $nextRound, null, false);
				}
			}
		}
	}

	/**
	 * Create a match. Check for missing players and supply a bye.
	 *
	 * @param int $home_id
	 * @param int $away_id
	 * @param int $order
	 * @param int $round
	 * @param int $pool
	 * @param bool $bye
	 * @return mixed
	 */
	public function createMatch($home_id, $away_id, $order, $round = null, $pool = null, $bye = true) {
		$query = array(
			'league_id' => $this->_event['league_id'],
			'event_id' => $this->_id,
			'home_id' => $home_id,
			'away_id' => $away_id,
			'type' => $this->_event['for'],
			'order' => $order,
			'round' => $round,
			'pool' => $pool,
			'playOn' => null // @TODO
		);


		// If away is null, give home a bye
		if (!$away_id && $bye) {
			$query = $query + array(
				'winner' => Match::HOME,
				'homeOutcome' => Match::BYE,
				'homePoints' => $this->_event['pointsForWin'],
				'awayOutcome' => Match::BYE,
				'awayPoints' => 0
			);
		}

		// Check if the match already exists
		$target = $this->Match->find('first', array(
			'conditions' => array(
				'Match.event_id' => $this->_id,
				'Match.round' => $round,
				'Match.order' => $order
			)
		));

		if ($target) {
			$this->Match->id = $target['Match']['id'];
		} else {
			$this->Match->create();
		}

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
	 * @param array $order
	 * @param bool $return
	 * @return array
	 * @throws Exception
	 */
	public function getParticipants(array $order = array(), $return = false) {
		$for = $this->_forModel;

		if (!$order) {
			if ($this->_event['seed'] == Event::POINTS) {
				$order = array($for . '.points' => 'ASC');
			} else {
				$order = 'RAND()';
			}
		}

		if ($for == 'Player') {
			$contain = array($for => array('User'));
		} else {
			$contain = array($for);
		}

		$participants = $this->EventParticipant->find('all', array(
			'conditions' => array(
				'EventParticipant.event_id' => $this->_id,
				'EventParticipant.isReady' => EventParticipant::YES
			),
			'contain' => $contain,
			'order' => $order
		));

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
	 * Return all the winners from the previous event round.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getWinners() {
		$matches = $this->Match->find('all', array(
			'conditions' => array(
				'Match.event_id' => $this->_id,
				'Match.round' => $this->_event['round']
			),
			'order' => array('Match.order' => 'ASC')
		));

		if (!$matches) {
			throw new Exception('No participants from the previous round');
		}

		$participant_ids = array();

		foreach ($matches as $match) {
			if ($match['Match']['winner'] == Match::HOME) {
				$participant_ids[] = $match['Match']['home_id'];

			} else if ($match['Match']['winner'] == Match::AWAY) {
				$participant_ids[] = $match['Match']['away_id'];
			}
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

		foreach ($matches as $match) {
			$round = (int) $match['Match']['round'];

			if (empty($rounds[$round])) {
				$rounds[$round] = array();
			}

			$rounds[$round][] = $match['Match']['id'];
		}

		// Loop through and sort matches
		foreach ($rounds as &$m) {
			sort($m, SORT_NUMERIC);
		}

		$bracket = new Bracket($this->_event);
		$bracket->setMatches($matches);
		$bracket->setParticipants($participants);
		$bracket->setRounds($rounds);

		return $bracket;
	}

	/**
	 * Organize and sort the seed order of players.
	 *
	 * @param array $participant_ids
	 * @return array
	 */
	public function organizeSeeds($participant_ids) {
		$seeds = $participant_ids;

		// Flag the seed order before sorting
		$seed = 1;

		foreach ($participant_ids as $participant_id) {
			if ($participant_id) {
				$this->flagParticipant($participant_id, $seed);
				$seed++;
			}
		}

		// Reorganize the seed order so that top tiered players match up last
		$count = count($seeds);
		$half = ceil($count / 2);
		$slice = 1;

		while ($slice < $half) {
			$temp = $seeds;
			$seeds = array();

			while (count($temp) > 0) {
				$seeds = array_merge($seeds, array_splice($temp, 0, $slice));
				$seeds = array_merge($seeds, array_splice($temp, -$slice, $slice));
			}

			$slice *= 2;
		}

		return $seeds;
	}

}