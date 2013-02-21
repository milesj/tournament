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
	 * Return a participant icon based on the defined data.
	 *
	 * @param array $participant
	 * @return string
	 */
	public function participantIcon($participant) {
		if (isset($participant['User'])) {
			$avatar = Configure::read('Tournament.userMap.avatar');
			$icon = isset($participant['User'][$avatar]) ? $participant['User'][$avatar] : null;
		} else {
			$icon = $participant['logo'];
		}

		if (empty($icon)) {
			return null;
		}

		return $this->Html->image($icon);
	}

	/**
	 * Return a participant link based on the defined data.
	 *
	 * @param array $participant
	 * @return string
	 */
	public function participantLink($participant) {
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
	 * Return the points score for the match for the specific participant.
	 *
	 * @param int $participant_id
	 * @param array $match
	 * @return array
	 */
	public function matchScore($participant_id, $match) {
		if ($match['winner'] == Match::PENDING) {
			return null;
		}

		if ($match['home_id'] == $participant_id) {
			$winPoint = $match['homeScore'];
			$lossPoint = $match['awayScore'];
		} else {
			$winPoint = $match['awayScore'];
			$lossPoint = $match['homeScore'];
		}

		return array($winPoint, $lossPoint);
	}

	/**
	 * Return the status of the match for the specific participant.
	 *
	 * @param int $participant_id
	 * @param array $match
	 * @return string
	 */
	public function matchStatus($participant_id, $match) {
		if ($match['winner'] == Match::PENDING) {
			return 'pending';

		} else if ($match['winner'] == Match::NONE) {
			return 'tie';
		}

		$status = 'loss';

		if (
			($match['home_id'] == $participant_id && $match['winner'] == Match::HOME) ||
			($match['away_id'] == $participant_id && $match['winner'] == Match::AWAY)
		) {
			$status = 'win';
		}

		return $status;
	}

	/**
	 * Display a number with its ordinal indicator.
	 *
	 * @param int $num
	 * @return string
	 */
	public function standing($num) {
		if (!$num) {
			return $num;
		}

		if (!in_array(($num % 100), array(11, 12, 13))) {
			switch ($num % 10) {
				case 1: $message = '%sst'; break;
				case 2: $message = '%snd'; break;
				case 3: $message = '%srd'; break;
				default: $message = '%sth'; break;
			}

			return __($message, $num);
		}

		return $num;
	}

}