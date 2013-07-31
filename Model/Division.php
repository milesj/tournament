<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Division extends TournamentAppModel {

	/**
	 * Has many.
	 *
	 * @type array
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
	 * @type array
	 */
	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'required' => true
		)
	);

	/**
	 * Admin settings.
	 *
	 * @type array
	 */
	public $admin = array(
		'iconClass' => 'icon-sitemap'
	);

}