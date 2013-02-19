<?php

App::uses('SingleElim', 'Tournament.Lib');

class DoubleElim extends SingleElim {

	/**
	 * Organize a list of matches into the correct match order for brackets.
	 *
	 * @param array $matches
	 * @return array
	 */
	public function organizeBrackets($matches) {
		return $matches;
	}

	/**
	 * Validate the event is the correct type for the class.
	 */
	public function validate() {
		if ($this->_event['type'] != Event::DOUBLE_ELIM) {
			throw new Exception('Event is not Double Elimination');
		}
	}

}