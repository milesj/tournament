<table class="table table--hover table--sortable">
	<thead>
		<tr>
			<th> </th>
			<th><?php echo $this->Paginator->sort('User.' . $userFields['username'], __d('tournament', 'Player')); ?></th>
			<th><?php echo $this->Paginator->sort('CurrentTeam.team_id', __d('tournament', 'Team')); ?></th>
			<th><?php echo $this->Paginator->sort('Player.points', __d('tournament', 'Points')); ?></th>
			<th><?php echo $this->Paginator->sort('Player.wins', __d('tournament', 'Wins')); ?></th>
			<th><?php echo $this->Paginator->sort('Player.losses', __d('tournament', 'Losses')); ?></th>
			<th><?php echo $this->Paginator->sort('Player.ties', __d('tournament', 'Ties')); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($players)) {
			foreach ($players as $player) { ?>

		<tr>
			<td class="col-player-image"><?php //echo $this->Bracket->participant($player, 'logo-link'); ?></td>
			<td class="col-player-name">
				<?php echo $this->Html->link($player['User'][$userFields['username']], array('action' => 'profile', 'id' => $player['User']['id'])); ?>
			</td>
			<td class="col-team-name">
				<?php if (!empty($player['CurrentTeam']['Team'])) {
					echo $this->Html->link($player['CurrentTeam']['Team']['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $player['CurrentTeam']['Team']['slug']), array('class' => 'alt'));
				} ?>
			</td>
			<td class="col-player-points"><?php echo number_format($player['Player']['points']); ?></td>
			<td class="col-player-wins"><?php echo number_format($player['Player']['wins']); ?></td>
			<td class="col-player-losses"><?php echo number_format($player['Player']['losses']); ?></td>
			<td class="col-player-ties"><?php echo number_format($player['Player']['ties']); ?></td>
		</tr>

			<?php }
		} else { ?>

		<tr>
			<td colspan="7" class="no-results">
				<?php echo __d('tournament', 'There are no results to display'); ?>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>