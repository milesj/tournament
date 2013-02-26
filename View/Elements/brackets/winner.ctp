
<div class="match">
	<table>
	<tbody>
		<tr class="participant-winner">
			<?php if ($icon = $this->Bracket->participant($winner, 'logo')) { ?>
				<td class="cell-icon">
					<?php echo $this->Html->link($icon, $this->Bracket->participant($winner, 'url'), array('escape' => false)); ?>
				</td>
			<?php } ?>

			<td class="cell-name">
				<?php echo $this->Bracket->participant($winner, 'link'); ?>

				<div class="stats">
					<span class="stat stat-wins" data-tooltip="<?php echo __d('tournament', 'Wins'); ?>"><?php echo $winner['EventParticipant']['wins']; ?></span> /
					<span class="stat stat-losses" data-tooltip="<?php echo __d('tournament', 'Losses'); ?>"><?php echo $winner['EventParticipant']['losses']; ?></span> /
					<span class="stat stat-ties" data-tooltip="<?php echo __d('tournament', 'Ties'); ?>"><?php echo $winner['EventParticipant']['ties']; ?></span> /
					<span class="stat stat-points" data-tooltip="<?php echo __d('tournament', 'Points'); ?>"><?php echo $winner['EventParticipant']['points']; ?></span>
				</div>
			</td>
		</tr>
	</tbody>
	</table>
</div>