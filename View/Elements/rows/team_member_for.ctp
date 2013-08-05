<tr>
	<td class="col-member-image"><?php //echo $this->Bracket->participant($team, 'logo-link'); ?></td>
	<td class="col-member-name">
		<?php echo $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['slug'])); ?>
	</td>
	<td class="col-member-status"><?php echo $this->Utility->enum('TeamMember', 'status', $team['TeamMember']['status']); ?></td>
	<td class="col-member-role"><?php echo $this->Utility->enum('TeamMember', 'role', $team['TeamMember']['role']); ?></td>
	<td class="col-member-created">
		<?php if ($team['TeamMember']['status'] == TeamMember::QUIT || $team['TeamMember']['status'] == TeamMember::REMOVED) {
			echo $this->Time->nice($team['TeamMember']['modified'], $this->Tournament->timezone());
		} else {
			echo $this->Time->nice($team['TeamMember']['created'], $this->Tournament->timezone());
		} ?>
	</td>
</tr>