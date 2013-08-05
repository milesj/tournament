<div class="page-header">
	<h2><?php echo __d('tournament', 'Join Team'); ?></h2>
</div>

<div class="page">
	<div class="alert alert-info"><?php echo __d('tournament', 'Entering a correct password will automatically join the team roster, else you will need to be approved by the team leader.'); ?></div>

	<?php
	echo $this->Form->create('Team');
	echo $this->Form->input('password');
	echo $this->Form->submit(__d('tournament', 'Join'), array('class' => 'btn btn-success btn-large'));
	echo $this->Form->end(); ?>
</div>