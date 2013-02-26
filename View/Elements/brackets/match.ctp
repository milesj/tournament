
<?php
$match = empty($match) ? null : $match;
$home = empty($home) ? null : $home;
$away = empty($away) ? null : $away;

if ($bracket->isElimination()) { ?>
	<div class="bracket-line">
		<div></div>
	</div>
<?php } ?>

<div class="match"<?php if ($match) { ?> id="match-<?php echo $match['id']; ?>"<?php } ?>>
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