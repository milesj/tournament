<?php

App::uses('SingleElim', 'Tournament.Lib');

class DoubleElim extends SingleElim {

	/**
	 * Event type.
	 *
	 * @type int
	 */
	protected $_type = Event::DOUBLE_ELIM;

	/**
	 * Organize a list of matches into the correct match order for brackets.
	 *
	 * @param array $matches
	 * @return array
	 */
	public function organizeBrackets($matches) {
		return $matches;
	}

}