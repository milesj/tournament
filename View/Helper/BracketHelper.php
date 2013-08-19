<?php

App::uses('AppHelper', 'View/Helper');
App::uses('Match', 'Tournament.Model');

class BracketHelper extends AppHelper {

	/**
	 * Helpers.
	 *
	 * @type array
	 */
	public $helpers = array('Html');

	/**
	 * Return a formatted piece of data about a participant.
	 *
	 * @param array $participant
	 * @param string $return
	 * @return string
	 */
	public function participant($participant, $return = 'both') {
		if (isset($participant['Player'])) {
			$participant = $participant['Player'];
		} else if (isset($participant['Team'])) {
			$participant = $participant['Team'];
		}

		$userMap = Configure::read('User.fieldMap');

		if (isset($participant['User'])) {
			$type = 'player';
			$name = isset($participant['User'][$userMap['username']]) ? $participant['User'][$userMap['username']] : null;
			$path = isset($participant['User'][$userMap['avatar']]) ? $participant['User'][$userMap['avatar']] : null;
			$url = array(
				'controller' => 'players',
				'action' => 'profile',
				'id' => $participant['user_id']
			);

		} else {
			$type = 'team';
			$name = $participant['name'];
			$path = $participant['logo'];
			$url = array(
				'controller' => 'teams',
				'action' => 'profile',
				'slug' => $participant['slug']
			);
		}

		switch ($return) {
			case 'url':
				return $url;
			break;
			case 'logo':
				if (!$path) {
					return null;
				}

				return $this->Html->tag('span', $this->Html->image($path), 'logo logo-' . $type);
			break;
			case 'logo-link':
				if (!$path) {
					return null;
				}

				return $this->Html->link($this->participant($participant, 'logo'), $url, array('escape' => false));
			break;
			case 'link':
				return $this->Html->link($name, $url);
			break;
		}

		// Combine both
		$output = $name;

		if ($path) {
			$output = $this->participant($participant, 'logo') . $output;
		}

		return $this->Html->link($output, $url, array(
			'escape' => false,
			'class' => 'participant-link ' . ($path ? 'has-logo' : 'no-logo')
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
		if ($match['Match']['winner'] == Match::PENDING) {
			return null;
		}

		if ($match['Match']['home_id'] == $participant_id) {
			$winPoint = $match['Match']['homePoints'];
			$lossPoint = $match['Match']['awayPoints'];
		} else {
			$winPoint = $match['Match']['awayPoints'];
			$lossPoint = $match['Match']['homePoints'];
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
		if ($match['Match']['winner'] == Match::PENDING) {
			return 'pending';

		} else if ($match['Match']['winner'] == Match::NONE) {
			return 'tie';
		}

		$status = 'loss';

		if (
			($match['Match']['home_id'] == $participant_id && $match['Match']['winner'] == Match::HOME) ||
			($match['Match']['away_id'] == $participant_id && $match['Match']['winner'] == Match::AWAY)
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

		return __('%sth', $num);
	}

}