<?php

switch ($event['Event']['type']) {
	case Event::SINGLE_ELIM:
		echo $this->element('brackets/single_elim', array('bracket' => $bracket));
	break;
	case Event::DOUBLE_ELIM:
		echo $this->element('brackets/double_elim', array('bracket' => $bracket));
	break;
	case Event::ROUND_ROBIN:
		echo $this->element('brackets/round_robin', array('bracket' => $bracket));
	break;
	case Event::SWISS:
		echo $this->element('brackets/swiss', array('bracket' => $bracket));
	break;
}