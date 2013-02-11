<?php

App::uses('Tournament', 'Tournament.Lib');
App::uses('Event', 'Tournament.Model');

class RoundRobin extends Tournament {

	/**
	 * Fetch event information.
	 *
	 * @param array $event
	 * @throws Exception
	 */
	public function __construct($event) {
		parent::__construct($event);

		if ($this->_event['type'] != Event::ROUND_ROBIN) {
			throw new Exception('Event is not Round Robin');

		} else if ($event['Event']['isGenerated']) {
			throw new Exception('Matches have already been generated for this event');
		}
	}

	/**
	 * Generate matches for a round robin event.
	 *
	 * 	- Every participant will play every other participant within their pool (or no pool = all)
	 * 	- Every match will be played in order based on round number
	 *
	 * @return void
	 * @throws Exception
	 */
	public function generateBrackets() {
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
				$round = 1;

				foreach ($participants as $away_id) {
					if (in_array($away_id, $exclude) || $home_id == $away_id) {
						continue;
					}

					$this->createMatch($home_id, $away_id, $round, ($index + 1));

					$round++;
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

}