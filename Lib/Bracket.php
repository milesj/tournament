<?php

App::uses('Event', 'Tournament.Model');

class Bracket {

	/**
	 * Type of bracket.
	 *
	 * @var int
	 */
	protected $_type;

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
	 * Store the event bracket type.
	 *
	 * @param int $type
	 */
	public function __construct($type) {
		$this->_type = $type;
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
		if (empty($this->_pools[$pool_id][$participant_id])) {
			throw new Exception(sprintf('Invalid participant %s in pool %s', $participant_id, $pool_id));
		}

		$matches = array();
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
		if (empty($this->_standings[$pool_id])) {
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
		if ($this->_type == Event::ROUND_ROBIN) {
			return count($this->_participants);
		}

		return count($this->_rounds);
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
			$scores = array();

			foreach ($participants as $participant_id => $matches) {
				foreach ($matches as $match_id) {
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
			}

			$this->_scores[$pool_id] = $scores;

			// Now generate the standings
			$standings = array();

			foreach ($scores as $participant_id => $score) {
				if ($score <= 0) {
					continue;
				} else if (isset($standings[$score])) {
					$standings[$score][] = $participant_id;
				} else {
					$standings[$score] = array($participant_id);
				}
			}

			ksort($standings);
			$standings = array_values(array_reverse($standings, true));

			$this->_standings[$pool_id] = $standings;
		}

		return $this;
	}

	/**
	 * Set an array of round ID to match IDs. The index should be the round ID and
	 * the value should be an array of match IDs in correct order.
	 *
	 * @param array $rounds
	 * @return Bracket
	 */
	public function setRounds(array $rounds) {
		$this->_rounds = $rounds;

		return $this;
	}

}