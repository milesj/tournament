
<style>
body { width: <?php echo ($bracket->getMaxRounds() * 335); ?>px; min-width: 100%; }
</style>

<div class="container">
	<div class="container-head">
		<h3><?php echo __d('tournament', 'Rounds %s of %s', $bracket->getCompletedRounds(), $bracket->getMaxRounds()); ?></h3>
	</div>

	<div class="container-body bracket single-elim">

		<?php // Loop over each round
		foreach ($bracket->getRounds() as $round => $matchesToShow) {
			$matchesDisplayed = 0;
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
				if ($matches = $bracket->getRoundMatches($round)) {
					foreach ($matches as $match) { ?>

					<li>
						<?php echo $this->element('brackets/match', array(
							'match' => $match,
							'home' => $bracket->getParticipant($match['home_id']),
							'away' => $bracket->getParticipant($match['away_id']),
							'currentRound' => $round
						)) ?>
					</li>

					<?php $matchesDisplayed++;
				} }

				// Loop over and create fake matches to fill the gaps
				if ($matchesDisplayed < $matchesToShow) {
					for ($i = $matchesDisplayed; $i < $matchesToShow; $i++) { ?>

					<li>
						<?php echo $this->element('brackets/match') ?>
					</li>

					<?php $matchesDisplayed++;
				} } ?>

				</ul>
			</div>

		<?php }

		// Display winners column
		if ($winner) { ?>

			<div class="bracket-column round-<?php echo $bracket->getMaxRounds(); ?> winner">
				<ul>
					<li><?php echo $this->element('brackets/winner'); ?></li>
				</ul>
			</div>

		<?php } ?>

		<span class="clear"></span>
	</div>
</div>