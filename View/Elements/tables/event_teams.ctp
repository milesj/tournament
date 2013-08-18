<table class="table table--hover table--sortable">
	<thead>
		<tr>
			<th colspan="2"><?php echo $this->Paginator->sort('EventParticipant.team_id', __d('tournament', 'Team')); ?></th>
			<th><?php echo $this->Paginator->sort('EventParticipant.status', __d('tournament', 'Status')); ?></th>
			<th><?php echo $this->Paginator->sort('EventParticipant.isReady', __d('tournament', 'Ready')); ?></th>
			<th><?php echo $this->Paginator->sort('EventParticipant.points', __d('tournament', 'Points')); ?></th>
			<th><?php echo $this->Paginator->sort('EventParticipant.wins', __d('tournament', 'Wins')); ?></th>
			<th><?php echo $this->Paginator->sort('EventParticipant.losses', __d('tournament', 'Losses')); ?></th>
			<th><?php echo $this->Paginator->sort('EventParticipant.ties', __d('tournament', 'Ties')); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($participants)) {
			foreach ($participants as $team) { ?>

		<tr>
			<td class="col-team-image">
				<?php if ($logo = $team['Team']['logo']) {
					echo $this->Html->image($logo, array('url' => array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['Team']['slug'])));
				} ?>
			</td>
			<td class="col-team-name">
				<?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['Team']['slug'])); ?>
			</td>
			<td class="col-participant-status"><?php echo $this->Utility->enum('EventParticipant', 'status', $team['EventParticipant']['status']); ?></td>
			<td class="col-participant-isReady"><?php echo __d('tournament', $team['EventParticipant']['isReady'] ? 'Yes' : 'No'); ?></td>
			<td class="col-participant-points"><?php echo number_format($team['EventParticipant']['points']); ?></td>
			<td class="col-participant-wins"><?php echo number_format($team['EventParticipant']['wins']); ?></td>
			<td class="col-participant-losses"><?php echo number_format($team['EventParticipant']['losses']); ?></td>
			<td class="col-participant-ties"><?php echo number_format($team['EventParticipant']['ties']); ?></td>
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