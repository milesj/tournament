<div class="page-title">
	<?php if ($user) { ?>
		<div class="buttons">
			<?php echo $this->Html->link(__d('tournament', 'Create Team'), array('action' => 'create'), array('class' => 'button')); ?>
		</div>
	<?php } ?>

	<h2><?php echo __d('tournament', 'Teams'); ?></h2>
</div>

<div class="container">
	<div class="container-body">
		<?php echo $this->element('pagination', array('class' => 'top')); ?>

		<div class="table">
			<table>
				<thead>
					<tr>
						<th> </th>
						<th><?php echo $this->Paginator->sort('Team.name', __d('tournament', 'Team')); ?></th>
						<th><?php echo $this->Paginator->sort('Leader.' . $config['User']['fieldMap']['username'], __d('tournament', 'Leader')); ?></th>
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
						<td class="col-icon"><?php echo $this->Bracket->participant($team, 'logo-link'); ?></td>
						<td>
							<b><?php echo $this->Html->link($team['Team']['name'], array('action' => 'profile', 'slug' => $team['Team']['slug'])); ?></b>
						</td>
						<td>
							<?php if (!empty($team['Leader']['id'])) {
								echo $this->Html->link($team['Leader'][$config['User']['fieldMap']['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $team['Leader']['id']), array('class' => 'alt'));
							} ?>
						</td>
						<td class="align-center"><?php echo $team['Team']['team_member_count']; ?></td>
						<td class="align-center"><?php echo $team['Team']['points']; ?></td>
						<td class="align-center"><?php echo $team['Team']['wins']; ?></td>
						<td class="align-center"><?php echo $team['Team']['losses']; ?></td>
						<td class="align-center"><?php echo $team['Team']['ties']; ?></td>
					</tr>

						<?php }
					} else { ?>

					<tr>
						<td colspan="4" class="no-results">
							<?php echo __d('tournament', 'There are no results to display'); ?>
						</td>
					</tr>

					<?php } ?>
				</tbody>
			</table>
		</div>

		<?php echo $this->element('pagination', array('class' => 'bottom')); ?>
	</div>
</div>