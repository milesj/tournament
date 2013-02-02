<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Event extends TournamentAppModel {

	// Type
	const SINGLE_ELIM = 0;
	const DOUBLE_ELIM = 1;
	const ROUND_ROBIN = 2;
	const SWISS = 3;

	// For
	const TEAM = 0;
	const PLAYER = 1;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Game' => array(
			'className' => 'Tournament.Game'
		),
		'League' => array(
			'className' => 'Tournament.League'
		),
		'Division' => array(
			'className' => 'Tournament.Division'
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Team' => array(
			'className' => 'Tournament.EventParticipant',
			'conditions' => array('Event.for' => self::TEAM, 'Team.team_id !=' => ''),
			'dependent' => true,
			'exclusive' => true
		),
		'Player' => array(
			'className' => 'Tournament.EventParticipant',
			'conditions' => array('Event.for' => self::PLAYER, 'Player.player_id !=' => ''),
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
	 * Enum mappings.
	 *
	 * @var array
	 */
	public $enum = array(
		'type' => array(
			self::SINGLE_ELIM => 'SINGLE_ELIM',
			self::DOUBLE_ELIM => 'DOUBLE_ELIM',
			self::ROUND_ROBIN => 'ROUND_ROBIN',
			self::SWISS => 'SWISS'
		),
		'for' => array(
			self::TEAM => 'TEAM',
			self::PLAYER => 'PLAYER'
		)
	);

}