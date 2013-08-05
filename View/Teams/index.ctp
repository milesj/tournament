<div class="title">
	<?php if ($user) { ?>
		<div class="buttons">
			<?php if (!empty($myTeam)) {
				echo $this->Html->link(__d('tournament', 'Manage Team'), array('plugin' => 'tournament', 'action' => 'edit', 'slug' => $myTeam['Team']['slug']), array('class' => 'btn btn-info'));
			} else {
				echo $this->Html->link(__d('tournament', 'Create Team'), array('action' => 'create'), array('class' => 'btn btn-primary'));
			} ?>
		</div>
	<?php } ?>

	<h2><?php echo __d('tournament', 'Teams'); ?></h2>
</div>

<div class="container">
	<?php
	echo $this->element('pagination', array('class' => 'top'));
	echo $this->element('tables/team_list');
	echo $this->element('pagination', array('class' => 'bottom')); ?>
</div>