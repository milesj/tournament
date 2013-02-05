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
		),
		'UpcomingEvent' => array(
			'className' => 'Tournament.Event',
			'conditions' => array(
				'UpcomingEvent.isRunning' => self::NO,
				'UpcomingEvent.isFinished' => self::NO
			),
			'order' => array('UpcomingEvent.start' => 'ASC'),
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
			'contain' => array('Game', 'Region', 'Division', 'CurrentEvent', 'UpcomingEvent'),
			'cache' => array(__METHOD__, $id)
		));
	}

	/**
	 * Return a record based on slug.
	 *
	 * @param string $slug
	 * @return array
	 */
	public function getBySlug($slug) {
		return $this->find('first', array(
			'conditions' => array('League.slug' => $slug),
			'contain' => array('Game', 'Region', 'Division', 'CurrentEvent', 'UpcomingEvent'),
			'cache' => array(__METHOD__, $slug)
		));
	}

	/**
	 * Return statistics for a league.
	 *
	 * @param int $id
	 * @return array
	 */
	public function getStats($id) {
		return array();
	}

}