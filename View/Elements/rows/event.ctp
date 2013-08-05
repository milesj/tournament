<tr>
	<td class="col-event-name">
		<?php echo $this->Html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', 'league' => $league['League']['slug'], 'event' => $event['Event']['slug'])); ?>
	</td>
	<td class="col-division-name"><?php echo $event['Division']['name']; ?></td>
	<td class="col-event-type"><?php echo $this->Utility->enum('Event', 'type', $event['Event']['type']); ?></td>
	<td class="col-event-for"><?php echo $this->Utility->enum('Event', 'for', $event['Event']['for']); ?></td>
	<td class="col-event-seed"><?php echo $this->Utility->enum('Event', 'seed', $event['Event']['seed']); ?></td>
	<td class="col-event-startDate"><?php echo $this->Tournament->eventSignupDates($event); ?></td>
	<td class="col-event-endDate"><?php echo $this->Tournament->eventPlayDates($event); ?></td>
	<td class="col-event-participantCount"><?php echo number_format($event['Event']['event_participant_count']); ?></td>
</tr>