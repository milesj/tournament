<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Division extends TournamentAppModel {

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Event' => array(
			'className' => 'Tournament.Event'
		)
	);

}