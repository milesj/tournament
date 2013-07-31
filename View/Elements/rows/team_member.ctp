<?php
$settings = Configure::read('Tournament.settings');

if (!$settings['showRemovedTeamMembers'] && $member['status'] == TeamMember::REMOVED) {
	return;
} else if (!$settings['showQuitTeamMembers'] && $member['status'] == TeamMember::QUIT) {
	return;
} ?>

<tr>
	<td class="col-icon"><?php echo $this->Bracket->participant($member, 'logo-link'); ?></td>
	<td>
		<b><?php echo $this->Html->link($member['User'][$config['User']['fieldMap']['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $member['User']['id'])); ?></b>
	</td>
	<td class="align-center"><?php echo $this->Tournament->options('TeamMember.status', $member['status']); ?></td>
	<td class="align-center"><?php echo $this->Tournament->options('TeamMember.role', $member['role']); ?></td>
	<td class="align-right">
		<?php if ($member['status'] == TeamMember::QUIT || $member['status'] == TeamMember::REMOVED) {
			echo $this->Time->nice($member['modified'], $this->Tournament->timezone());
		} else {
			echo $this->Time->nice($member['created'], $this->Tournament->timezone());
		} ?>
	</td>
</tr>