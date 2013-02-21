<?php
$rounds = (int) $event['Event']['maxRounds']; ?>

<style>
body { width: <?php echo ($rounds * 325); ?>px;  }
</style>

<div class="bracket swiss">
	<div class="container">
		<div class="container-body">

			<?php // Loop over all the rounds
			for ($i = 1; $i <= $rounds; $i++) { ?>

				<div class="bracket-column">
					<div class="container-head">
						<h3><?php echo __d('tournament', 'Round %s', $i); ?></h3>
					</div>

					<?php if ($matches = $bracket->getRoundMatches($i)) { ?>
						<ul>
							<?php foreach ($matches as $match) { ?>

								<li>
									<?php echo $this->element('brackets/match', array(
										'match' => $match,
										'home' => $bracket->getParticipant($match['home_id']),
										'away' => $bracket->getParticipant($match['away_id'])
									)) ?>
								</li>

							<?php } ?>
						</ul>
					<?php } ?>
				</div>

			<?php } ?>

			<span class="clear"></span>
		</div>
	</div>
</div>