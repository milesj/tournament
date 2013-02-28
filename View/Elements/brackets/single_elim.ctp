<?php
$maxRounds = $bracket->getMaxRounds(); ?>

<style>
body { width: <?php echo ($maxRounds * 335); ?>px; min-width: 100%; }
</style>

<div class="container">
	<div class="container-head">
		<h3><?php echo __d('tournament', 'Rounds %s of %s', $bracket->getCompletedRounds(), $maxRounds); ?></h3>
	</div>

	<div class="container-body bracket single-elim">

		<?php // Loop over each round
		for ($i = 1; $i <= $maxRounds; $i++) {
			$matchesToShow = $bracket->calculateRoundMatches($i);
			$matchesDisplayed = 0;
			$round = $i;
			$class = '';

			// Give the final rounds a special class
			switch ($matchesToShow) {
				case 1:	$class = 'finals'; break;
				case 2:	$class = 'semi-finals'; break;
				case 4:	$class = 'quarter-finals'; break;
			} ?>

			<div class="bracket-column round-<?php echo $round; ?> <?php echo $class; ?>">
				<ul>

				<?php // Loop over official matches
				if ($matches = $bracket->getMatches($round)) {
					foreach ($matches as $match) { ?>

					<li>
						<?php echo $this->element('brackets/match', array(
							'match' => $match,
							'currentRound' => $round
						)); ?>
					</li>

					<?php $matchesDisplayed++;
				} }

				// Loop over and create fake matches to fill the gaps
				while ($matchesDisplayed < $matchesToShow) { ?>

					<li>
						<?php echo $this->element('brackets/match', array(
							'match' => null,
							'currentRound' => $round
						)); ?>
					</li>

					<?php $matchesDisplayed++;
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

		<span class="clear"></span>
	</div>
</div>