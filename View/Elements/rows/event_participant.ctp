<tr>
	<td>
		<?php echo $this->Html->link($event['name'], array('controller' => 'events', 'action' => 'view', 'league' => $event['League']['slug'], 'event' => $event['slug'])); ?>
	</td>
	<td>
		<?php echo $this->Html->link($event['League']['name'], array('controller' => 'leagues', 'action' => 'view', 'league' => $event['League']['slug'])); ?>
	</td>
	<td class="align-center"><?php echo $event['Division']['name']; ?></td>
	<td class="align-center"><?php echo number_format($event['EventParticipant']['points']); ?></td>
	<td class="align-center"><?php echo number_format($event['EventParticipant']['wins']); ?></td>
	<td class="align-center"><?php echo number_format($event['EventParticipant']['losses']); ?></td>
	<td class="align-center"><?php echo number_format($event['EventParticipant']['ties']); ?></td>
	<td class="align-right">
		<?php echo $this->Time->niceShort($event['EventParticipant']['created'], $this->Tournament->timezone()); ?>
	</td>
</tr>