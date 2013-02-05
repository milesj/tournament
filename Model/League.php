<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class League extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Game' => array(
			'className' => 'Tournament.Game'
		),
		'Region' => array(
			'className' => 'Tournament.Region'
		)
	);

	/**
	 * Has one.
	 *
	 * @var array
	 */
	public $hasOne = array(
		'CurrentEvent' => array(
			'className' => 'Tournament.Event',
			'conditions' => array('CurrentEvent.isRunning' => self::YES),
			'limit' => 1
		)
	);

	/**
	 * Has many.
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Division' => array(
			'className' => 'Tournament.Division',
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
	 * Return a record based on ID.
	 *
	 * @param int $id
	 * @return array
	 */
	public function getById($id) {
		return $this->find('first', array(
			'conditions' => array('League.id' => $id),
			'contain' => array('Game', 'Region', 'Division', 'CurrentEvent'),
			'cache' => array(__METHOD__, $id)
		));
	}

}