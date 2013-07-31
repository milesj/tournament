<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Game extends TournamentAppModel {

	/**
	 * Has many.
	 *
	 * @type array
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

	/**
	 * Validation.
	 *
	 * @type array
	 */
	public $validate = array(
		'name' => 'notEmpty'
	);

	/**
	 * Admin settings.
	 *
	 * @type array
	 */
	public $admin = array(
		'iconClass' => 'icon-screenshot',
		'imageFields' => array('image', 'imageSmall', 'imageIcon')
	);

}