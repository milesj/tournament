<tr>
	<td class="col-icon"><?php echo $this->Bracket->participant($team, 'logo-link'); ?></td>
	<td>
		<b><?php echo $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['slug'])); ?></b>
	</td>
	<td class="align-center"><?php echo $this->Tournament->options('TeamMember.status', $team['TeamMember']['status']); ?></td>
	<td class="align-center"><?php echo $this->Tournament->options('TeamMember.role', $team['TeamMember']['role']); ?></td>
	<td class="align-right">
		<?php if ($team['TeamMember']['status'] == TeamMember::QUIT || $team['TeamMember']['status'] == TeamMember::REMOVED) {
			echo $this->Time->nice($team['TeamMember']['modified'], $this->Tournament->timezone());
		} else {
			echo $this->Time->nice($team['TeamMember']['created'], $this->Tournament->timezone());
		} ?>
	</td>
</tr>