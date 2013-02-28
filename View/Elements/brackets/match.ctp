
<?php
$home = null;
$away = null;
$isBye = false;

if ($match) {
	$home = $bracket->getParticipant($match['Match']['home_id']);
	$away = $bracket->getParticipant($match['Match']['away_id']);
	$isBye = ($match['Match']['homeOutcome'] == Match::BYE);
}

if ($bracket->isElimination()) { ?>
	<div class="bracket-line">
		<div></div>
	</div>
<?php }

if (isset($title)) { ?>
	<div class="match-title">
		<?php echo $title; ?>
	</div>
<?php } ?>

<div class="match <?php if ($isBye) echo 'match-bye'; ?>"
	<?php if ($match) { ?> id="match-<?php echo $match['Match']['id']; ?>"<?php } ?>>
	<table>
	<tbody>
		<?php if ($match && $home) {
			echo $this->element('brackets/participant', array(
				'match' => $match,
				'type' => 'home',
				'participant' => $home,
				'currentRound' => $currentRound
			));
		} else {?>

			<tr class="participant-home">
				<td class="cell-seed"></td>
				<td></td>
				<td class="cell-score"></td>
			</tr>

		<?php }

		if ($match && $away) {
			echo $this->element('brackets/participant', array(
				'match' => $match,
				'type' => 'away',
				'participant' => $away,
				'currentRound' => $currentRound
			));
		} else { ?>

			<tr class="participant-away">
				<td class="cell-seed"></td>
				<?php if ($isBye) { ?>
					<td class="cell-bye"><?php echo __d('tournament', 'Bye'); ?></td>
				<?php } else { ?>
					<td></td>
				<?php } ?>
				<td class="cell-score"></td>
			</tr>

		<?php } ?>
	</tbody>
	</table>
</div>