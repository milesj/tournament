<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Region extends TournamentAppModel {

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
		),
		'slug' => 'notEmpty'
	);

}