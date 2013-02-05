<?php
echo $this->Form->create('Team', array('type' => 'file'));
echo $this->Form->input('name');
echo $this->Form->input('password');
echo $this->Form->input('description');
echo $this->Form->input('logo', array('type' => 'file'));
echo $this->Form->end('Create');