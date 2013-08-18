<?php $id = $participant['EventParticipant']['team_id'] ?: $participant['EventParticipant']['player_id']; ?>

<tr class="participant-<?php echo $type; ?> participant-<?php echo $id; ?> status-<?php echo $this->Bracket->matchStatus($id, $match); ?>">
	<?php if ($settings['showBracketSeed']) { ?>
		<td class="cell-seed js-tooltip" data-tooltip="<?php echo __d('tournament', 'Seed'); ?>">
			<?php echo $participant['EventParticipant']['seed']; ?>
		</td>
	<?php } ?>
	<td class="cell-name">
		<?php echo $this->Bracket->participant($participant); ?>
	</td>
	<td class="cell-score js-tooltip" data-tooltip="<?php echo __d('tournament', 'Points'); ?>">
		<?php
		if ($match['Match']['winner'] != Match::PENDING) {
			echo $match['Match'][$type . 'Points'];

			if ($bracket->isElimination()) {
				if ($standing = $bracket->getStanding($id, $currentRound)) { ?>

					<div class="standing standing-<?php echo $standing; ?> js-tooltip" data-tooltip="<?php echo __d('tournament', 'Standing'); ?>">
						<span><?php echo $this->Bracket->standing($standing); ?></span>
					</div>

			<?php }
			}
		} ?>
	</td>
</tr>