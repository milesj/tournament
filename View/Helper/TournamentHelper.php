<?php

App::uses('AppHelper', 'View/Helper');

class TournamentHelper extends AppHelper {

	public $helpers = array('Time', 'Session');

	public function setupFor($for) {
		return __d('tournament', $for ? 'Player' : 'Team');
	}

	public function eventType($type) {
		return __d('tournament', 'event.type.' . strtolower($type));
	}

	public function eventRegistration($event) {
		if (empty($event['Event']['signupEnd']) || empty($event['Event']['signupEnd'])) {
			return false;
		}

		$now = time();
		$start = strtotime($event['Event']['signupStart']);
		$end = strtotime($event['Event']['signupEnd']);

		if ($now < $start) {
			return __d('tournament', 'Opens %s', array(
				$this->Time->niceShort($start, $this->timezone())
			));

		} else if ($now > $end) {
			return __d('tournament', 'Closed');
		}

		return __d('tournament', 'Open until %s', array(
			$this->Time->niceShort($end, $this->timezone())
		));
	}

	/**
	 * Get the users timezone.
	 *
	 * @return string
	 */
	public function timezone() {
		$timezone = Configure::read('Tournament.userMap.timezone');
		$default = Configure::read('Tournament.settings.defaultTimezone');

		if (!$timezone) {
			return $default;
		}

		if ($this->Session->check('Auth.User.' . $timezone)) {
			return $this->Session->read('Auth.User.' . $timezone);
		}

		return $default;
	}

}