<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Team extends TournamentAppModel {

	const DISBANDED = 3;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Leader' => array(
			'className' => TOURNAMENT_USER,
			'foreignKey' => 'user_id'
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
	 * Has and belongs to many.
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array(
		'Event' => array(
			'className' => 'Tournament.Event',
			'with' => 'Tournament.EventParticipant',
			'order' => array('EventParticipant.created' => 'DESC')
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
	 * Enum mapping.
	 *
	 * @var array
	 */
	public $enum = array(
		'status' => array(
			self::PENDING => 'PENDING',
			self::ACTIVE => 'ACTIVE',
			self::DISABLED => 'DISABLED',
			self::DISBANDED => 'DISBANDED'
		)
	);

	/**
	 * Configure Uploader manually.
	 *
	 * @param bool|int $id
	 * @param string $table
	 * @param string $ds
	 */
	public function __construct($id = false, $table = null, $ds = null) {
		$config = Configure::read('Tournament.uploads');
		$transport = $config['transport'];

		if ($transport) {
			$transport['folder'] = 'tournament/teams/';
		}

		$this->actsAs['Uploader.Attachment'] = array(
			'logo' => array(
				'nameCallback' => 'formatLogo',
				'uploadDir' => WWW_ROOT . 'files/tournament/teams/',
				'finalPath' => 'files/tournament/teams/',
				'dbColumn' => 'logo',
				'overwrite' => true,
				'stopSave' => true,
				'allowEmpty' => false,
				'transport' => $transport,
				'transforms' => array(
					'logo' => array(
						'method' => 'crop',
						'width' => $config['teamLogo'][0],
						'height' => $config['teamLogo'][1],
						'self' => true,
						'overwrite' => true
					)
				)
			)
		);

		$this->actsAs['Uploader.FileValidation'] = array(
			'logo' => array(
				'minWidth' => $config['teamLogo'][0],
				'minHeight' => $config['teamLogo'][1],
				'extension' => array('gif', 'jpg', 'jpeg', 'png'),
				'type' => array('image/gif', 'image/jpg', 'image/jpeg', 'image/png'),
				'required' => true
			)
		);

		parent::__construct($id, $table, $ds);
	}

	/**
	 * Disband a team.
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function disband($id) {
		$team = $this->getById($id);

		if (!$team) {
			return true;
		}

		$this->TeamMember->updateAll(
			array('TeamMember.status' => TeamMember::DISBANDED),
			array(
				'TeamMember.team_id' => $id,
				'TeamMember.status <=' => TeamMember::ACTIVE
			)
		);

		$this->id = $id;
		$this->deleteFiles($id); // uploader

		return $this->save(array(
			'name' => sprintf('%s (%s)', $team['Team']['name'], __d('tournament', 'Disbanded')),
			'status' => self::DISBANDED
		), false);
	}

	/**
	 * Get a team profile and related data.
	 *
	 * @param string $slug
	 * @return array
	 */
	public function getTeamProfile($slug) {
		return $this->find('first', array(
			'conditions' => array('Team.slug' => $slug),
			'contain' => array(
				'Leader',
				'TeamMember' => array('Player', 'User'),
				'Event' => array('Game', 'League', 'Division')
			),
			'cache' => array(__METHOD__, $slug)
		));
	}

	/**
	 * Use team slug as logo name.
	 *
	 * @param string $name
	 * @param \Transit\File $file
	 * @return string
	 */
	public function formatLogo($name, $file) {
		if ($this->id) {
			if ($team = $this->getById($this->id)) {
				return $team['Team']['slug'];
			}
		}

		return md5($name);
	}

	/**
	 * Set required for both create and update.
	 *
	 * @param array $options
	 * @return bool
	 */
	public function beforeValidate($options = array()) {
		unset($this->validate['logo']['required']['on']);

		return true;
	}

}