<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class TeamsUser extends TournamentAppModel {

	const MEMBER = 0;
	const LEADER = 1;
	const MANAGER = 2;
	const SUB = 3;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Team' => array(
			'className' => 'Tournament.Team'
		),
		'User' => array(
			'className' => TOURNAMENT_USER
		)
	);

	/**
	 * Enum mappings.
	 *
	 * @var array
	 */
	public $enum = array(
		'role' => array(
			self::MEMBER => 'MEMBER',
			self::LEADER => 'LEADER',
			self::MANAGER => 'MANAGER',
			self::SUB => 'SUB'
		)
	);

}