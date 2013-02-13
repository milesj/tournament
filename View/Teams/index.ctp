
<div class="container">
	<div class="container-head">
		<h2><?php echo __d('tournament', 'Teams'); ?></h2>
	</div>

	<div class="container-body">
		<?php echo $this->element('pagination'); ?>

		<div class="table">
			<table>
				<thead>
					<tr>
						<th colspan="2"><?php echo $this->Paginator->sort('Team.name', __d('tournament', 'Team')); ?></th>
						<th><?php echo $this->Paginator->sort('Leader.' . $config['userMap']['username'], __d('tournament', 'Leader')); ?></th>
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
						<td>
							<?php if ($logo = $team['Team']['logo']) {
								echo $this->Html->image($logo, array('url' => array('action' => 'profile', 'slug' => $team['Team']['slug'])));
							} ?>
						</td>
						<td>
							<h3><?php echo $this->Html->link($team['Team']['name'], array('action' => 'profile', 'slug' => $team['Team']['slug'])); ?></h3>
						</td>
						<td>
							<?php if (!empty($team['Leader']['id'])) { ?>
								<h3><?php echo $this->Html->link($team['Leader'][$config['userMap']['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $team['Leader']['id'])); ?></h3>
							<?php } ?>
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

		<?php echo $this->element('pagination'); ?>
	</div>
</div>