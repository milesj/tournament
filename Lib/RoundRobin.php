<?php

App::uses('Tournament', 'Tournament.Lib');
App::uses('Event', 'Tournament.Model');

class RoundRobin extends Tournament {

	/**
	 * Generate matches for a round robin event.
	 *
	 * 	- Every participant will play every other participant within their pool (or no pool = all)
	 *	- If there are rounds, the participants in the same pool will play a new set of matches
	 * 	- Every match will be played in order based on seed number
	 *
	 * @return void
	 * @throws Exception
	 */
	public function generateMatches() {
		if ($this->_event['isFinished']) {
			throw new Exception('Event has already finished');
		}

		$nextRound = (int) $this->_event['round'] + 1;
		$maxRounds = (int) $this->_event['maxRounds'];
		$poolSize = (int) $this->_event['poolSize'];
		$pools = array();

		// End the event if the max rounds is reached
		if ($maxRounds && $nextRound > $maxRounds) {
			$this->endEvent();
		}

		// First round chunk into pools
		if ($nextRound == 1) {
			$participants = $this->getParticipants();

			if ($poolSize) {
				$pools = array_chunk($participants, $poolSize);
			} else {
				$pools = array($participants);
			}

		// Other rounds group into pools
		} else {
			$participants = $this->getParticipants(array(
				'order' => array('EventParticipant.seed' => 'ASC')
			), true);

			foreach ($participants as $participant) {
				$p = $participant['EventParticipant'];
				$pools[$p['pool']][$p['seed']] = $p[$this->_forField];
			}

			// Reset so pool is 0 based
			$pools = array_values($pools);
		}

		// Loop over each pool and create all matches
		$currentSeed = 1;

		foreach ($pools as $index => $pool) {
			$exclude = array();
			$currentPool = ($index + 1);

			foreach ($pool as $home_id) {
				foreach ($pool as $away_id) {
					if (in_array($away_id, $exclude) || $home_id == $away_id) {
						continue;
					}

					$this->createMatch($home_id, $away_id, $nextRound, $currentPool);
				}

				$this->flagParticipant($home_id, $currentSeed, $currentPool);

				$currentSeed++;
				$exclude[] = $home_id;
			}
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
	 * 	- Save a mapping of matches and participants for easy lookup
	 * 	- Group all matches into an index of participants per pool
	 *
	 * @param array $matches
	 * @return Bracket
	 */
	public function organizeBrackets($matches) {
		$participants = $this->getParticipants(array(), true);
		$pools = array();

		foreach ($matches as $match) {
			$home_id = $match['Match']['home_id'];
			$away_id = $match['Match']['away_id'];
			$round = (int) $match['Match']['round'];
			$pool = (int) $match['Match']['pool'];

			// Store match IDs into a pool and round indexed by the participant
			if (empty($pools[$pool][$round][$home_id])) {
				$pools[$pool][$round][$home_id] = array();
			}

			if (empty($pools[$pool][$round][$away_id])) {
				$pools[$pool][$round][$away_id] = array();
			}

			$pools[$pool][$round][$home_id][] = $match['Match']['id'];
			$pools[$pool][$round][$away_id][] = $match['Match']['id'];
		}

		// Loop through and sort matches
		foreach ($pools as &$p) {
			foreach ($p as &$r) {
				foreach ($r as &$m) {
					sort($m, SORT_NUMERIC);
				}
			}
		}

		$bracket = new Bracket($this->_event);
		$bracket->setMatches($matches);
		$bracket->setParticipants($participants);
		$bracket->setPools($pools);

		return $bracket;
	}

	/**
	 * Validate the event is the correct type for the class.
	 */
	public function validate() {
		if ($this->_event['type'] != Event::ROUND_ROBIN) {
			throw new Exception('Event is not Round Robin');
		}
	}

}