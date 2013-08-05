<?php
$maxRounds = $bracket->getMaxRounds(); ?>

<style>
	body { width: <?php echo ($maxRounds * 335); ?>px; min-width: 100%; }
</style>

<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __d('tournament', 'Rounds %s of %s', $bracket->getCompletedRounds(), $maxRounds); ?></h3>
	</div>

	<div class="bracket single-elim">

		<?php // Loop over each round
		for ($i = 1; $i <= $maxRounds; $i++) {
			$matchesToShow = $bracket->calculateRoundMatches($i);
			$round = $i;
			$class = '';

			// Give the final rounds a special class
			switch ($matchesToShow) {
				case 1:
					$class = 'finals';
					$matchesToShow++; // bronze
				break;
				case 2:	$class = 'semi-finals'; break;
				case 4:	$class = 'quarter-finals'; break;
			} ?>

			<div class="bracket-column round-<?php echo $round; ?> <?php echo $class; ?>">
				<ul>

				<?php // Loop over official matches
				$matches = $bracket->getMatches($round);

				for ($m = 1; $m <= $matchesToShow; $m++) {
					if ($m == 2 && $bracket->isRound($round, Bracket::FINALS)) { ?>

						<li class="bronze-match">
							<div class="match-title">
								<?php echo __d('tournament', 'Bronze Match'); ?>
							</div>

							<?php echo $this->element('brackets/match', array(
								'match' => isset($matches[$m]) ? $matches[$m] : null,
								'currentRound' => $round
							)); ?>
						</li>

					<?php } else { ?>

						<li>
							<?php echo $this->element('brackets/match', array(
								'match' => isset($matches[$m]) ? $matches[$m] : null,
								'currentRound' => $round
							)); ?>
						</li>

					<?php }
				} ?>

				</ul>
			</div>

		<?php }

		// Display winners column
		if ($winner) { ?>

			<div class="bracket-column round-<?php echo $maxRounds; ?> winner">
				<ul>
					<li><?php echo $this->element('brackets/winner'); ?></li>
				</ul>
			</div>

		<?php } ?>

	</div>
</div>