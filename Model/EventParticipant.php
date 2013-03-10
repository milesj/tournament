<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class EventParticipant extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Tournament.Event',
			'counterCache' => true
		),
		'Team' => array(
			'className' => 'Tournament.Team'
		),
		'Player' => array(
			'className' => 'Tournament.Player'
		)
	);

	/**
	 * Validation.
	 *
	 * @var array
	 */
	public $validate = array(
		'event_id' => 'notEmpty',
		'status' => 'notEmpty',
		'wins' => array(
			'rule' => 'numeric',
			'message' => 'May only contain numerical characters'
		),
		'losses' => array(
			'rule' => 'numeric',
			'message' => 'May only contain numerical characters'
		),
		'ties' => array(
			'rule' => 'numeric',
			'message' => 'May only contain numerical characters'
		),
		'points' => array(
			'rule' => 'numeric',
			'message' => 'May only contain numerical characters'
		)
	);

	/**
	 * Admin settings.
	 *
	 * @var array
	 */
	public $admin = array(
		'iconClass' => 'icon-list',
		'hideFields' => array('standing', 'pool', 'seed')
	);

	/**
	 * Return all participants by a specific type.
	 * Only contain relations based on that type.
	 *
	 * @param int $event_id
	 * @return array
	 */
	public function getParticipants($event_id) {
		$event = $this->Event->getById($event_id);

		if ($event['Event']['for'] == self::TEAM) {
			$contain = array('Team' => array('Leader'));
		} else {
			$contain = array('Player' => array('User'));
		}

		return $this->find('all', array(
			'conditions' => array('EventParticipant.event_id' => $event_id),
			'order' => array('EventParticipant.isReady' => 'DESC', 'EventParticipant.created' => 'ASC'),
			'contain' => $contain,
			'cache' => array(__METHOD__, $event_id)
		));
	}

	/**
	 * Get an events standings as a list.
	 *
	 * @param int $event_id
	 * @return array
	 */
	public function getStandings($event_id) {
		$event = $this->Event->getById($event_id);

		if ($event['Event']['for'] == self::TEAM) {
			$field = 'team_id';
		} else {
			$field = 'player_id';
		}

		return $this->find('list', array(
			'conditions' => array('EventParticipant.event_id' => $event_id),
			'fields' => array('EventParticipant.' . $field, 'EventParticipant.standing'),
			'order' => array('EventParticipant.standing' => 'ASC'),
			'cache' => array(__METHOD__, $event_id)
		));
	}

	/**
	 * Return the winning participant.
	 *
	 * @param int $event_id
	 * @return array
	 */
	public function getWinner($event_id) {
		$event = $this->Event->getById($event_id);

		if ($event['Event']['for'] == self::TEAM) {
			$contain = array('Team' => array('Leader'));
		} else {
			$contain = array('Player' => array('User'));
		}

		return $this->find('first', array(
			'conditions' => array(
				'EventParticipant.event_id' => $event_id,
				'EventParticipant.isWinner' => self::YES
			),
			'contain' => $contain,
			'cache' => array(__METHOD__, $event_id)
		));
	}

	/**
	 * Update a participant by increasing or decreasing stats (wins, losses, etc).
	 *
	 * @param int $event_id
	 * @param int $participant_id
	 * @param array $stats
	 * @return bool
	 */
	public function updateStatistics($event_id, $participant_id, array $stats) {
		$event = $this->Event->getById($event_id);
		$conditions = array('EventParticipant.event_id' => $event_id);
		$query = array();

		if ($event['Event']['for'] == self::TEAM) {
			$conditions['EventParticipant.team_id'] = $participant_id;
		} else {
			$conditions['EventParticipant.player_id'] = $participant_id;
		}

		foreach ($stats as $key => $value) {
			$query['EventParticipant.' . $key] = 'EventParticipant.' . $key . ' + ' . (int) $value;
		}

		return $this->updateAll($query, $conditions);
	}

}