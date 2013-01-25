<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Division extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'League' => array(
			'className' => 'Tournament.League'
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Season' => array(
			'className' => 'Tournament.Season'
		)
	);

}