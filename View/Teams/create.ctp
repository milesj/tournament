<?php
echo $this->Form->create('Team');
echo $this->Form->input('name');
echo $this->Form->input('password');
echo $this->Form->input('description');
echo $this->Form->end('Create');