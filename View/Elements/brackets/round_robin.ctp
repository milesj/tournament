
<div class="bracket round-robin">

	<?php // Loop over each pool
	foreach ($bracket->getPools() as $pool) {
		$participants = $bracket->getPoolParticipants($pool);
		$participant_ids = array_keys($participants);
		$rounds = $bracket->getTotalRounds(); ?>

	<div id="pool-<?php echo $pool; ?>" class="container">
		<div class="container-head">
			<h3><?php echo __d('tournament', 'Pool %s', $pool); ?></h3>
		</div>

		<div class="container-body">
			<div class="table no-paging">
				<table>
					<thead>
						<tr>
							<th><?php echo __d('tournament', 'Round'); ?></th>
							<?php for ($i = 1; $i <= $rounds; $i++) { ?>
								<th><?php echo $i; ?></th>
							<?php } ?>
							<th><?php echo __d('tournament', 'Score'); ?></th>
							<th><?php echo __d('tournament', 'Standing'); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<?php foreach ($participants as $participant) { ?>
								<td class="participant">
									<?php echo $this->Bracket->participantIcon($participant); ?>
									<?php echo $this->Bracket->participantLink($participant); ?>
								</td>
							<?php } ?>
							<td></td>
							<td></td>
						</tr>

						<?php // Loop over each participant
						foreach ($participants as $participant) {
							$matches = array_values($bracket->getPoolMatches($pool, $participant['id'])); ?>

						<tr>
							<td class="participant">
								<?php echo $this->Bracket->participantIcon($participant); ?>
								<?php echo $this->Bracket->participantLink($participant); ?>
							</td>

							<?php // Loop over each match for the participant
							$round = 0;
							$winPoints = 0;
							$lossPoints = 0;

							for ($i = 0; $i < $rounds; $i++) {

								// Skip if playing against your self or no match found
								if ($participant_ids[$i] == $participant['id'] || empty($matches[$round])) { ?>

								<td class="void"></td>

								<?php } else {
									$match = $matches[$round];
									$score = $this->Bracket->matchScore($participant['id'], $match);
									$round++;

									if ($score) {
										$winPoints += $score[0];
										$lossPoints += $score[1];
									} ?>

								<td class="status-<?php echo $this->Bracket->matchStatus($participant['id'], $match); ?>">
									<?php if ($score) {
										echo implode(' - ', $score);
									} ?>
								</td>

								<?php }
							} ?>

							<td class="score">
								<?php echo $winPoints; ?> -
								<?php echo $lossPoints; ?>
							</td>
							<td class="standing">
								<?php echo $this->Bracket->standing($bracket->getPoolStanding($pool, $participant['id'])); ?>
							</td>
						</tr>

						<?php } ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php } ?>

</div>