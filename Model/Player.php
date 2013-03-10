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
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'HomeMatch' => array(
			'className' => 'Tournament.Match',
			'foreignKey' => 'home_id',
			'conditions' => array('HomeMatch.type' => self::PLAYER),
			'dependent' => true,
			'exclusive' => true
		),
		'AwayMatch' => array(
			'className' => 'Tournament.Match',
			'foreignKey' => 'away_id',
			'conditions' => array('AwayMatch.type' => self::PLAYER),
			'dependent' => true,
			'exclusive' => true
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
	 * Validation.
	 *
	 * @var array
	 */
	public $validate = array(
		'user_id' => 'notEmpty',
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
		'iconClass' => 'icon-user'
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
				'Event' => array('League', 'Game', 'Division')
			),
			'cache' => array(__METHOD__, $user_id)
		));
	}

}