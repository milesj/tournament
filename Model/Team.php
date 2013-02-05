<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Team extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Owner' => array(
			'className' => TOURNAMENT_USER
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'TeamMember' => array(
			'className' => 'Tournament.TeamMember',
			'dependent' => true,
			'exclusive' => true
		)
	);

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

	/**
	 * Validation rules.
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => 'notEmpty',
		'password' => 'notEmpty'
	);

	/**
	 * Before save.
	 *
	 * @param array $options
	 * @return bool
	 */
	public function beforeSave($options = array()) {
		if (isset($this->data['Team']['password'])) {
			$this->data['Team']['password'] = AuthComponent::password($this->data['Team']['password']);
		}

		return true;
	}

}