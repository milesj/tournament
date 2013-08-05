<div class="title">
	<div class="buttons">
		<?php if ($event['Event']['for'] == Event::PLAYER) {
			echo $this->Html->link(__d('tournament', 'View Players'), array('action' => 'players', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug']), array('class' => 'btn btn-default'));
		} else {
			echo $this->Html->link(__d('tournament', 'View Teams'), array('action' => 'players', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug']), array('class' => 'btn btn-default'));
		} ?>
	</div>

	<h2><?php echo $event['Event']['name']; ?> - <?php echo __d('tournament', 'Brackets'); ?></h2>
</div>

<div class="container">
	<?php echo $this->element('tables/event_bracket'); ?>
</div>