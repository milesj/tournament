<?php

App::uses('CakeSession', 'Model/Datasource');

class TournamentAppModel extends AppModel {

	/**
	 * Toggleable constants.
	 */
	const YES = 1;
	const NO = 0;

	/**
	 * Status enums.
	 */
	const PENDING = 0;
	const ACTIVE = 1;
	const DISABLED = 2;

	/**
	 * Table prefix.
	 *
	 * @var string
	 */
	public $tablePrefix = 'tourn_';

	/**
	 * Database config.
	 *
	 * @var string
	 */
	public $useDbConfig = 'default';

	/**
	 * Cache queries.
	 *
	 * @var boolean
	 */
	public $cacheQueries = true;

	/**
	 * No recursion.
	 *
	 * @var int
	 */
	public $recursive = -1;

	/**
	 * Behaviors.
	 *
	 * @var array
	 */
	public $actsAs = array(
		'Containable',
		'Utility.Enumerable' => array(
			'persist' => false,
			'format' => false
		),
		'Utility.Cacheable' => array(
			'cacheConfig' => 'forum',
			'appendKey' => false,
			'expires' => '+1 hour',
			'events' => array(
				'onCreate' => false
			)
		)
	);

	/**
	 * Enum mapping.
	 *
	 * @var array
	 */
	public $enum = array(
		'status' => array(
			self::PENDING => 'PENDING',
			self::ACTIVE => 'ACTIVE',
			self::DISABLED => 'DISABLED'
		)
	);

	/**
	 * Session instance.
	 *
	 * @var CakeSession
	 */
	public $Session;

	/**
	 * Allow the model to interact with the session.
	 *
	 * @param int $id
	 * @param string $table
	 * @param string $ds
	 */
	public function __construct($id = null, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->Session = new CakeSession();
	}

	/**
	 * Return all records.
	 *
	 * @return array
	 */
	public function getAll() {
		return $this->find('all', array(
			'contain' => false,
			'cache' => $this->alias . '::' . __FUNCTION__
		));
	}

	/**
	 * Return all records as a list.
	 *
	 * @return array
	 */
	public function getList() {
		return $this->find('list', array(
			'contain' => false,
			'cache' => $this->alias . '::' . __FUNCTION__
		));
	}

	/**
	 * Return a record based on ID.
	 *
	 * @param int $id
	 * @return array
	 */
	public function getById($id) {
		return $this->find('first', array(
			'conditions' => array('id' => $id),
			'contain' => array_keys($this->belongsTo),
			'cache' => array($this->alias . '::' . __FUNCTION__, $id)
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
			'conditions' => array('slug' => $slug),
			'contain' => array_keys($this->belongsTo),
			'cache' => array($this->alias . '::' . __FUNCTION__, $slug)
		));
	}

	/**
	 * Get a count of all rows.
	 *
	 * @return int
	 */
	public function getTotal() {
		return $this->find('count', array(
			'contain' => false,
			'recursive' => false,
			'cache' => $this->alias . '::' . __FUNCTION__,
			'cacheExpires' => '+24 hours'
		));
	}

	/**
	 * Adds locale functions to errors.
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param mixed $param
	 * @return boolean
	 */
	public function invalidate($field, $value = true, $param = '') {
		parent::invalidate($field, sprintf(__d('tournament', $value), $param));

		return false;
	}

}
