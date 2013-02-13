
<div class="container">
	<div class="container-head">
		<h2><?php echo __d('tournament', 'Events'); ?></h2>
	</div>

	<div class="container-body">
		<?php echo $this->element('pagination'); ?>

		<div class="table">
			<table>
				<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('Event.name', __d('tournament', 'Event')); ?></th>
						<th><?php echo $this->Paginator->sort('Game.name', __d('tournament', 'Game')); ?></th>
						<th><?php echo $this->Paginator->sort('League.name', __d('tournament', 'League')); ?></th>
						<th><?php echo $this->Paginator->sort('Division.name', __d('tournament', 'Division')); ?></th>
						<th><?php echo $this->Paginator->sort('Event.type', __d('tournament', 'Type')); ?></th>
						<th><?php echo $this->Paginator->sort('Event.for', __d('tournament', 'Setup')); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($events) {
						foreach ($events as $event) { ?>

					<tr>
						<td>
							<h3><?php echo $this->Html->link($event['Event']['name'], array('action' => 'view', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug'])); ?></h3>
						</td>
						<td><?php echo $event['Game']['name']; ?></td>
						<td>
							<?php echo $this->Html->link($event['League']['name'], array('controller' => 'leagues', 'action' => 'view', 'league' => $event['League']['slug'])); ?>
						</td>
						<td><?php echo $event['Division']['name']; ?></td>
						<td><?php echo $event['Event']['type_enum']; ?></td>
						<td><?php echo $event['Event']['for_enum']; ?></td>
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