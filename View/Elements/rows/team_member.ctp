<?php
$settings = Configure::read('Tournament.settings');

if (!$settings['showRemovedTeamMembers'] && $member['status'] == TeamMember::REMOVED) {
	return;
} else if (!$settings['showQuitTeamMembers'] && $member['status'] == TeamMember::QUIT) {
	return;
} ?>

<tr>
	<td class="col-avatar">
		<?php if ($avatar = $member['User'][$config['userMap']['avatar']]) {
			echo $this->Html->image($avatar, array('url' => array('controller' => 'players', 'action' => 'profile', 'id' => $member['User']['id'])));
		} ?>
	</td>
	<td>
		<b><?php echo $this->Html->link($member['User'][$config['userMap']['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $member['User']['id'])); ?></b>
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