<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class SeasonsTeam extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Season' => array(
			'className' => 'Tournament.Season'
		),
		'Team' => array(
			'className' => 'Tournament.Team'
		)
	);

}