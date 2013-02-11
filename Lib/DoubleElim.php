<?php

App::uses('SingleElim', 'Tournament.Lib');

class DoubleElim extends SingleElim {

	/**
	 * Fetch event information.
	 *
	 * @param array $event
	 * @throws Exception
	 */
	public function __construct($event) {
		parent::__construct($event);

		if ($this->_event['type'] != Event::DOUBLE_ELIM) {
			throw new Exception('Event is not Double Elimination');
		}
	}

}