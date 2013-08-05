<div class="title">
	<?php if ($user && $team['Team']['status'] == Team::ACTIVE) { ?>
		<div class="buttons">
			<?php if ($user['id'] == $team['Team']['user_id']) {
				echo $this->Html->link(__d('tournament', 'Manage Team'), array('plugin' => 'tournament', 'action' => 'edit', 'slug' => $team['Team']['slug']), array('class' => 'btn btn-info'));
			}

			if ($member) {
				echo $this->Html->link(__d('tournament', 'Leave Team'), array('plugin' => 'tournament', 'action' => 'leave', 'slug' => $team['Team']['slug']), array('class' => 'btn btn-danger'));
			} else {
				echo $this->Html->link(__d('tournament', 'Join Team'), array('plugin' => 'tournament', 'action' => 'join', 'slug' => $team['Team']['slug']), array('class' => 'btn btn-default'));
			} ?>
		</div>
	<?php } ?>

	<h2><?php echo $team['Team']['name']; ?></h2>
</div>

<div class="container">
	<?php if ($desc = $team['Team']['description']) { ?>
		<p><?php echo $desc; ?></p>
	<?php } ?>

	<dl class="dl-horizontal">
		<dt><?php echo __d('tournament', 'Members'); ?></dt> <dd><?php echo number_format($team['Team']['team_member_count']); ?></dd>
		<dt><?php echo __d('tournament', 'Points'); ?></dt> <dd><?php echo number_format($team['Team']['points']); ?></dd>
		<dt><?php echo __d('tournament', 'Wins'); ?></dt> <dd><?php echo number_format($team['Team']['wins']); ?></dd>
		<dt><?php echo __d('tournament', 'Losses'); ?></dt> <dd><?php echo number_format($team['Team']['losses']); ?></dd>
		<dt><?php echo __d('tournament', 'Ties'); ?></dt> <dd><?php echo number_format($team['Team']['ties']); ?></dd>
	</dl>

	<?php
	if (!empty($team['TeamMember'])) {
		echo $this->element('panels/team_roster');
	}

	if (!empty($team['Event'])) {
		echo $this->element('panels/team_events');
	} ?>
</div>