<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Player extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => TOURNAMENT_USER
		)
	);

}