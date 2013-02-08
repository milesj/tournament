<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Team extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Leader' => array(
			'className' => TOURNAMENT_USER,
			'foreignKey' => 'user_id'
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'TeamMember' => array(
			'className' => 'Tournament.TeamMember',
			'dependent' => true,
			'exclusive' => true
		)
	);

	/**
	 * Behaviors.
	 *
	 * @var array
	 */
	public $actsAs = array(
		'Utility.Sluggable' => array(
			'field' => 'name'
		)
	);

	/**
	 * Validation rules.
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => 'notEmpty',
		'password' => 'notEmpty'
	);

	/**
	 * Disband a team.
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function disband($id) {
		$this->TeamMember->updateAll(
			array('TeamMember.status' => TeamMember::DISBANDED),
			array('TeamMember.team_id' => $id)
		);

		return $this->updateStatus($id, self::DELETED);
	}

}