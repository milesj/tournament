<?php

App::uses('TournamentAppModel', 'Tournament.Model');
App::uses('Tournament', 'Tournament.Lib');

class MatchScore extends TournamentAppModel {

	/**
	 * Belongs to.
	 *
	 * @type array
	 */
	public $belongsTo = array(
		'Match' => array(
			'className' => 'Tournament.Match'
		),
		'HomeTeam' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'home_id'
		),
		'AwayTeam' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'away_id'
		),
		'HomePlayer' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'home_id'
		),
		'AwayPlayer' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'away_id'
		)
	);

	/**
	 * Enum mappings.
	 *
	 * @type array
	 */
	public $enum = array(
		'status' => array(
			self::PENDING => 'PENDING',
			self::WIN => 'WIN',
			self::LOSS => 'LOSS',
			self::TIE => 'TIE',
			self::BYE => 'BYE'
		)
	);

	/**
	 * Validation.
	 *
	 * @type array
	 */
	public $validate = array(
		'match_id' => 'notEmpty'
	);

	/**
	 * Admin settings.
	 *
	 * @type array
	 */
	public $admin = array(
		'iconClass' => 'icon-list-ol',
		'hideFields' => array('game'),
		'imageFields' => array('screenshot'),
		'fileFields' => array('replay')
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
			$transport['folder'] = 'tournament/matches/';
		}

		$attachment = array(
			'nameCallback' => 'formatFilename',
			'uploadDir' => WWW_ROOT . 'files/tournament/matches/',
			'finalPath' => 'files/tournament/matches/',
			'overwrite' => true,
			'stopSave' => true,
			'allowEmpty' => false,
			'transport' => $transport
		);

		$this->actsAs['Uploader.Attachment'] = array(
			'screenshot' => $attachment,
			'replay' => $attachment
		);

		$this->actsAs['Uploader.FileValidation'] = array(
			'screenshot' => array(
				'extension' => array('jpg', 'jpeg', 'png'),
				'type' => array('image/jpg', 'image/jpeg', 'image/png'),
				'required' => true
			),
			'replay' => array(
				// @todo
				//'extension' => array('jpg', 'jpeg', 'png'),
				//'type' => array('image/jpg', 'image/jpeg', 'image/png'),
				'required' => true
			)
		);

		parent::__construct($id, $table, $ds);
	}

	/**
	 * Organize replays and screenshots into their own folder.
	 *
	 * @param array $options
	 * @return array
	 */
	public function beforeUpload($options) {
		$folder = $options['dbColumn'] . 's/';

		$options['uploadDir'] = $options['uploadDir'] . $folder;
		$options['finalPath'] = $options['finalPath'] . $folder;

		if (isset($options['transport']['folder'])) {
			$options['transport']['folder'] = $options['transport']['folder'] . $folder;
		}

		return $options;
	}

}