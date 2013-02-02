<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Game extends TournamentAppModel {

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'League' => array(
			'className' => 'Tournament.League',
			'dependent' => true,
			'exclusive' => true
		),
		'Event' => array(
			'className' => 'Tournament.Event',
			'dependent' => true,
			'exclusive' => true
		)
	);

}