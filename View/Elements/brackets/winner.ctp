<div class="bracket-line-horizontal"></div>

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
						<span class="stat stat-wins js-tooltip" data-tooltip="<?php echo __d('tournament', 'Wins'); ?>">
							<?php echo $winner['EventParticipant']['wins']; ?>
							<span class="icon-plus-sign"></span>
						</span>
						<span class="stat stat-losses js-tooltip" data-tooltip="<?php echo __d('tournament', 'Losses'); ?>">
							<?php echo $winner['EventParticipant']['losses']; ?>
							<span class="icon-remove-sign"></span>
						</span>
						<span class="stat stat-ties js-tooltip" data-tooltip="<?php echo __d('tournament', 'Ties'); ?>">
							<?php echo $winner['EventParticipant']['ties']; ?>
							<span class="icon-minus-sign"></span>
						</span>
						<span class="stat stat-points js-tooltip" data-tooltip="<?php echo __d('tournament', 'Points'); ?>">
							<?php echo $winner['EventParticipant']['points']; ?>
							<span class="icon-trophy"></span>
						</span>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>