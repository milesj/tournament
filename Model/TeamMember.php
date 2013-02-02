<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class TeamMember extends TournamentAppModel {

	// Roles
	const MEMBER = 0;
	const LEADER = 1;
	const MANAGER = 2;
	const SUB = 3;

	// Status
	const PENDING = 0;
	const ACTIVE = 1;
	const REMOVED = 2; // Removed by captain
	const QUIT = 3; // Left team personally

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Team' => array(
			'className' => 'Tournament.Team',
			'counterCache' => true
		),
		'Player' => array(
			'className' => 'Tournament.Player'
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
		),
		'status' => array(
			self::PENDING => 'PENDING',
			self::ACTIVE => 'ACTIVE',
			self::REMOVED => 'REMOVED',
			self::QUIT => 'QUIT'
		)
	);

}