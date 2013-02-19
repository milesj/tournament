<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Match', 'Tournament.Model');

class BracketHelper extends AppHelper {

	/**
	 * Helpers.
	 *
	 * @var array
	 */
	public $helpers = array('Html');

	/**
	 * Bracket data.
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Store the bracket data to manipulate.
	 *
	 * @param array $data
	 */
	public function setup(array $data) {
		$this->data = $data;
	}

	/**
	 * Return a participant link based on ID.
	 *
	 * @param int $id
	 * @return string
	 */
	public function getParticipant($id) {
		if (empty($this->data['participants'][$id])) {
			return null;
		}

		$participant = $this->data['participants'][$id];

		if (isset($participant['User'])) {
			return $this->Html->link($participant['User'][Configure::read('Tournament.userMap.username')], array(
				'controller' => 'players',
				'action' => 'profile',
				'id' => $participant['user_id']
			));
		}

		return $this->Html->link($participant['name'], array(
			'controller' => 'teams',
			'action' => 'profile',
			'slug' => $participant['slug']
		));
	}

	/**
	 * Return the status of the match for the specific participant.
	 *
	 * @param int $id
	 * @param array $match
	 * @return string
	 */
	public function getMatchStatus($id, $match) {
		if ($match['winner'] == Match::PENDING) {
			return 'pending';

		} else if ($match['winner'] == Match::NONE) {
			return 'tie';
		}

		$status = 'loss';

		if (
			($match['home_id'] == $id && $match['winner'] == Match::HOME) ||
			($match['away_id'] == $id && $match['winner'] == Match::AWAY)
		) {
			$status = 'win';
		}

		return $status;
	}

}