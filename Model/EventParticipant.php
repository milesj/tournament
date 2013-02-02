<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class EventParticipant extends TournamentAppModel {

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
		),
		'Player' => array(
			'className' => 'Tournament.Player'
		)
	);

}