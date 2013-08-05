
<?php // Loop over each pool
foreach ($bracket->getPools() as $pool) { ?>

<div id="pool-<?php echo $pool; ?>" class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __d('tournament', 'Pool %s', $pool); ?></h3>
	</div>

	<div class="bracket round-robin">

		<?php // Loop over each round
		foreach ($bracket->getRounds($pool) as $round) {
			$participants = $bracket->getParticipants($round, $pool);
			$participant_ids = array_keys($participants);
			$matchesCount = count($participants); ?>

			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th colspan="<?php echo $matchesCount + 1; ?>" class="align-left">
							<?php echo __d('tournament', 'Round %s of %s', $round, $bracket->getMaxRounds()); ?>
						</th>
						<th><?php echo __d('tournament', 'Score'); ?></th>
						<th><?php echo __d('tournament', 'Standing'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<?php foreach ($participants as $participant) { ?>
							<td class="cell-participant">
								<?php echo $this->Bracket->participant($participant); ?>
							</td>
						<?php } ?>
						<td></td>
						<td></td>
					</tr>

					<?php // Loop over each participant
					foreach ($participants as $participant_id => $participant) {
						$matches = array_values($bracket->getMatches($round, $pool, $participant_id)); ?>

					<tr>
						<td class="cell-participant">
							<?php echo $this->Bracket->participant($participant); ?>
						</td>

						<?php // Loop over each match for the participant
						$count = 0;
						$winPoints = 0;
						$lossPoints = 0;

						for ($i = 0; $i < $matchesCount; $i++) {

							// Skip if playing against your self or no match found
							if ($participant_ids[$i] == $participant_id || empty($matches[$count])) { ?>

							<td class="cell-void"></td>

							<?php } else {
								$match = $matches[$count];
								$score = $this->Bracket->matchScore($participant_id, $match);
								$count++;

								if ($score) {
									$winPoints += $score[0];
									$lossPoints += $score[1];
								} ?>

							<td class="status-<?php echo $this->Bracket->matchStatus($participant_id, $match); ?>">
								<?php if ($score) {
									echo implode(' - ', $score);
								} ?>
							</td>

							<?php }
						} ?>

						<td class="cell-score">
							<?php echo $winPoints; ?> -
							<?php echo $lossPoints; ?>
						</td>

						<td class="cell-standing">
							<?php echo $this->Bracket->standing($bracket->getStanding($participant_id, $round, $pool)); ?>
						</td>
					</tr>

					<?php } ?>
				</tbody>
			</table>

		<?php } ?>

	</div>
</div>

<?php } ?>