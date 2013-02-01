<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class EventsTeam extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Tournament.Event'
		),
		'Team' => array(
			'className' => 'Tournament.Team'
		)
	);

}