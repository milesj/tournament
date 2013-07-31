<?php

App::uses('TournamentAppModel', 'Tournament.Model');
App::uses('Tournament', 'Tournament.Lib');

class Match extends TournamentAppModel {

	// Bracket
	const WINNERS = 0;
	const LOSERS = 1;

	/**
	 * Belongs to.
	 *
	 * @type array
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
	 * Has many.
	 *
	 * @type array
	 */
	public $hasMany = array(
		'MatchScore' => array(
			'className' => 'Tournament.MatchScore',
			'dependent' => true,
			'exclusive' => true
		)
	);

	/**
	 * Enum mappings.
	 *
	 * @type array
	 */
	public $enum = array(
		'type' => array(
			self::TEAM => 'TEAM',
			self::PLAYER => 'PLAYER'
		),
		'bracket' => array(
			self::WINNERS => 'WINNERS',
			self::LOSERS => 'LOSERS'
		),
		'winner' => array(
			self::PENDING => 'PENDING',
			self::HOME => 'HOME',
			self::AWAY => 'AWAY',
			self::NONE => 'NONE'
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
	 * Validation.
	 *
	 * @type array
	 */
	public $validate = array(
		'league_id' => 'notEmpty',
		'event_id' => 'notEmpty',
		'home_id' => 'notEmpty',
		'away_id' => 'notEmpty',
		'type' => 'notEmpty',
		'bracket' => 'notEmpty',
		'winner' => 'notEmpty',
		'homeOutcome' => 'notEmpty',
		'awayOutcome' => 'notEmpty',
		'homePoints' => array(
			'rule' => 'numeric',
			'message' => 'May only contain numerical characters'
		),
		'awayPoints' => array(
			'rule' => 'numeric',
			'message' => 'May only contain numerical characters'
		),
	);

	/**
	 * Admin settings.
	 *
	 * @type array
	 */
	public $admin = array(
		'iconClass' => 'icon-table',
		'hideFields' => array('round', 'pool', 'order')
	);

	/**
	 * Return all matches for an event.
	 *
	 * @param int $event_id
	 * @param int $type
	 * @return array
	 */
	public function getMatches($event_id, $type) {
		$query = array(
			'conditions' => array('Match.event_id' => $event_id),
			'order' => array('Match.order' => 'ASC'),
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

	/**
	 * Return all matches that haven't been played yet.
	 *
	 * @param int $event_id
	 * @return array
	 */
	public function getPendingMatches($event_id) {
		return $this->find('all', array(
			'conditions' => array(
				'Match.event_id' => $event_id,
				'Match.winner' => self::PENDING
			),
			'order' => array('Match.order' => 'ASC')
		));
	}

	/**
	 * Return all matches for an event in the correct bracket order.
	 *
	 * @param array $event
	 * @return array
	 */
	public function getBrackets($event) {
		$matches = $this->find('all', array(
			'conditions' => array('Match.event_id' => $event['Event']['id']),
			'order' => array('Match.order' => 'ASC'),
			'contain' => array('MatchScore'),
			'cache' => array(__METHOD__, $event['Event']['id'])
		));

		return Tournament::factory($event)->organizeBrackets($matches);
	}

}