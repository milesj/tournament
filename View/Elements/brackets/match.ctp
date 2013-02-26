
<div class="match"<?php if (!empty($match)) { ?> id="match-<?php echo $match['id']; ?>"<?php } ?>>
	<table>
	<tbody>

		<?php if (!empty($home)) { ?>

			<tr class="participant-home status-<?php echo $this->Bracket->matchStatus($home['id'], $match); ?>">
				<td class="cell-name">
					<?php echo $this->Bracket->participant($home); ?>
				</td>
				<td class="cell-score">
					<?php if ($match['winner'] != Match::PENDING) {
						echo $match['homeScore'];
					}

					if ($standing = $bracket->getStanding($home['id'], $currentRound)) { ?>
						<div class="standing"><?php echo $standing; ?></div>
					<?php } ?>
				</td>
			</tr>

		<?php } else { ?>

			<tr class="participant-home">
				<td></td>
				<td class="score"></td>
			</tr>

		<?php } ?>

		<?php if (!empty($away)) { ?>

			<tr class="participant-away status-<?php echo $this->Bracket->matchStatus($away['id'], $match); ?>">
				<td class="cell-name">
					<?php echo $this->Bracket->participant($away); ?>
				</td>
				<td class="cell-score">
					<?php if ($match['winner'] != Match::PENDING) {
						echo $match['awayScore'];
					}

					if ($standing = $bracket->getStanding($away['id'], $currentRound)) { ?>
						<div class="standing"><?php echo $standing; ?></div>
					<?php } ?>
				</td>
			</tr>

		<?php } else { ?>

			<tr class="participant-away">
				<td></td>
				<td class="cell-score"></td>
			</tr>

		<?php } ?>

	</tbody>
	</table>
</div>