<?php
debug($team);

if ($user && $team['Team']['status'] == Team::ACTIVE) {
	if ($member) {
		echo $this->Html->link('Leave Team', array('plugin' => 'tournament', 'action' => 'leave', 'slug' => $team['Team']['slug']));
	} else {
		echo $this->Html->link('Join Team', array('plugin' => 'tournament', 'action' => 'join', 'slug' => $team['Team']['slug']));
	}
}