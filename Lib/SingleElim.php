<?php

class SingleElim {

	public function generatePlayerBrackets($players) {
		$players = Hash::sort($players, '{n}.Player.score', 'desc', 'numeric');
		$total = count($players);
	}

}