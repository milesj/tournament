<tr>
	<td class="col-event-name">
		<?php echo $this->Html->link($event['name'], array('controller' => 'events', 'action' => 'view', 'league' => $event['League']['slug'], 'event' => $event['slug'])); ?>
	</td>
	<td class="col-league-name">
		<?php echo $this->Html->link($event['League']['name'], array('controller' => 'leagues', 'action' => 'view', 'league' => $event['League']['slug'])); ?>
	</td>
	<td class="col-division-name"><?php echo $event['Division']['name']; ?></td>
	<td class="col-participant-points"><?php echo number_format($event['EventParticipant']['points']); ?></td>
	<td class="col-participant-wins"><?php echo number_format($event['EventParticipant']['wins']); ?></td>
	<td class="col-participant-losses"><?php echo number_format($event['EventParticipant']['losses']); ?></td>
	<td class="col-participant-ties"><?php echo number_format($event['EventParticipant']['ties']); ?></td>
	<td class="col-participant-created">
		<?php echo $this->Time->niceShort($event['EventParticipant']['created'], $this->Tournament->timezone()); ?>
	</td>
</tr>