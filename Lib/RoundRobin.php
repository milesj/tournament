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

		if ($this->_event['Event']['type'] != Event::ROUND_ROBIN) {
			throw new Exception('Event is not Round Robin');
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
		$event = $this->_event;
		$participants = $this->getParticipants();

		if (!$participants) {
			throw new Exception('There are no participants for this event');
		}

		// Generate a list of IDs
		$participant_ids = array();
		$for = ($event['Event']['for'] == Event::TEAM) ? 'Team' : 'Player';

		foreach ($participants as $participant) {
			$participant_ids[] = $participant[$for]['id'];
		}

		// Cycle through all the IDs and generate match pairs
		$Match = ClassRegistry::init('Tournament.Match');

		if ($poolSize = $event['Event']['poolSize']) {
			$pools = array_chunk($participant_ids, $poolSize);
		} else {
			$pools = array($participant_ids);
		}

		foreach ($pools as $index => $pool) {
			$exclude = array();

			foreach ($pool as $home_id) {
				$round = 1;

				foreach ($participant_ids as $away_id) {
					if (in_array($away_id, $exclude) || $home_id == $away_id) {
						continue;
					}

					// Create the match pairs
					$Match->create();
					$Match->save(array(
						'league_id' => $event['Event']['league_id'],
						'event_id' => $this->_id,
						'home_id' => $home_id,
						'away_id' => $away_id,
						'type' => $event['Event']['for'],
						'round' => $round,
						'pool' => ($index + 1),
						//'playOn' => null @TODO
					));

					$round++;
				}

				$exclude[] = $home_id;
			}
		}

		// Update event status
		$Event = ClassRegistry::init('Tournament.Event');
		$Event->id = $this->_id;
		$Event->save(array(
			'isGenerated' => Event::YES
		), false);
	}

}