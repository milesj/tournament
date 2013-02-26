
<?php if ($bracket->isElimination()) { ?>
	<div class="bracket-line">
		<div></div>
	</div>
<?php } ?>

<div class="match"<?php if (!empty($match)) { ?> id="match-<?php echo $match['id']; ?>"<?php } ?>>
	<table>
	<tbody>
		<?php
		echo $this->element('brackets/participant', array(
			'match' => $match,
			'type' => 'home',
			'participant' => $home,
			'currentRound' => $currentRound
		));

		echo $this->element('brackets/participant', array(
			'match' => $match,
			'type' => 'away',
			'participant' => $away,
			'currentRound' => $currentRound
		)); ?>
	</tbody>
	</table>
</div>