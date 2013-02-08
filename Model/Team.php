<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Team extends TournamentAppModel {

	const DISBANDED = 3;

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
	 * Enum mapping.
	 *
	 * @var array
	 */
	public $enum = array(
		'status' => array(
			self::PENDING => 'PENDING',
			self::ACTIVE => 'ACTIVE',
			self::DISABLED => 'DISABLED',
			self::DISBANDED => 'DISBANDED'
		)
	);

	/**
	 * Disband a team.
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function disband($id) {
		$team = $this->getById($id);

		if (!$team) {
			return true;
		}

		$this->TeamMember->updateAll(
			array('TeamMember.status' => TeamMember::DISBANDED),
			array('TeamMember.team_id' => $id)
		);

		$this->id = $id;
		$this->deleteFiles($id); // uploader

		return $this->save(array(
			'name' => sprintf('%s (%s)', $team['Team']['name'], __d('tournament', 'Disbanded')),
			'status' => self::DISBANDED
		), false);
	}

}