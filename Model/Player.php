<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Player extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => TOURNAMENT_USER
		)
	);

	/**
	 * Has one.
	 *
	 * @var array
	 */
	public $hasOne = array(
		'CurrentTeam' => array(
			'className' => 'Tournament.TeamMember',
			'conditions' => array('CurrentTeam.status' => self::ACTIVE)
		)
	);

	/**
	 * Has and belongs to many.
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'Team' => array(
			'className' => 'Tournament.Team',
			'with' => 'Tournament.TeamMember',
			'conditions' => array('TeamMember.status !=' => self::PENDING),
			'order' => array('Team.status' => 'ASC', 'TeamMember.created' => 'DESC')
		),
		'Event' => array(
			'className' => 'Tournament.Event',
			'with' => 'Tournament.EventParticipant',
			'order' => array('EventParticipant.created' => 'DESC')
		)
	);

	/**
	 * Grab the users player record. If it doesn't exist, create it!
	 *
	 * @param int $user_id
	 * @param array|bool $contain
	 * @return array
	 */
	public function getPlayer($user_id, $contain = array('User')) {
		$profile = $this->find('first', array(
			'conditions' => array('Player.user_id' => $user_id),
			'contain' => $contain
		));

		if (!$profile && $user_id) {
			$this->create();
			$this->save(array('user_id' => $user_id), false);

			return $this->find('first', array(
				'conditions' => array('Player.id' => $this->id),
				'contain' => $contain
			));
		}

		return $profile;
	}

	/**
	 * Get a players profile and related data.
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function getPlayerProfile($user_id) {
		$player = $this->getPlayer($user_id, false);

		return $this->find('first', array(
			'conditions' => array('Player.id' => $player['Player']['id']),
			'contain' => array(
				'User',
				'Team',
				'Event' => array('League', 'Division')
			),
			'cache' => array(__METHOD__, $user_id)
		));
	}

}