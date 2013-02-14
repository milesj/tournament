<?php

App::uses('AppHelper', 'View/Helper');

class TournamentHelper extends AppHelper {

	/**
	 * Helpers.
	 *
	 * @var array
	 */
	public $helpers = array('Time', 'Session');

	/**
	 * Determine the state of an event registration date.
	 *
	 * @param array $event
	 * @return string
	 */
	public function eventRegistration($event) {
		if (empty($event['Event']['signupEnd']) || empty($event['Event']['signupEnd'])) {
			return false;
		}

		$now = time();
		$start = strtotime($event['Event']['signupStart']);
		$end = strtotime($event['Event']['signupEnd']);

		if ($now > $end || $event['Event']['isFinished']) {
			return __d('tournament', 'Closed');

		} else if ($now < $start) {
			return __d('tournament', 'Opens %s', array(
				$this->Time->niceShort($start, $this->timezone())
			));
		}

		return __d('tournament', 'Open until %s', array(
			$this->Time->niceShort($end, $this->timezone())
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

		} else if ($field === 'for') {
			return __d('tournament', $value ? 'Player' : 'Team');
		}

		return __d('tournament', strtolower($key . '.' . ClassRegistry::init('Tournament.' . $model)->enum($field, $value)));
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