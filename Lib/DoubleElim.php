<?php

App::uses('Tournament', 'Tournament.Lib');

class DoubleElim extends Tournament {

	/**
	 * Fetch event information.
	 *
	 * @param int $id
	 * @throws Exception
	 */
	public function __construct($id) {
		parent::__construct($id);

		if ($this->_event['Event']['type'] != Event::DOUBLE_ELIM) {
			throw new Exception('Event is not Double Elimination');
		}
	}

}