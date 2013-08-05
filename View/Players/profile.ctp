<div class="title">
	<h2><?php echo $player['User'][$userFields['username']]; ?></h2>
</div>

<div class="container">
	<dl class="dl-horizontal">
		<dt><?php echo __d('tournament', 'Points'); ?></dt> <dd><?php echo number_format($player['Player']['points']); ?></dd>
		<dt><?php echo __d('tournament', 'Wins'); ?></dt> <dd><?php echo number_format($player['Player']['wins']); ?></dd>
		<dt><?php echo __d('tournament', 'Losses'); ?></dt> <dd><?php echo number_format($player['Player']['losses']); ?></dd>
		<dt><?php echo __d('tournament', 'Ties'); ?></dt> <dd><?php echo number_format($player['Player']['ties']); ?></dd>
	</dl>

	<?php
	if (!empty($player['Team'])) {
		echo $this->element('panels/player_teams');
	}

	if (!empty($player['Event'])) {
		echo $this->element('panels/player_events');
	} ?>
</div>