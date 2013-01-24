<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class TeamsUser extends TournamentAppModel {

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

}