
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
						<th colspan="2"><?php echo __d('tournament', 'Team'); ?></th>
						<th><?php echo __d('tournament', 'Status'); ?></th>
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
							<h3><?php echo $this->Html->link($team['Team']['name'], array('controller' => 'teams', 'action' => 'profile', 'slug' => $team['Team']['slug'])); ?></h3>
						</td>
						<td><?php echo $team['EventParticipant']['status_enum']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['points']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['wins']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['losses']; ?></td>
						<td class="align-center"><?php echo $team['EventParticipant']['ties']; ?></td>
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