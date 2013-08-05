<?php
if (!$settings['showRemovedTeamMembers'] && $member['status'] == TeamMember::REMOVED) {
	return;
} else if (!$settings['showQuitTeamMembers'] && $member['status'] == TeamMember::QUIT) {
	return;
} ?>

<tr>
	<td class="col-member-image"><?php //echo $this->Bracket->participant($member, 'logo-link'); ?></td>
	<td class="col-member-name">
		<?php echo $this->Html->link($member['User'][$userFields['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $member['User']['id'])); ?>
	</td>
	<td class="col-member-status"><?php echo $this->Utility->enum('TeamMember', 'status', $member['status']); ?></td>
	<td class="col-member-role"><?php echo $this->Utility->enum('TeamMember', 'role', $member['role']); ?></td>
	<td class="col-member-created">
		<?php if ($member['status'] == TeamMember::QUIT || $member['status'] == TeamMember::REMOVED) {
			echo $this->Time->nice($member['modified'], $this->Tournament->timezone());
		} else {
			echo $this->Time->nice($member['created'], $this->Tournament->timezone());
		} ?>
	</td>
</tr>