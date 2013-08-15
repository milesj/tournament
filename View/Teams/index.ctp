<div class="title">
	<?php if ($user) { ?>
		<div class="action-buttons">
			<?php if (!empty($myTeam)) {
				echo $this->Html->link(__d('tournament', 'Manage Team'), array('plugin' => 'tournament', 'action' => 'edit', 'slug' => $myTeam['Team']['slug']), array('class' => 'button info'));
			} else {
				echo $this->Html->link(__d('tournament', 'Create Team'), array('action' => 'create'), array('class' => 'button info'));
			} ?>
		</div>
	<?php } ?>

	<h2><?php echo __d('tournament', 'Teams'); ?></h2>
</div>

<div class="container">
	<?php
	echo $this->element('Admin.pagination', array('class' => 'top'));
	echo $this->element('tables/team_list');
	echo $this->element('Admin.pagination', array('class' => 'bottom')); ?>
</div>