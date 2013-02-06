<h2>Join Team</h2>

<p>Entering a correct password will automatically join the team roster, else you will need to be approved by the team leader.</p>

<?php
echo $this->Form->create('Team');
echo $this->Form->input('password');
echo $this->Form->end('Join');