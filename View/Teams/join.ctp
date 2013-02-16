<div class="page-title">
	<h2><?php echo __d('tournament', 'Join Team'); ?></h2>
</div>

<div class="page-body">
	<p><?php echo __d('tournament', 'Entering a correct password will automatically join the team roster, else you will need to be approved by the team leader.'); ?></p>

	<?php
	echo $this->Form->create('Team');
	echo $this->Form->input('password');
	echo $this->Form->submit(__d('tournament', 'Join'), array('class' => 'button large'));
	echo $this->Form->end(); ?>
</div>