<?php
$participant_ids = array_keys($bracket['participants']); ?>

<style type="text/css">
body { width: <?php echo ($bracket['rounds'] * 150); ?>px; }
.round-robin .table td { text-align: center; vertical-align: middle; padding: 10px; }
.cell-participant,
.cell-points { background: #fff; font-weight: bold; }
.cell-void { background: #d7d7d7; }
.cell-loss { background: #ffeeee; }
.cell-win { background: #eefff1; }
.cell-tie { background: #eef1ff; }
</style>

<div class="bracket">
<?php foreach ($bracket['pools'] as $pool => $participants) { ?>

<div id="pool-<?php echo $pool; ?>" class="container bracket round-robin">
	<div class="container-head">
		<h3><?php echo __d('tournament', 'Pool %s', $pool); ?></h3>
	</div>

	<div class="container-body">
		<div class="table no-paging">
			<table>
				<thead>
					<tr>
						<td> </td>
						<?php foreach ($participants as $participant_id => $matches) { ?>
							<td class="cell-participant">
								<?php echo $this->Bracket->getParticipant($participant_id); ?>
							</td>
						<?php } ?>
						<td> </td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($participants as $participant_id => $matches) { ?>
						<tr>
							<td class="cell-participant">
								<?php echo $this->Bracket->getParticipant($participant_id); ?>
							</td>

							<?php
							$round = 0;
							$winPoints = 0;
							$lossPoints = 0;

							for ($i = 0; $i < $bracket['rounds']; $i++) {
								if ($participant_ids[$i] == $participant_id || empty($matches[$round])) { ?>
									<td class="cell-void">
									</td>

								<?php } else {
									$match = $bracket['matches'][$matches[$round]];
									$round++;

									if ($match['winner'] != Match::PENDING) {
										if ($match['home_id'] == $participant_id) {
											$winPoint = $match['homeScore'];
											$lossPoint = $match['awayScore'];
										} else {
											$winPoint = $match['awayScore'];
											$lossPoint = $match['homeScore'];
										}

										$winPoints += $winPoint;
										$lossPoints += $lossPoint;
									} ?>

									<td class="cell-<?php echo $this->Bracket->getMatchStatus($participant_id, $match); ?>">
										<?php if ($match['winner'] == Match::PENDING) {
											echo '-';
										} else {
											echo $winPoint . ' - ' . $lossPoint;
										} ?>
									</td>

							<?php } } ?>

							<td class="cell-points">
								<?php echo $winPoints; ?> -
								<?php echo $lossPoints; ?>
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