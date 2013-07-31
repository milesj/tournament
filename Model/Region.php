<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Region extends TournamentAppModel {

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
		)
	);

	/**
	 * Validation.
	 *
	 * @type array
	 */
	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'required' => true
		),
		'slug' => 'notEmpty'
	);

	/**
	 * Admin settings.
	 *
	 * @type array
	 */
	public $admin = array(
		'iconClass' => 'icon-globe'
	);

}