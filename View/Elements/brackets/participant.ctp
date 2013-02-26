<?php if (!empty($participant)) {
	$id = $participant['id']; ?>

	<tr class="participant-<?php echo $type; ?> participant-<?php echo $id; ?> status-<?php echo $this->Bracket->matchStatus($id, $match); ?>">
		<td class="cell-seed"></td>
		<td class="cell-name">
			<?php echo $this->Bracket->participant($participant); ?>
		</td>
		<td class="cell-score">
			<?php if ($match['winner'] != Match::PENDING) {
				echo $match[$type . 'Score'];

				$standing = $bracket->getStanding($id, $currentRound);

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
		<td class="cell-seed"></td>
		<td></td>
		<td class="cell-score"></td>
	</tr>

<?php } ?>