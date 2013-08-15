<div class="title">
	<h2><?php echo __d('tournament', 'Join Team'); ?></h2>
</div>

<div class="container">
	<div class="alert info"><?php echo __d('tournament', 'Entering a correct password will automatically join the team roster, else you will need to be approved by the team leader.'); ?></div>

	<?php
	echo $this->Form->create('Team');
	echo $this->Form->input('password');
	echo $this->Form->submit(__d('tournament', 'Join'), array('class' => 'button success large', 'div' => 'form-actions'));
	echo $this->Form->end(); ?>
</div>