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
	 * Return all participants by a specific type.
	 * Only contain relations based on that type.
	 *
	 * @param int $event_id
	 * @param int $type
	 * @return array
	 */
	public function getParticipantsByType($event_id, $type = self::TEAM) {
		if ($type == self::TEAM) {
			$contain = array('Team' => array('Leader'));
		} else {
			$contain = array('Player' => array('User'));
		}

		return $this->find('all', array(
			'conditions' => array('EventParticipant.event_id' => $event_id),
			'order' => array('EventParticipant.isReady' => 'DESC', 'EventParticipant.created' => 'ASC'),
			'contain' => $contain,
			'cache' => array(__METHOD__, $event_id, $type)
		));
	}

}