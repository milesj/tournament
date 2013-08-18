<style type="text/css">
	body { width: <?php echo ($bracket->getCompletedRounds() * 325); ?>px; min-width: 100%; }
</style>

<div class="swiss">
	<div class="swiss-head">
		<h3><?php echo __d('tournament', 'Rounds %s of %s', $bracket->getCompletedRounds(), $bracket->getMaxRounds()); ?></h3>
	</div>

	<div class="swiss-body bracket">

		<?php foreach ($bracket->getRounds() as $round) { ?>

			<div class="bracket-column">
				<?php if ($matches = $bracket->getMatches($round)) { ?>

					<ul>
						<?php foreach ($matches as $match) { ?>

							<li>
								<?php echo $this->element('brackets/match', array(
									'match' => $match,
									'currentRound' => $round
								)); ?>
							</li>

						<?php } ?>
					</ul>

				<?php } ?>
			</div>

		<?php }

		// Display winners column
		if ($winner) { ?>

			<div class="bracket-column winner">
				<ul>
					<li><?php echo $this->element('brackets/winner'); ?></li>
				</ul>
			</div>

		<?php } ?>

	</div>
</div>