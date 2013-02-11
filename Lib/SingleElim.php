<?php

App::uses('Tournament', 'Tournament.Lib');

class SingleElim extends Tournament {

	/**
	 * Fetch event information.
	 *
	 * @param array $event
	 * @throws Exception
	 */
	public function __construct($event) {
		parent::__construct($event);

		if ($this->_event['Event']['type'] != Event::SINGLE_ELIM) {
			throw new Exception('Event is not Single Elimination');
		}
	}

}