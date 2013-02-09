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
	 * Has and belongs to many.
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'Team' => array(
			'className' => 'Tournament.Team',
			'with' => 'Tournament.TeamMember'
		)
	);

	/**
	 * Grab the users player profile. If it doesn't exist, create it!
	 *
	 * @param int $user_id
	 * @param array $contain
	 * @return array
	 */
	public function getPlayerProfile($user_id, array $contain = array('User')) {
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

}