<?php

App::uses('Tournament', 'Tournament.Lib');
App::uses('Match', 'Tournament.Model');

class SingleElim extends Tournament {

	/**
	 * Event type.
	 *
	 * @type int
	 */
	protected $_type = Event::SINGLE_ELIM;

	/**
	 * Generate matches for a single elimination event.
	 *
	 * 	- Top participants should be seeded so that they don't compete against each other until the last rounds
	 * 	- Winners of each match will advance to the next round
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

		// End the event if the max rounds is reached
		if ($maxRounds && $nextRound > $maxRounds) {
			$this->endEvent();
		}

		// Organize players by seed for first round
		// Determine how many players should get byes based on the number of participants
		if ($nextRound == 1) {
			$participants = $this->getParticipants();

			if ($byes = $this->_event['startingByes']) {
				$participants = array_merge($participants, array_fill(0, $byes, null));
			}

			$participants = $this->organizeSeeds($participants);

		// Other rounds should use the match order
		} else {
			$participants = $this->getWinners();
		}

		$half = round(count($participants) / 2);

		for ($i = 1; $i <= $half; $i++) {
			$home_id = array_shift($participants);
			$away_id = array_shift($participants);

			$this->createMatch($home_id, $away_id, $i, $nextRound);
		}

		// Generate bronze match
		if ($nextRound === $maxRounds || $half === 1) {
			$bronzePlayers = $this->getLosers();

			// Remove nulls (byes)
			$bronzePlayers = array_filter($bronzePlayers);

			if (count($bronzePlayers) == 2) {
				$this->createMatch(array_shift($bronzePlayers), array_shift($bronzePlayers), $i, $nextRound);
			}
		}

		// Advance matches that are byes
		$this->advanceByes($nextRound);

		// Update event status
		$this->Event->id = $this->_id;
		$this->Event->save(array(
			'isGenerated' => Event::YES,
			'round' => $nextRound
		), false);
	}

}