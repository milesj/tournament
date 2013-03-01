
<div class="page-title">
	<h2><?php echo __d('tournament', 'Events'); ?></h2>
</div>

<div class="container">
	<div class="container-body">
		<?php echo $this->element('pagination', array('class' => 'top')); ?>

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
							<b><?php echo $this->Html->link($event['Event']['name'], array('action' => 'bracket', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug'])); ?></b>
						</td>
						<td><?php echo $event['Game']['name']; ?></td>
						<td>
							<?php echo $this->Html->link($event['League']['name'], array('controller' => 'leagues', 'action' => 'view', 'league' => $event['League']['slug']), array('class' => 'alt')); ?>
						</td>
						<td class="align-center"><?php echo $event['Division']['name']; ?></td>
						<td class="align-center"><?php echo $this->Tournament->options('Event.type', $event['Event']['type']); ?></td>
						<td class="align-center"><?php echo $this->Tournament->options('Event.for', $event['Event']['for']); ?></td>
					</tr>

						<?php }
					} else { ?>

					<tr>
						<td colspan="6" class="no-results">
							<?php echo __d('tournament', 'There are no results to display'); ?>
						</td>
					</tr>

					<?php } ?>
				</tbody>
			</table>
		</div>

		<?php echo $this->element('pagination', array('class' => 'bottom')); ?>
	</div>
</div>