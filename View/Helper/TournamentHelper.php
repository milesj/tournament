<?php

App::uses('AppHelper', 'View/Helper');

class TournamentHelper extends AppHelper {

	/**
	 * Helpers.
	 *
	 * @type array
	 */
	public $helpers = array('Time', 'Session');

	/**
	 * Determine the state of an event registration date.
	 *
	 * @param array $event
	 * @return string
	 */
	public function eventSignupDates($event) {
		if (empty($event['Event']['signupStart']) || empty($event['Event']['signupEnd'])) {
			return false;
		}

		$now = time();
		$start = strtotime($event['Event']['signupStart']);
		$end = strtotime($event['Event']['signupEnd']);

		if ($now > $end || $event['Event']['isFinished']) {
			return __d('tournament', 'Closed');

		} else if ($now < $start) {
			$time = $start;
			$message = 'Opens in %s';

		} else {
			$time = $end;
			$message = 'Open for %s';
		}

		return __d('tournament', $message, array(
			$this->Time->timeAgoInWords($time, array(
				'timezone' => $this->timezone()
			))
		));
	}

	/**
	 * Determine the state of an event play time date.
	 *
	 * @param array $event
	 * @return string
	 */
	public function eventPlayDates($event) {
		if (empty($event['Event']['start']) || empty($event['Event']['end'])) {
			return false;
		}

		$now = time();
		$start = strtotime($event['Event']['start']);
		$end = strtotime($event['Event']['end']);

		if ($now > $end || $event['Event']['isFinished']) {
			return __d('tournament', 'Finished');

		} else if ($now < $start) {
			$time = $start;
			$message = 'Starts in %s';

		} else {
			$time = $end;
			$message = 'Ends in %s';
		}

		return __d('tournament', $message, array(
			$this->Time->timeAgoInWords($time, array(
				'timezone' => $this->timezone()
			))
		));
	}

	/**
	 * Get the users timezone.
	 *
	 * @return string
	 */
	public function timezone() {
		if ($timezone = $this->Session->read(AuthComponent::$sessionKey . '.' . Configure::read('User.fieldMap.timezone'))) {
			return $timezone;
		}

		return Configure::read('Tournament.settings.defaultTimezone');
	}

}