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