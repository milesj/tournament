<?php if (!empty($participant)) { ?>

	<tr class="participant-<?php echo $type; ?> status-<?php echo $this->Bracket->matchStatus($participant['id'], $match); ?>">
		<td class="cell-name">
			<?php echo $this->Bracket->participant($participant); ?>
		</td>
		<td class="cell-score">
			<?php if ($match['winner'] != Match::PENDING) {
				echo $match[$type . 'Score'];

				$standing = $bracket->getStanding($participant['id'], $currentRound);

				if ($standing && $bracket->isElimination()) { ?>
					<div class="standing standing-<?php echo $standing; ?>">
						<span><?php echo $standing; ?></span>
					</div>
				<?php }
			} ?>
		</td>
	</tr>

<?php } else { ?>

	<tr class="participant-<?php echo $type; ?>">
		<td></td>
		<td class="score"></td>
	</tr>

<?php } ?>