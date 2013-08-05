<div class="page-header">
	<div class="buttons">
		<?php echo $this->Html->link(__d('tournament', 'View Event'), array('action' => 'view', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug']), array('class' => 'btn btn-default')); ?>
		<?php echo $this->Html->link(__d('tournament', 'View Brackets'), array('action' => 'bracket', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug']), array('class' => 'btn btn-default')); ?>
	</div>

	<h2><?php echo $event['Event']['name']; ?> - <?php echo __d('tournament', 'Players'); ?></h2>
</div>

<div class="page">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th colspan="2"><?php echo __d('tournament', 'Player'); ?></th>
				<th><?php echo __d('tournament', 'Status'); ?></th>
				<th><?php echo __d('tournament', 'Ready'); ?></th>
				<th><?php echo __d('tournament', 'Points'); ?></th>
				<th><?php echo __d('tournament', 'Wins'); ?></th>
				<th><?php echo __d('tournament', 'Losses'); ?></th>
				<th><?php echo __d('tournament', 'Ties'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ($participants) {
				foreach ($participants as $player) { ?>

			<tr>
				<td>
					<?php if ($logo = $player['Player']['User'][$userFields['avatar']]) {
						echo $this->Html->image($logo, array('url' => array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id']), 'width' => 25, 'height' => 25));
					} ?>
				</td>
				<td>
					<?php echo $this->Html->link($player['Player']['User'][$userFields['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id'])); ?>
				</td>
				<td class="align-center"><?php echo $this->Utility->enum('EventParticipant', 'status', $player['EventParticipant']['status']); ?></td>
				<td class="align-center"><?php echo __d('tournament', $player['EventParticipant']['isReady'] ? 'Yes' : 'No'); ?></td>
				<td class="align-center"><?php echo number_format($player['EventParticipant']['points']); ?></td>
				<td class="align-center"><?php echo number_format($player['EventParticipant']['wins']); ?></td>
				<td class="align-center"><?php echo number_format($player['EventParticipant']['losses']); ?></td>
				<td class="align-center"><?php echo number_format($player['EventParticipant']['ties']); ?></td>
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
</div>