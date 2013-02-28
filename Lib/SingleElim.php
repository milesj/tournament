<?php

App::uses('Tournament', 'Tournament.Lib');
App::uses('Match', 'Tournament.Model');

class SingleElim extends Tournament {

	/**
	 * Event type.
	 *
	 * @var int
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
		if ($nextRound == 1) {
			$participants = $this->organizeSeeds($this->getParticipants());

		// Other rounds should use the match order
		} else {
			$participants = $this->getWinners();
		}

		// Create matches
		$half = ceil(count($participants) / 2);

		for ($i = 0; $i < $half; $i++) {
			$home_id = array_shift($participants);
			$away_id = array_shift($participants);

			$this->createMatch($home_id, $away_id, $i, $nextRound);
		}

		// Update event status
		$this->Event->id = $this->_id;
		$this->Event->save(array(
			'isGenerated' => Event::YES,
			'round' => $nextRound
		), false);
	}

}