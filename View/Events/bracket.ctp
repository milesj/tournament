<div class="page-header">
	<div class="buttons">
		<?php if ($event['Event']['for'] == Event::PLAYER) {
			echo $this->Html->link(__d('tournament', 'View Players'), array('action' => 'players', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug']), array('class' => 'btn btn-default'));
		} else {
			echo $this->Html->link(__d('tournament', 'View Teams'), array('action' => 'players', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug']), array('class' => 'btn btn-default'));
		} ?>
	</div>

	<h2><?php echo $event['Event']['name']; ?> - <?php echo __d('tournament', 'Brackets'); ?></h2>
</div>

<div class="page">
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
	} ?>
</div>