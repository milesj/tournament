<h2>Information</h2>

<?php
echo $this->Form->create('Team');
echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'settings'));
echo $this->Form->input('name');
echo $this->Form->input('password', array('type' => 'text'));
echo $this->Form->input('description');
echo $this->Form->end('Update'); ?>

<h2>Logo</h2>
<p>Uploads will be resized to <?php echo implode('x', $config['uploads']['teamLogo']); ?>.</p>

<?php
echo $this->Form->create('Team', array('type' => 'file'));
echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'logo'));
echo $this->Form->input('logo', array('type' => 'file'));
echo $this->Form->end('Upload'); ?>

<h2>Owner</h2>
<p>Changing ownership will demote you to a regular member and promote the new owner as leader.</p>

<?php
echo $this->Form->create('Team');
echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'owner'));
echo $this->Form->input('user_id', array('label' => __d('tournament', 'Promote')));
echo $this->Form->input('leave', array('type' => 'checkbox', 'label' => __d('tournament', 'Leave Team?')));
echo $this->Form->end('Change'); ?>

<h2>Disband</h2>
<p>Disbanding will remove the team and all its members.</p>

<?php
echo $this->Form->create('Team');
echo $this->Form->input('action', array('type' => 'hidden', 'value' => 'disband'));
echo $this->Form->input('disband', array('type' => 'checkbox', 'label' => __d('tournament', 'Are you sure?')));
echo $this->Form->end('Disband'); ?>