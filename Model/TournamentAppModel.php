<?php

App::uses('CakeSession', 'Model/Datasource');

class TournamentAppModel extends AppModel {

	const YES = 1;
	const NO = 0;

	// Status flags
	const PENDING = 0;
	const ACTIVE = 1;
	const DISABLED = 2;

	// Participant flags
	const TEAM = 0;
	const PLAYER = 1;

	// Winner flags
	// PENDING = 0;
	const HOME = 1;
	const AWAY = 2;
	const NONE = 3;

	// Outcome flags
	// PENDING = 0;
	const WIN = 1;
	const LOSS = 2;
	const TIE = 3;
	const BYE = 4;

	/**
	 * Table prefix.
	 *
	 * @type string
	 */
	public $tablePrefix = TOURNAMENT_PREFIX;

	/**
	 * Database config.
	 *
	 * @type string
	 */
	public $useDbConfig = TOURNAMENT_DATABASE;

	/**
	 * Cache queries.
	 *
	 * @type boolean
	 */
	public $cacheQueries = true;

	/**
	 * No recursion.
	 *
	 * @type int
	 */
	public $recursive = -1;

	/**
	 * Behaviors.
	 *
	 * @type array
	 */
	public $actsAs = array(
		'Containable',
		'Utility.Enumerable',
		'Utility.Cacheable' => array(
			'cacheConfig' => 'tournament'
		)
	);

	/**
	 * Enum mapping.
	 *
	 * @type array
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
	 * @type CakeSession
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
	 * Use an md5 hash of the file name.
	 *
	 * @param string $name
	 * @param \Transit\File $file
	 * @return string
	 */
	public function formatFilename($name, $file) {
		return md5($name . time());
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
			'conditions' => array($this->alias . '.' . $this->primaryKey => $id),
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
			'conditions' => array($this->alias . '.slug' => $slug),
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

	/**
	 * Change a records status.
	 *
	 * @param int $id
	 * @param int $status
	 * @return mixed
	 */
	public function updateStatus($id, $status) {
		$this->id = $id;

		return $this->saveField('status', $status);
	}

}
