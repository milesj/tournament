
<div class="page-title">
	<h2><?php echo __d('tournament', 'Players'); ?></h2>
</div>

<div class="container">
	<div class="container-body">
		<div class="table no-paging">
			<table>
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
							<?php if ($logo = $player['Player']['User'][$config['userMap']['avatar']]) {
								echo $this->Html->image($logo, array('url' => array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id']), 'width' => 25, 'height' => 25));
							} ?>
						</td>
						<td>
							<b><?php echo $this->Html->link($player['Player']['User'][$config['userMap']['username']], array('controller' => 'players', 'action' => 'profile', 'id' => $player['Player']['User']['id'])); ?></b>
						</td>
						<td class="align-center"><?php echo $this->Tournament->options('EventParticipant.status', $player['EventParticipant']['status']); ?></td>
						<td class="align-center"><?php echo $this->Tournament->options('EventParticipant.isReady', $player['EventParticipant']['isReady']); ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['points']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['wins']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['losses']; ?></td>
						<td class="align-center"><?php echo $player['EventParticipant']['ties']; ?></td>
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
		</div>
	</div>
</div>