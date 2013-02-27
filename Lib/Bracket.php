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
			$home_id = $match['Match']['home_id'];
			$away_id = $match['Match']['away_id'];

			if (empty($scores[$home_id])) {
				$scores[$home_id] = 0;
			}

			if (empty($scores[$away_id])) {
				$scores[$away_id] = 0;
			}

			if ($match['Match']['winner'] == Match::PENDING) {
				continue;
			}

			$scores[$home_id] += $match['Match']['homeScore'];
			$scores[$away_id] += $match['Match']['awayScore'];
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
			$home_id = $match['Match']['home_id'];
			$away_id = $match['Match']['away_id'];

			if (empty($scores[$home_id])) {
				$scores[$home_id] = null;
			}

			if (empty($scores[$away_id])) {
				$scores[$away_id] = null;
			}

			if ($match['Match']['winner'] == Match::PENDING) {
				continue;
			}

			if ($match['Match']['homeOutcome'] == Match::WIN) {
				$scores[$home_id] += 3;

			} else if ($match['Match']['homeOutcome'] == Match::LOSS) {
				$scores[$home_id] += -3;

			} else if ($match['Match']['homeOutcome'] == Match::TIE) {
				$scores[$home_id] += 1;
			}

			if ($match['Match']['awayOutcome'] == Match::WIN) {
				$scores[$away_id] += 3;

			} else if ($match['Match']['awayOutcome'] == Match::LOSS) {
				$scores[$away_id] += -3;

			} else if ($match['Match']['awayOutcome'] == Match::TIE) {
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

		return null;
	}

	/**
	 * Return all matches filtered by round, pool and participant.
	 *
	 * @param int $round
	 * @param int $pool
	 * @param int $participant_id
	 * @return array
	 */
	public function getMatches($round, $pool = null, $participant_id = null) {
		if ($pool) {
			$ids = $this->_pools[$pool][$round];
		} else {
			$ids = $this->_rounds[$round];
		}

		// Filter down again to participant
		if ($participant_id) {
			$ids = $ids[$participant_id];
		}

		$matches = array();

		foreach ($ids as $match_id) {
			$matches[$match_id] = $this->getMatch($match_id);
		}

		return $matches;
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
		return (int) $this->_event['maxRounds'];
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

		return null;
	}

	/**
	 * Return all participants filtered by pool and round.
	 *
	 * @param int $round
	 * @param int $pool
	 * @return array
	 */
	public function getParticipants($round, $pool = null) {
		if ($pool) {
			$ids = $this->_pools[$pool][$round];
		} else {
			$ids = $this->_rounds[$round];
		}

		$participants = array();

		foreach (array_keys($ids) as $participant_id) {
			$participants[$participant_id] = $this->getParticipant($participant_id);
		}

		return $participants;
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
	 * Return all rounds. The output will change depending on the round type.
	 *
	 * @param int $pool_id
	 * @return array
	 */
	public function getRounds($pool_id = null) {
		if ($pool_id) {
			return array_keys($this->_pools[$pool_id]);
		}

		return array_keys($this->_rounds);
	}

	/**
	 * Return the participants standing in the bracket.
	 * If it's a bracket tree, only show the standing during a specific round.
	 *
	 * @param int $participant_id
	 * @param int $round
	 * @param int $pool
	 * @return int
	 */
	public function getStanding($participant_id, $round = null, $pool = null) {
		$standing = null;

		if ($pool) {
			$standings = $this->_standings[$pool][$round];
		} else {
			$standings = $this->_standings;
		}

		foreach ($standings as $i => $participants) {
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
	 * Is the event an elimination event?
	 *
	 * @return bool
	 */
	public function isElimination() {
		return ($this->isSingleElimination() || $this->isDoubleElimination());
	}

	/**
	 * Is the event a single elimination event?
	 *
	 * @return bool
	 */
	public function isSingleElimination() {
		return ($this->_event['type'] == Event::SINGLE_ELIM);
	}

	/**
	 * Is the event a double elimination event?
	 *
	 * @return bool
	 */
	public function isDoubleElimination() {
		return ($this->_event['type'] == Event::DOUBLE_ELIM);
	}

	/**
	 * Is the event a round robin event?
	 *
	 * @return bool
	 */
	public function isRoundRobin() {
		return ($this->_event['type'] == Event::ROUND_ROBIN);
	}

	/**
	 * Is the event a swiss event?
	 *
	 * @return bool
	 */
	public function isSwiss() {
		return ($this->_event['type'] == Event::SWISS);
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
		foreach ($matches as $match) {
			$this->_matches[$match['Match']['id']] = $match;
		}

		return $this;
	}

	/**
	 * Set a mapping of participants data (either Team or Player) indexed by ID.
	 *
	 * @param array $participants
	 * @return Bracket
	 */
	public function setParticipants(array $participants) {
		foreach ($participants as $participant) {
			$id = empty($participant['EventParticipant']['team_id'])
				? $participant['EventParticipant']['player_id']
				: $participant['EventParticipant']['team_id'];

			$this->_participants[$id] = $participant;
		}

		return $this;
	}

	/**
	 * Set an array of pool ID to match IDs. The index should be the pool ID and
	 * the value should be an array of participant IDs in correct order.
	 * Each participant should have an array of match IDs.
	 *
	 * @param array $pools
	 * @param bool $calculate
	 * @return Bracket
	 * @throws Exception
	 */
	public function setPools(array $pools, $calculate = true) {
		$this->_pools = $pools;

		if (empty($this->_matches)) {
			throw new Exception('Matches must be set before pools');
		}

		if (!$calculate) {
			return $this;
		}

		// Cache and tally the current scores
		foreach ($pools as $pool_id => $rounds) {
			foreach ($rounds as $round_id => $participants) {
				foreach ($participants as $match_ids) {
					$this->_scores[$pool_id][$round_id] = $this->calculateScores($match_ids);
					$this->_standings[$pool_id][$round_id] = $this->calculateStandings($match_ids);
				}

			}
		}

		return $this;
	}

	/**
	 * Set an array of round ID to match IDs. The index should be the round ID and
	 * the value should be an array of match IDs in correct order.
	 *
	 * @param array $rounds
	 * @param bool $calculate
	 * @return Bracket
	 * @throws Exception
	 */
	public function setRounds(array $rounds, $calculate = true) {
		$this->_rounds = $rounds;

		if (empty($this->_matches)) {
			throw new Exception('Matches must be set before pools');
		}

		if (!$calculate) {
			return $this;
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