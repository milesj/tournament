<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Region extends TournamentAppModel {

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