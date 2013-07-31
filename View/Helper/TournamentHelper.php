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
	 * Return a translated equivalent of a model enum field.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return string
	 */
	public function options($key, $value) {
		list($model, $field) = explode('.', $key);

		if ($field === 'status') {
			$key = 'status';
		}

		switch ($key) {
			case 'Event.for':
				return __d('tournament', $value ? 'Solo' : 'Team');
			break;
			case 'EventParticipant.isReady':
				return __d('tournament', $value ? 'Yes' : 'No');
			break;
			default:
				return __d('tournament', strtolower($key . '.' . ClassRegistry::init('Tournament.' . $model)->enum($field, $value)));
			break;
		}
	}

	/**
	 * Get the users timezone.
	 *
	 * @return string
	 */
	public function timezone() {
		$timezone = Configure::read('User.fieldMap.timezone');
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