
<div class="match" id="match-<?php echo $match['id']; ?>">
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
</div>