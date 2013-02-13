
<div class="container">
	<div class="container-head">
		<h2><?php echo __d('tournament', 'Players'); ?></h2>
	</div>

	<div class="container-body">
		<?php echo $this->element('pagination'); ?>

		<div class="table">
			<table>
				<thead>
					<tr>
						<th colspan="2"><?php echo $this->Paginator->sort('User.' . $config['userMap']['username'], __d('tournament', 'Player')); ?></th>
						<th><?php echo $this->Paginator->sort('CurrentTeam.team_id', __d('tournament', 'Team')); ?></th>
						<th><?php echo $this->Paginator->sort('Player.points', __d('tournament', 'Points')); ?></th>
						<th><?php echo $this->Paginator->sort('Player.wins', __d('tournament', 'Wins')); ?></th>
						<th><?php echo $this->Paginator->sort('Player.losses', __d('tournament', 'Losses')); ?></th>
						<th><?php echo $this->Paginator->sort('Player.ties', __d('tournament', 'Ties')); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($players) {
						foreach ($players as $player) { ?>

					<tr>
						<td>
							<?php if ($logo = $player['User'][$config['userMap']['avatar']]) {
								echo $this->Html->image($logo, array('url' => array('action' => 'profile', 'id' => $player['User']['id'])));
							} ?>
						</td>
						<td>
							<h3><?php echo $this->Html->link($player['User'][$config['userMap']['username']], array('action' => 'profile', 'id' => $player['User']['id'])); ?></h3>
						</td>
						<td>
							<?php if (!empty($player['CurrentTeam']['Team'])) { ?>
								<h3><?php echo $this->Html->link($player['CurrentTeam']['Team']['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $player['CurrentTeam']['Team']['slug'])); ?></h3>
							<?php } ?>
						</td>
						<td class="align-center"><?php echo $player['Player']['points']; ?></td>
						<td class="align-center"><?php echo $player['Player']['wins']; ?></td>
						<td class="align-center"><?php echo $player['Player']['losses']; ?></td>
						<td class="align-center"><?php echo $player['Player']['ties']; ?></td>
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