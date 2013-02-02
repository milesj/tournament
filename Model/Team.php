<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Team extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Owner' => array(
			'className' => TOURNAMENT_USER
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Member' => array(
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

}