<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class League extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Game' => array(
			'className' => 'Tournament.Game'
		),
		'Region' => array(
			'className' => 'Tournament.Region'
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Division' => array(
			'className' => 'Tournament.Division',
			'dependent' => true,
			'exclusive' => true
		),
		'Event' => array(
			'className' => 'Tournament.Event',
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

}