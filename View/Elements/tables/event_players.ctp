<table class="table table--hover table--sortable">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('EventParticipant.standing', __d('tournament', 'Standing')); ?></th>
			<th colspan="2"><?php echo $this->Paginator->sort('EventParticipant.player_id', __d('tournament', 'Player')); ?></th>
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
			foreach ($participants as $player) { ?>

		<tr>
			<td class="col-participant-standing"><?php echo $this->Bracket->standing($player['EventParticipant']['standing']); ?></td>
			<td class="col-player-image">
				<?php if ($logo = $player['Player']['User'][$userFields['avatar']]) {
					echo $this->Html->image($logo, array('url' => array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id']), 'width' => 25, 'height' => 25));
				} ?>
			</td>
			<td class="col-player-name">
				<?php echo $this->Html->link($player['Player']['User'][$userFields['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id'])); ?>
			</td>
			<td class="col-participant-status"><?php echo $this->Utility->enum('EventParticipant', 'status', $player['EventParticipant']['status']); ?></td>
			<td class="col-participant-isReady"><?php echo __d('tournament', $player['EventParticipant']['isReady'] ? 'Yes' : 'No'); ?></td>
			<td class="col-participant-points"><?php echo number_format($player['EventParticipant']['points']); ?></td>
			<td class="col-participant-wins"><?php echo number_format($player['EventParticipant']['wins']); ?></td>
			<td class="col-participant-losses"><?php echo number_format($player['EventParticipant']['losses']); ?></td>
			<td class="col-participant-ties"><?php echo number_format($player['EventParticipant']['ties']); ?></td>
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