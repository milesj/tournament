
<div class="page-title">
	<h2><?php echo $league['League']['name']; ?></h2>
	<b><?php echo __d('tournament', 'Game'); ?>:</b> <?php echo $league['Game']['name']; ?><br>
	<b><?php echo __d('tournament', 'Region'); ?>:</b> <?php echo $league['Region']['name']; ?>
</div>

<div class="container">
	<div class="container-head">
		<h3><?php echo __d('tournament', 'Events'); ?></h3>
	</div>

	<div class="container-body">
		<div class="table no-paging">
			<table>
				<thead>
					<tr>
						<th><?php echo __d('tournament', 'Event'); ?></th>
						<th><?php echo __d('tournament', 'Division'); ?></th>
						<th><?php echo __d('tournament', 'Type'); ?></th>
						<th><?php echo __d('tournament', 'Setup'); ?></th>
						<th><?php echo __d('tournament', 'Seed'); ?></th>
						<th><?php echo __d('tournament', 'Registration'); ?></th>
						<th><?php echo __d('tournament', 'Timeframe'); ?></th>
						<th><?php echo __d('tournament', 'Signed Up'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($events) {
						foreach ($events as $event) {
							echo $this->element('rows/event', array(
								'event' => $event
							));
						}
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
	</div>
</div>