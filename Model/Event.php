<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Event extends TournamentAppModel {

	const SINGLE_ELIM = 0;
	const DOUBLE_ELIM = 1;
	const ROUND_ROBIN = 2;
	const SWISS = 3;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'League' => array(
			'className' => 'Tournament.League'
		),
		'Division' => array(
			'className' => 'Tournament.Division'
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
		)
	);

}