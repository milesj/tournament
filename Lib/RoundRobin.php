<?php

App::uses('Tournament', 'Tournament.Lib');
App::uses('Event', 'Tournament.Model');

class RoundRobin extends Tournament {

	/**
	 * Generate matches for a round robin event.
	 *
	 * 	- Every participant will play every other participant within their pool (or no pool = all)
	 * 	- Every match will be played in order based on round number
	 *
	 * @return void
	 * @throws Exception
	 */
	public function generateMatches() {
		if ($this->_event['isGenerated']) {
			throw new Exception('Matches have already been generated for this event');
		}

		$participants = $this->getParticipants();

		// Cycle through all the IDs and generate match pairs
		if ($poolSize = $this->_event['poolSize']) {
			$pools = array_chunk($participants, $poolSize);
		} else {
			$pools = array($participants);
		}

		foreach ($pools as $index => $pool) {
			$exclude = array();

			foreach ($pool as $home_id) {
				foreach ($pool as $away_id) {
					if (in_array($away_id, $exclude) || $home_id == $away_id) {
						continue;
					}

					$this->createMatch($home_id, $away_id, null, ($index + 1));
				}

				$exclude[] = $home_id;
			}
		}

		// Update event status
		$this->Event->id = $this->_id;
		$this->Event->save(array(
			'isGenerated' => Event::YES
		), false);
	}

	/**
	 * Organize a list of matches into the correct match order for brackets.
	 *
	 * @todo
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
		$pools = array();
		$list = array();

		foreach ($matches as $match) {
			$home_id = $match['Match']['home_id'];
			$away_id = $match['Match']['away_id'];
			$pool = (int) $match['Match']['pool'];

			if (empty($pools[$pool])) {
				$pools[$pool] = array();
			}

			// Store participant info
			if (empty($participants[$home_id])) {
				$participants[$home_id] = $match[$homeIndex];
			}

			if (empty($participants[$away_id])) {
				$participants[$away_id] = $match[$awayIndex];
			}

			// Store match info
			$list[$match['Match']['id']] = $match['Match'];

			// Store match IDs into a pool indexed by the participant
			if (empty($pools[$pool][$home_id])) {
				$pools[$pool][$home_id] = array();
			}

			if (empty($pools[$pool][$away_id])) {
				$pools[$pool][$away_id] = array();
			}

			$pools[$pool][$home_id][] = $match['Match']['id'];
			$pools[$pool][$away_id][] = $match['Match']['id'];
		}

		// Loop through and sort matches by round
		foreach ($pools as &$p) {
			foreach ($p as &$m) {
				sort($m, SORT_NUMERIC);
			}
		}

		return array(
			'matches' => $list,
			'participants' => $participants,
			'pools' => $pools,
			'rounds' => count($participants)
		);
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