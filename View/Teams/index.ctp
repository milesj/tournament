<div class="page-header">
	<?php if ($user) { ?>
		<div class="buttons">
			<?php echo $this->Html->link(__d('tournament', 'Create Team'), array('action' => 'create'), array('class' => 'btn btn-primary')); ?>
		</div>
	<?php } ?>

	<h2><?php echo __d('tournament', 'Teams'); ?></h2>
</div>

<div class="page">
	<?php echo $this->element('pagination', array('class' => 'top')); ?>

	<table class="table table-striped table-bordered table-hover">
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
			<?php if ($teams) {
				foreach ($teams as $team) { ?>

			<tr>
				<td class="col-icon"><?php //echo $this->Bracket->participant($team, 'logo-link'); ?></td>
				<td>
					<?php echo $this->Html->link($team['Team']['name'], array('action' => 'profile', 'slug' => $team['Team']['slug'])); ?>
				</td>
				<td>
					<?php if (!empty($team['Leader']['id'])) {
						echo $this->Html->link($team['Leader'][$userFields['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $team['Leader']['id']), array('class' => 'alt'));
					} ?>
				</td>
				<td class="align-center"><?php echo number_format($team['Team']['team_member_count']); ?></td>
				<td class="align-center"><?php echo number_format($team['Team']['points']); ?></td>
				<td class="align-center"><?php echo number_format($team['Team']['wins']); ?></td>
				<td class="align-center"><?php echo number_format($team['Team']['losses']); ?></td>
				<td class="align-center"><?php echo number_format($team['Team']['ties']); ?></td>
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

	<?php echo $this->element('pagination', array('class' => 'bottom')); ?>
</div>