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
		'HomeTeam' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'home_id',
			'conditions' => array('Match.type' => self::TEAM)
		),
		'AwayTeam' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'away_id',
			'conditions' => array('Match.type' => self::TEAM)
		),
		'HomePlayer' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'home_id',
			'conditions' => array('Match.type' => self::PLAYER)
		),
		'AwayPlayer' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'away_id',
			'conditions' => array('Match.type' => self::PLAYER)
		)
	);



}