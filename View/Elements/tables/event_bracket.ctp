<?php
$this->Html->css('Tournament.bracket', null, array('inline' => false));

switch ($event['Event']['type']) {
	case Event::SINGLE_ELIM:
		echo $this->element('brackets/single_elim');
	break;
	case Event::DOUBLE_ELIM:
		echo $this->element('brackets/double_elim');
	break;
	case Event::ROUND_ROBIN:
		echo $this->element('brackets/round_robin');
	break;
	case Event::SWISS:
		echo $this->element('brackets/swiss');
	break;
}