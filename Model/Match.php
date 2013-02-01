<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Match extends TournamentAppModel {

	// Type
	const TEAM = 0;
	const PLAYER = 1;

	// Outcome
	const PENDING = 0;
	const WIN = 1;
	const LOSS = 2;
	const TIE = 3;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'League' => array(
			'className' => 'Tournament.League'
		),
		'Event' => array(
			'className' => 'Tournament.Event'
		),
		'TeamA' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'a_id',
			'conditions' => array('Match.type' => self::TEAM)
		),
		'TeamB' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'b_id',
			'conditions' => array('Match.type' => self::TEAM)
		),
		'PlayerA' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'a_id',
			'conditions' => array('Match.type' => self::PLAYER)
		),
		'PlayerB' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'b_id',
			'conditions' => array('Match.type' => self::PLAYER)
		)
	);



}