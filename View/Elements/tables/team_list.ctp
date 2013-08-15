<table class="table table--hover table--sortable">
	<thead>
		<tr>
			<th> </th>
			<th><?php echo $this->Paginator->sort('Team.name', __d('tournament', 'Team')); ?></th>
			<th><?php echo $this->Paginator->sort('Leader.' . $userFields['username'], __d('tournament', 'Leader')); ?></th>
			<th><?php echo $this->Paginator->sort('Team.team_member_count', __d('tournament', 'Members')); ?></th>
			<th><?php echo $this->Paginator->sort('Team.points', __d('tournament', 'Points')); ?></th>
			<th><?php echo $this->Paginator->sort('Team.wins', __d('tournament', 'Wins')); ?></th>
			<th><?php echo $this->Paginator->sort('Team.losses', __d('tournament', 'Losses')); ?></th>
			<th><?php echo $this->Paginator->sort('Team.ties', __d('tournament', 'Ties')); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($teams)) {
			foreach ($teams as $team) { ?>

		<tr>
			<td class="col-team-image"><?php //echo $this->Bracket->participant($team, 'logo-link'); ?></td>
			<td class="col-team-name">
				<?php echo $this->Html->link($team['Team']['name'], array('action' => 'profile', 'slug' => $team['Team']['slug'])); ?>
			</td>
			<td class="col-player-name">
				<?php if (!empty($team['Leader']['id'])) {
					echo $this->Html->link($team['Leader'][$userFields['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $team['Leader']['id']), array('class' => 'alt'));
				} ?>
			</td>
			<td class="col-team-memberCount"><?php echo number_format($team['Team']['team_member_count']); ?></td>
			<td class="col-team-points"><?php echo number_format($team['Team']['points']); ?></td>
			<td class="col-team-wins"><?php echo number_format($team['Team']['wins']); ?></td>
			<td class="col-team-losses"><?php echo number_format($team['Team']['losses']); ?></td>
			<td class="col-team-ties"><?php echo number_format($team['Team']['ties']); ?></td>
		</tr>

			<?php }
		} else { ?>

		<tr>
			<td colspan="8" class="no-results">
				<?php echo __d('tournament', 'There are no results to display'); ?>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>