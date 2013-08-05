<div class="page-header">
	<h2><?php echo __d('tournament', 'Manage Team'); ?></h2>
</div>

<div class="page">
	<div class="tabs" id="team-tabs">
		<nav>
			<ul class="nav nav-pills">
				<li><a href="#team-info"><?php echo __d('tournament', 'Edit Information'); ?></a></li>
				<li><a href="#team-logo"><?php echo __d('tournament', 'Change Logo'); ?></a></li>
				<li><a href="#team-owner"><?php echo __d('tournament', 'Change Ownership'); ?></a></li>
				<li><a href="#team-disband"><?php echo __d('tournament', 'Disband'); ?></a></li>
			</ul>
		</nav>

		<section id="team-info" style="display: none">
			<?php
			echo $this->Form->create('Team');
			echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'settings'));
			echo $this->Form->input('name');
			echo $this->Form->input('password', array('type' => 'text'));
			echo $this->Form->input('description');
			echo $this->Form->submit(__d('tournament', 'Update'), array('class' => 'btn btn-success btn-large'));
			echo $this->Form->end(); ?>
		</section>

		<section id="team-logo" style="display: none">
			<div class="alert alert-info"><?php echo __d('tournament', 'Uploaded logos will be resized to %s.', implode('x', $config['Tournament']['uploads']['teamLogo'])); ?></div>

			<?php
			echo $this->Form->create('Team', array('type' => 'file'));
			echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'logo'));
			echo $this->Form->input('logo', array('type' => 'file'));
			echo $this->Form->submit(__d('tournament', 'Upload'), array('class' => 'btn btn-success btn-large'));
			echo $this->Form->end(); ?>
		</section>

		<section id="team-owner" style="display: none">
			<div class="alert alert-info"><?php echo __d('tournament', 'Changing ownership will demote you to a regular member and promote the new owner as leader.'); ?></div>

			<?php
			echo $this->Form->create('Team');
			echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'owner'));
			echo $this->Form->input('user_id', array('label' => __d('tournament', 'Promote')));
			echo $this->Form->input('leave', array('type' => 'checkbox', 'label' => __d('tournament', 'Leave Team?')));
			echo $this->Form->submit(__d('tournament', 'Change'), array('class' => 'btn btn-success btn-large'));
			echo $this->Form->end(); ?>
		</section>

		<section id="team-disband" style="display: none">
			<div class="alert alert-info"><?php echo __d('tournament', 'Disbanding will remove the team and all its members.'); ?></div>

			<?php
			echo $this->Form->create('Team');
			echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'disband'));
			echo $this->Form->input('disband', array('type' => 'checkbox', 'label' => __d('tournament', 'Are you sure?')));
			echo $this->Form->submit(__d('tournament', 'Disband'), array('class' => 'btn btn-success btn-large'));
			echo $this->Form->end(); ?>
		</section>
	</div>

	<script type="text/javascript">
		window.addEvent('domready', function() {
			var tabs = Titon.Tabs.factory('team-tabs', {
				persistState: true
			});
		});
	</script>
</div>