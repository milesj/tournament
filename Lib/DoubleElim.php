<?php

App::uses('SingleElim', 'Tournament.Lib');

class DoubleElim extends SingleElim {

	/**
	 * Validate the event is the correct type for the class.
	 */
	public function validate() {
		if ($this->_event['type'] != Event::DOUBLE_ELIM) {
			throw new Exception('Event is not Double Elimination');
		}
	}

}