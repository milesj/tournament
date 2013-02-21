<?php

App::uses('Tournament', 'Tournament.Lib');

class Swiss extends Tournament {

	/**
	 * Generate matches for a swiss event.
	 *
	 * 	- Event participants will be paired each round with opponents of similar point score
	 *
	 * @return void
	 * @throws Exception
	 */
	public function generateMatches() {
		$nextRound = (int) $this->_event['round'] + 1;
		$maxRounds = $this->_event['maxRounds'];

		if ($maxRounds && $nextRound > $maxRounds) {
			throw new Exception('Max rounds reached for this event');
		}

		// First round order by seed
		if ($nextRound == 1) {
			$participants = $this->getParticipants();

		// Other rounds order by current event points
		} else {
			$participants = $this->getParticipants(array(
				'order' => array(
					'EventParticipant.points' => 'DESC',
					'EventParticipant.wins' => 'DESC',
					'EventParticipant.ties' => 'DESC'
				)
			));
		}

		// Create matches
		$half = ceil(count($participants) / 2);

		for ($i = 0; $i < $half; $i++) {
			$home_id = array_shift($participants);
			$away_id = array_shift($participants);

			$this->createMatch($home_id, $away_id, $nextRound);
		}

		// Update event status
		$this->Event->id = $this->_id;
		$this->Event->save(array(
			'isGenerated' => Event::YES,
			'round' => $nextRound
		), false);
	}

	/**
	 * Organize a list of matches into the correct match order for brackets.
	 *
	 * @param array $matches
	 * @return array
	 */
	public function organizeBrackets($matches) {
		if ($this->_event['for'] == Event::TEAM) {
			$homeIndex = 'HomeTeam';
			$awayIndex = 'AwayTeam';
		} else {
			$homeIndex = 'HomePlayer';
			$awayIndex = 'AwayPlayer';
		}

		$participants = array();
		$rounds = array();
		$list = array();

		foreach ($matches as $match) {
			$home_id = $match['Match']['home_id'];
			$away_id = $match['Match']['away_id'];
			$round = (int) $match['Match']['round'];

			// Store participant info
			if (empty($participants[$home_id])) {
				$participants[$home_id] = $match[$homeIndex];
			}

			if (empty($participants[$away_id])) {
				$participants[$away_id] = $match[$awayIndex];
			}

			// Store match IDs into rounds
			if (empty($rounds[$round])) {
				$rounds[$round] = array();
			}

			$rounds[$round][] = $match['Match']['id'];
			$list[$match['Match']['id']] = $match['Match'];
		}

		// Loop through and sort matches
		foreach ($rounds as &$m) {
			sort($m, SORT_NUMERIC);
		}

		$bracket = new Bracket($this->_event['type']);
		$bracket->setMatches($list);
		$bracket->setParticipants($participants);
		$bracket->setRounds($rounds);

		return $bracket;
	}

	/**
	 * Validate the event is the correct type for the class.
	 */
	public function validate() {
		if ($this->_event['type'] != Event::SWISS) {
			throw new Exception('Event is not Swiss');
		}
	}

}