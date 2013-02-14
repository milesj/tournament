<?php
$divisions = array();

if (!empty($league['Division'])) {
	foreach ($league['Division'] as $division) {
		$divisions[] = $division['name'];
	}
} ?>

<div class="page-title">
	<h2><?php echo $league['League']['name']; ?></h2>
	<b><?php echo __d('tournament', 'Game'); ?>:</b> <?php echo $league['Game']['name']; ?><br>
	<b><?php echo __d('tournament', 'Region'); ?>:</b> <?php echo $league['Region']['name']; ?><br>
	<?php if ($divisions) { ?>
		<b><?php echo __d('tournament', 'Divisions'); ?>:</b> <?php echo implode(', ', $divisions); ?>
	<?php } ?>
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
						<th><?php echo __d('tournament', 'Registration'); ?></th>
						<th><?php echo __d('tournament', 'Duration'); ?></th>
						<th><?php echo __d('tournament', 'Signed Up'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($events) {
						foreach ($events as $event) { ?>

					<tr>
						<td>
							<b><?php echo $this->Html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', 'league' => $league['League']['slug'], 'event' => $event['Event']['slug'])); ?></b>
						</td>
						<td class="align-center"><?php echo $event['Division']['name']; ?></td>
						<td class="align-center"><?php echo $this->Tournament->eventType($event['Event']['type_enum']); ?></td>
						<td class="align-center"><?php echo $this->Tournament->setupFor($event['Event']['for']); ?></td>
						<td class="align-center"><?php echo $this->Tournament->eventRegistration($event); ?></td>
						<td class="align-center">
							<?php echo $this->Time->niceShort($event['Event']['start'], $this->Tournament->timezone()); ?> -
							<?php echo $this->Time->niceShort($event['Event']['end'], $this->Tournament->timezone()); ?>
						</td>
						<td class="align-center"><?php echo $event['Event']['event_participant_count']; ?></td>
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