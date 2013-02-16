
<div class="page-title">
	<h2><?php echo __d('tournament', 'Teams'); ?></h2>
</div>

<div class="container">
	<div class="container-body">
		<div class="table no-paging">
			<table>
				<thead>
					<tr>
						<th colspan="2"><?php echo __d('tournament', 'Team'); ?></th>
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
						foreach ($participants as $team) { ?>

					<tr>
						<td>
							<?php if ($logo = $team['Team']['logo']) {
								echo $this->Html->image($logo, array('url' => array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['Team']['slug'])));
							} ?>
						</td>
						<td>
							<b><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['Team']['slug'])); ?></b>
						</td>
						<td class="align-center"><?php echo $this->Tournament->options('EventParticipant.status', $team['EventParticipant']['status']); ?></td>
						<td class="align-center"><?php echo $this->Tournament->options('EventParticipant.isReady', $team['EventParticipant']['isReady']); ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['points']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['wins']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['losses']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['ties']; ?></td>
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