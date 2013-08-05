<tr>
	<td>
		<?php echo $this->Html->link($event['Event']['name'], array('controller' => 'events', 'action' => 'view', 'league' => $league['League']['slug'], 'event' => $event['Event']['slug'])); ?>
	</td>
	<td class="align-center"><?php echo $event['Division']['name']; ?></td>
	<td class="align-center"><?php echo $this->Utility->enum('Event', 'type', $event['Event']['type']); ?></td>
	<td class="align-center"><?php echo $this->Utility->enum('Event', 'for', $event['Event']['for']); ?></td>
	<td class="align-center"><?php echo $this->Utility->enum('Event', 'seed', $event['Event']['seed']); ?></td>
	<td class="align-center"><?php echo $this->Tournament->eventSignupDates($event); ?></td>
	<td class="align-center"><?php echo $this->Tournament->eventPlayDates($event); ?></td>
	<td class="align-center"><?php echo number_format($event['Event']['event_participant_count']); ?></td>
</tr>