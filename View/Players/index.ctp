<div class="page-header">
	<h2><?php echo __d('tournament', 'Players'); ?></h2>
</div>

<div class="page">
	<?php echo $this->element('pagination', array('class' => 'top')); ?>

	<table class="table table-striped table-bordered table-hover">
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
			<?php if ($players) {
				foreach ($players as $player) { ?>

			<tr>
				<td class="col-icon"><?php //echo $this->Bracket->participant($player, 'logo-link'); ?></td>
				<td>
					<?php echo $this->Html->link($player['User'][$userFields['username']], array('action' => 'profile', 'id' => $player['User']['id'])); ?>
				</td>
				<td>
					<?php if (!empty($player['CurrentTeam']['Team'])) {
						echo $this->Html->link($player['CurrentTeam']['Team']['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $player['CurrentTeam']['Team']['slug']), array('class' => 'alt'));
					} ?>
				</td>
				<td class="align-center"><?php echo number_format($player['Player']['points']); ?></td>
				<td class="align-center"><?php echo number_format($player['Player']['wins']); ?></td>
				<td class="align-center"><?php echo number_format($player['Player']['losses']); ?></td>
				<td class="align-center"><?php echo number_format($player['Player']['ties']); ?></td>
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

	<?php echo $this->element('pagination', array('class' => 'bottom')); ?>
</div>