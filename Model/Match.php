<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class Match extends TournamentAppModel {

	// Winner
	const HOME = 1;
	const AWAY = 2;

	// Outcome
	const WIN = 1;
	const LOSS = 2;
	const TIE = 3;
	const BYE = 4;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'League' => array(
			'className' => 'Tournament.League'
		),
		'Event' => array(
			'className' => 'Tournament.Event',
			'counterCache' => true
		),
		'HomeTeam' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'home_id',
			'conditions' => array('Match.type' => self::TEAM)
		),
		'AwayTeam' => array(
			'className' => 'Tournament.Team',
			'foreignKey' => 'away_id',
			'conditions' => array('Match.type' => self::TEAM)
		),
		'HomePlayer' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'home_id',
			'conditions' => array('Match.type' => self::PLAYER)
		),
		'AwayPlayer' => array(
			'className' => 'Tournament.Player',
			'foreignKey' => 'away_id',
			'conditions' => array('Match.type' => self::PLAYER)
		)
	);

	/**
	 * Enum mappings.
	 *
	 * @var array
	 */
	public $enum = array(
		'type' => array(
			self::TEAM => 'TEAM',
			self::PLAYER => 'PLAYER'
		),
		'winner' => array(
			self::PENDING => 'PENDING',
			self::HOME => 'HOME',
			self::AWAY => 'AWAY'
		),
		'homeOutcome' => array(
			self::PENDING => 'PENDING',
			self::WIN => 'WIN',
			self::LOSS => 'LOSS',
			self::TIE => 'TIE',
			self::BYE => 'BYE'
		),
		'awayOutcome' => array(
			self::PENDING => 'PENDING',
			self::WIN => 'WIN',
			self::LOSS => 'LOSS',
			self::TIE => 'TIE',
			self::BYE => 'BYE'
		)
	);

	/**
	 * Return all matches for an event bracket.
	 *
	 * @param int $event_id
	 * @param int $type
	 * @return array
	 */
	public function getBrackets($event_id, $type = self::TEAM) {
		$query = array(
			'conditions' => array('Match.event_id' => $event_id),
			'order' => array('Match.bracket' => 'ASC'),
			'cache' => array(__METHOD__, $event_id, $type)
		);

		if ($type == self::TEAM) {
			$query['contain'] = array('HomeTeam', 'AwayTeam');
		} else {
			$query['contain'] = array(
				'HomePlayer' => array('User'),
				'AwayPlayer' => array('User')
			);
		}

		return $this->find('all', $query);
	}


}