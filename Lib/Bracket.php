<?php

App::uses('Event', 'Tournament.Model');

class Bracket {

	const FINALS = 0;
	const SEMI_FINALS = 1;
	const QUARTER_FINALS = 2;

	/**
	 * Event information.
	 *
	 * @var array
	 */
	protected $_event;

	/**
	 * List of matches.
	 *
	 * @var array
	 */
	protected $_matches = array();

	/**
	 * List of participants.
	 *
	 * @var array
	 */
	protected $_participants = array();

	/**
	 * Mapping of pools.
	 *
	 * @var array
	 */
	protected $_pools = array();

	/**
	 * Mapping of rounds.
	 *
	 * @var array
	 */
	protected $_rounds = array();

	/**
	 * Mapping of participant total scores by pool or round.
	 *
	 * @var array
	 */
	protected $_scores = array();

	/**
	 * The order of participants by highest score.
	 *
	 * @var array
	 */
	protected $_standings = array();

	/**
	 * Store the event.
	 *
	 * @param array $event
	 */
	public function __construct($event) {
		$this->_event = $event;
	}

	/**
	 * Loop through a list of match IDs and calculate the current point scores.
	 *
	 * @param array $match_ids
	 * @return array
	 */
	public function calculateScores(array $match_ids) {
		$scores = array();

		foreach ($match_ids as $match_id) {
			$match = $this->getMatch($match_id);
			$home_id = $match['home_id'];
			$away_id = $match['away_id'];

			if (empty($scores[$home_id])) {
				$scores[$home_id] = 0;
			}

			if (empty($scores[$away_id])) {
				$scores[$away_id] = 0;
			}

			if ($match['winner'] == Match::PENDING) {
				continue;
			}

			$scores[$home_id] += $match['homeScore'];
			$scores[$away_id] += $match['awayScore'];
		}

		return $scores;
	}

	/**
	 * Loop through a list of match IDs and calculate the current participant standings.
	 *
	 * @param array $match_ids
	 * @return array
	 */
	public function calculateStandings(array $match_ids) {
		$match_ids = array_unique($match_ids);
		$scores = array();

		foreach ($match_ids as $match_id) {
			$match = $this->getMatch($match_id);
			$home_id = $match['home_id'];
			$away_id = $match['away_id'];

			if (empty($scores[$home_id])) {
				$scores[$home_id] = null;
			}

			if (empty($scores[$away_id])) {
				$scores[$away_id] = null;
			}

			if ($match['winner'] == Match::PENDING) {
				continue;
			}

			if ($match['homeOutcome'] == Match::WIN) {
				$scores[$home_id] += 3;
			} else if ($match['homeOutcome'] == Match::LOSS) {
				$scores[$home_id] += -3;
			} else if ($match['homeOutcome'] == Match::TIE) {
				$scores[$home_id] += 1;
			}

			if ($match['awayOutcome'] == Match::WIN) {
				$scores[$away_id] += 3;
			} else if ($match['awayOutcome'] == Match::LOSS) {
				$scores[$away_id] += -3;
			} else if ($match['awayOutcome'] == Match::TIE) {
				$scores[$away_id] += 1;
			}
		}

		$standings = array();

		foreach ($scores as $participant_id => $score) {
			if ($score === null) {
				continue;
			} else if (isset($standings[$score])) {
				$standings[$score][] = $participant_id;
			} else {
				$standings[$score] = array($participant_id);
			}
		}

		ksort($standings);
		$standings = array_values(array_reverse($standings, true));

		return $standings;
	}

	/**
	 * Return the count of how many rounds have been completed.
	 *
	 * @return int
	 */
	public function getCompletedRounds() {
		return count($this->_rounds);
	}

	/**
	 * Return a single match by ID.
	 *
	 * @param int $id
	 * @return array
	 * @throws Exception
	 */
	public function getMatch($id) {
		if (isset($this->_matches[$id])) {
			return $this->_matches[$id];
		}

		throw new Exception(sprintf('Match %s does not exist', $id));
	}

	/**
	 * Return all matches.
	 *
	 * @return array
	 */
	public function getMatches() {
		return $this->_matches;
	}

	/**
	 * Return the max amount of pools that will be played.
	 *
	 * @return int
	 */
	public function getMaxPools() {
		return count($this->getPools());
	}

	/**
	 * Return the max amount of rounds that will be played.
	 *
	 * @return int
	 */
	public function getMaxRounds() {
		if (!empty($this->_event['maxRounds'])) {
			return (int) $this->_event['maxRounds'];
		}

		return count($this->getRounds());
	}

	/**
	 * Return a single participant by ID.
	 *
	 * @param int $id
	 * @return array
	 * @throws Exception
	 */
	public function getParticipant($id) {
		if (isset($this->_participants[$id])) {
			return $this->_participants[$id];
		}

		throw new Exception(sprintf('Participant %s does not exist', $id));
	}

	/**
	 * Return all participants.
	 *
	 * @return array
	 */
	public function getParticipants() {
		return $this->_participants;
	}

	/**
	 * Return a single pool by ID.
	 *
	 * @param int $id
	 * @return array
	 * @throws Exception
	 */
	public function getPool($id) {
		if (isset($this->_pools[$id])) {
			return $this->_pools[$id];
		}

		throw new Exception(sprintf('Pool %s does not exist', $id));
	}

	/**
	 * Return all pools.
	 *
	 * @return array
	 */
	public function getPools() {
		return array_keys($this->_pools);
	}

	/**
	 * Return all matches for a participant within a pool.
	 *
	 * @param int $pool_id
	 * @param int $participant_id
	 * @return array
	 * @throws Exception
	 */
	public function getPoolMatches($pool_id, $participant_id) {
		$matches = array();

		if (empty($this->_pools[$pool_id][$participant_id])) {
			return $matches;
		}

		$ids = $this->_pools[$pool_id][$participant_id];

		foreach ($ids as $match_id) {
			$matches[$match_id] = $this->getMatch($match_id);
		}

		return $matches;
	}

	/**
	 * Return all participants within a pool.
	 *
	 * @param int $pool_id
	 * @return array
	 */
	public function getPoolParticipants($pool_id) {
		$pool = $this->getPool($pool_id);
		$participants = array();

		foreach (array_keys($pool) as $participant_id) {
			$participants[$participant_id] = $this->getParticipant($participant_id);
		}

		return $participants;
	}

	/**
	 * Return the participants standing in the pool.
	 *
	 * @param int $pool_id
	 * @param int $participant_id
	 * @return int
	 * @throws Exception
	 */
	public function getPoolStanding($pool_id, $participant_id) {
		if (!isset($this->_standings[$pool_id])) {
			throw new Exception(sprintf('Invalid pool %s', $pool_id));
		}

		foreach ($this->_standings[$pool_id] as $i => $participants) {
			if (in_array($participant_id, $participants)) {
				return ($i + 1);
			}
		}

		return null;
	}

	/**
	 * Return all rounds. The output will change depending on the round type.
	 *
	 * @return array
	 */
	public function getRounds() {
		switch ($this->_event['type']) {
			case Event::SINGLE_ELIM:
			case Event::DOUBLE_ELIM:
				$participantCount = count($this->_rounds[1]);
				$rounds = array(1 => $participantCount);

				while ($participantCount != 1) {
					$participantCount = ceil($participantCount / 2);
					$rounds[] = $participantCount;
				}

				return $rounds;
			break;
		}

		return array_keys($this->_rounds);
	}

	/**
	 * Return all matches for a specific round.
	 *
	 * @param int $round_id
	 * @return array
	 * @throws Exception
	 */
	public function getRoundMatches($round_id) {
		$matches = array();

		if (empty($this->_rounds[$round_id])) {
			return $matches;
		}

		$ids = $this->_rounds[$round_id];

		foreach ($ids as $match_id) {
			$matches[$match_id] = $this->getMatch($match_id);
		}

		return $matches;
	}

	/**
	 * Return the participants standing in the bracket.
	 * If it's a bracket tree, only show the standing during a specific round.
	 *
	 * @param int $participant_id
	 * @param int $round
	 * @return int
	 */
	public function getStanding($participant_id, $round = null) {
		$standing = null;

		foreach ($this->_standings as $i => $participants) {
			if (in_array($participant_id, $participants)) {
				$standing = ($i + 1);
				break;
			}
		}

		if (!$this->isElimination()) {
			return $standing;
		}

		$maxRounds = $this->getMaxRounds();
		$remainder = $maxRounds - $round;

		if ($remainder == self::FINALS && $standing <= 2) {
			return $standing;

		} else if ($remainder == self::SEMI_FINALS && ($standing == 4 || $standing == 3)) {
			return $standing;

		} else if ($remainder == self::QUARTER_FINALS && ($standing >= 5 && $standing <= 8)) {
			return $standing;
		}

		return null;
	}

	/**
	 * Return all standings.
	 *
	 * @return array
	 */
	public function getStandings() {
		return $this->_standings;
	}

	/**
	 * Return the total pool count.
	 *
	 * @return int
	 */
	public function getTotalPools() {
		return count($this->_pools);
	}

	/**
	 * Return the total round count.
	 *
	 * @return int
	 */
	public function getTotalRounds() {
		if ($this->_event['type'] == Event::ROUND_ROBIN) {
			$pools = array_values($this->_pools);

			return count($pools[0]);
		}

		return count($this->_rounds);
	}

	/**
	 * Is the event an elimination event?
	 *
	 * @return bool
	 */
	public function isElimination() {
		return in_array($this->_event['type'], array(Event::SINGLE_ELIM, Event::DOUBLE_ELIM));
	}

	/**
	 * Determine which round type it currently is.
	 *
	 * @param int $round
	 * @param int $type
	 * @return bool
	 */
	public function isRound($round, $type) {
		return ($this->getMaxRounds() - $round) == $type;
	}

	/**
	 * Determine if the current round is in a finals round.
	 *
	 * @param int $round
	 * @return bool
	 */
	public function isInFinals($round) {
		return ($this->getMaxRounds() - $round) <= self::QUARTER_FINALS;
	}

	/**
	 * Set a mapping of matches data (returned from Match) indexed by match ID.
	 *
	 * @param array $matches
	 * @return Bracket
	 */
	public function setMatches(array $matches) {
		$this->_matches = $matches;

		return $this;
	}

	/**
	 * Set a mapping of participants data (either Team or Player) indexed by ID.
	 *
	 * @param array $participants
	 * @return Bracket
	 */
	public function setParticipants(array $participants) {
		$this->_participants = $participants;

		return $this;
	}

	/**
	 * Set an array of pool ID to match IDs. The index should be the pool ID and
	 * the value should be an array of participant IDs in correct order.
	 * Each participant should have an array of match IDs.
	 *
	 * @param array $pools
	 * @return Bracket
	 * @throws Exception
	 */
	public function setPools(array $pools) {
		$this->_pools = $pools;

		if (empty($this->_matches)) {
			throw new Exception('Matches must be set before pools');
		}

		// Cache and tally the current scores
		foreach ($pools as $pool_id => $participants) {
			$match_ids = array();

			foreach ($participants as $participant_id => $matches) {
				$match_ids = array_merge($match_ids, $matches);
			}

			$this->_scores[$pool_id] = $this->calculateScores($match_ids);
			$this->_standings[$pool_id] = $this->calculateStandings($match_ids);
		}

		return $this;
	}

	/**
	 * Set an array of round ID to match IDs. The index should be the round ID and
	 * the value should be an array of match IDs in correct order.
	 *
	 * @param array $rounds
	 * @return Bracket
	 * @throws Exception
	 */
	public function setRounds(array $rounds) {
		$this->_rounds = $rounds;

		if (empty($this->_matches)) {
			throw new Exception('Matches must be set before pools');
		}

		// Cache and tally the current scores
		$match_ids = array();

		foreach ($rounds as $matches) {
			$match_ids = array_merge($match_ids, $matches);
		}

		$this->_scores = $this->calculateScores($match_ids);
		$this->_standings = $this->calculateStandings($match_ids);

		return $this;
	}

}