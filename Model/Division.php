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
			'className' => 'Tournament.Event',
			'dependent' => true,
			'exclusive' => true
		)
	);

	/**
	 * Validation.
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'required' => true
		)
	);

}