<?php

App::uses('Tournament', 'Tournament.Lib');
App::uses('Match', 'Tournament.Model');

class SingleElim extends Tournament {

	/**
	 * Generate matches for a single elimination event.
	 *
	 * 	- Top event participant will play bottom in the 1st round
	 * 	- Winners of each match will advance to the next round
	 *
	 * @return void
	 * @throws Exception
	 */
	public function generateMatches() {
		$nextRound = (int) $this->_event['round'] + 1;

		if ($nextRound == 1) {
			$this->generateFirstRound($nextRound);
		} else {
			$this->generateRound($nextRound);
		}

		// Update event status
		$this->Event->id = $this->_id;
		$this->Event->save(array(
			'isGenerated' => Event::YES,
			'round' => $nextRound
		), false);
	}

	/**
	 * Generate the 1st round brackets. The top player in the list should be matched against the bottom player.
	 * If there is an uneven amount of players, give the remaining player a bye.
	 *
	 * @param int $round
	 * @return void
	 */
	public function generateFirstRound($round) {
		$participants = $this->getParticipants();
		$half = ceil(count($participants) / 2);

		for ($i = 0; $i < $half; $i++) {
			$home_id = array_shift($participants);
			$away_id = array_pop($participants);

			$this->createMatch($home_id, $away_id, $round);
		}
	}

	/**
	 * Generate the 2nd and up round brackets. The top 2 players within each loop will be paired in a match.
	 * If there is an uneven amount of players, give the remaining player a bye.
	 *
	 * @param int $round
	 * @return void
	 * @throws Exception
	 */
	public function generateRound($round) {
		$participants = $this->getWinners();

		if (count($participants) == 1) {
			$this->endEvent();
		}

		$half = ceil(count($participants) / 2);

		for ($i = 0; $i < $half; $i++) {
			$home_id = array_shift($participants);
			$away_id = array_shift($participants);

			$this->createMatch($home_id, $away_id, $round);
		}
	}

	/**
	 * Return all the winners from the previous event round.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getWinners() {
		$matches = $this->Match->find('all', array(
			'conditions' => array(
				'Match.event_id' => $this->_id,
				'Match.round' => $this->_event['round']
			),
			'order' => array('Match.id' => 'ASC')
		));

		if (!$matches) {
			throw new Exception('No participants from the previous round');
		}

		$participant_ids = array();

		foreach ($matches as $match) {
			if ($match['Match']['winner'] == Match::HOME) {
				$participant_ids[] = $match['Match']['home_id'];

			} else if ($match['Match']['winner'] == Match::AWAY) {
				$participant_ids[] = $match['Match']['away_id'];
			}
		}

		return $participant_ids;
	}

	/**
	 * Validate the event is the correct type for the class.
	 */
	public function validate() {
		if ($this->_event['type'] != Event::SINGLE_ELIM) {
			throw new Exception('Event is not Single Elimination');
		}
	}

}