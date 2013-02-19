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
		return $matches;
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