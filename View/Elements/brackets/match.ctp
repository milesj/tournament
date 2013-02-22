
<div class="match"<?php if (!empty($match)) { ?> id="match-<?php echo $match['id']; ?>"<?php } ?>>

	<?php if (!empty($home)) { ?>
		<div class="participant participant-home status-<?php echo $this->Bracket->matchStatus($home['id'], $match); ?>">
			<span class="score">
				<?php if ($match['winner'] != Match::PENDING) {
					echo $match['homeScore'];
				} ?>
			</span>

			<?php if ($icon = $this->Bracket->participantIcon($home)) { ?>
				<span class="icon"><?php echo $icon; ?></span>
			<?php } ?>

			<span class="name"><?php echo $this->Bracket->participantLink($home); ?></span>
			<span class="clear"></span>
		</div>

	<?php } else { ?>
		<div class="participant participant-home">
			<span class="score"></span>
			<span class="clear"></span>
		</div>
	<?php } ?>

	<?php if (!empty($away)) { ?>
		<div class="participant participant-away status-<?php echo $this->Bracket->matchStatus($away['id'], $match); ?>">
			<span class="score">
				<?php if ($match['winner'] != Match::PENDING) {
					echo $match['awayScore'];
				} ?>
			</span>

			<?php if ($icon = $this->Bracket->participantIcon($away)) { ?>
				<span class="icon"><?php echo $icon; ?></span>
			<?php } ?>

			<span class="name"><?php echo $this->Bracket->participantLink($away); ?></span>
			<span class="clear"></span>
		</div>

	<?php } else { ?>
		<div class="participant participant-away">
			<span class="score"></span>
			<span class="clear"></span>
		</div>
	<?php } ?>
</div>