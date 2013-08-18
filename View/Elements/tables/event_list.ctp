<table class="table table--hover table--sortable">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('Event.name', __d('tournament', 'Event')); ?></th>
			<th><?php echo $this->Paginator->sort('Game.name', __d('tournament', 'Game')); ?></th>
			<th><?php echo $this->Paginator->sort('League.name', __d('tournament', 'League')); ?></th>
			<th><?php echo $this->Paginator->sort('Division.name', __d('tournament', 'Division')); ?></th>
			<th><?php echo $this->Paginator->sort('Event.type', __d('tournament', 'Type')); ?></th>
			<th><?php echo $this->Paginator->sort('Event.for', __d('tournament', 'Setup')); ?></th>
			<th><?php echo $this->Paginator->sort('Event.event_participant_count', __d('tournament', 'Entered')); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($events)) {
			foreach ($events as $event) { ?>

		<tr>
			<td class="col-event-name">
				<?php echo $this->Html->link($event['Event']['name'], array('action' => 'bracket', 'league' => $event['League']['slug'], 'event' => $event['Event']['slug'])); ?>
			</td>
			<td class="col-game-name"><?php echo $event['Game']['name']; ?></td>
			<td class="col-league-name">
				<?php echo $this->Html->link($event['League']['name'], array('controller' => 'leagues', 'action' => 'view', 'league' => $event['League']['slug']), array('class' => 'alt')); ?>
			</td>
			<td class="col-division-name"><?php echo $event['Division']['name']; ?></td>
			<td class="col-event-type"><?php echo $this->Utility->enum('Event', 'type', $event['Event']['type']); ?></td>
			<td class="col-event-for"><?php echo $this->Utility->enum('Event', 'for', $event['Event']['for']); ?></td>
			<td class="col-event-participantCount"><?php echo __d('tournament', '%s of %s', $event['Event']['event_participant_count'], $event['Event']['maxParticipants']); ?></td>
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