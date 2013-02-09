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

	// Seed
	const RANDOM = 0;
	const POINTS = 1;

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
		'EventParticipant' => array(
			'className' => 'Tournament.EventParticipant',
			'dependent' => true,
			'exclusive' => true
		),
		'Match' => array(
			'className' => 'Tournament.Match',
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
		),
		'seed' => array(
			self::RANDOM => 'RANDOM',
			self::POINTS => 'POINTS'
		)
	);

	/**
	 * Return all events for a league.
	 *
	 * @param int $league_id
	 * @return array
	 */
	public function getEventsInLeague($league_id) {
		return $this->find('all', array(
			'conditions' => array('Event.league_id' => $league_id),
			'order' => array('Event.start' => 'DESC'),
			'contain' => array('Division'),
			'cache' => array(__METHOD__, $league_id)
		));
	}

}