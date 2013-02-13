
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
						<th colspan="2"><?php echo __d('tournament', 'Player'); ?></th>
						<th><?php echo __d('tournament', 'Status'); ?></th>
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
							<?php if ($logo = $player['Player']['User'][$config['userMap']['avatar']]) {
								echo $this->Html->image($logo, array('url' => array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id'])));
							} ?>
						</td>
						<td>
							<h3><?php echo $this->Html->link($player['Player']['User'][$config['userMap']['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id'])); ?></h3>
						</td>
						<td><?php echo $player['EventParticipant']['status_enum']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['points']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['wins']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['losses']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['ties']; ?></td>
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