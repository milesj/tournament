<?php

App::uses('Event', 'Tournament.Model');

class Bracket {

	const FINALS = 0;
	const SEMI_FINALS = 1;
	const QUARTER_FINALS = 2;

	/**
	 * Event information.
	 *
	 * @type array
	 */
	protected $_event;

	/**
	 * List of matches.
	 *
	 * @type array
	 */
	protected $_matches = array();

	/**
	 * List of participants.
	 *
	 * @type array
	 */
	protected $_participants = array();

	/**
	 * Mapping of pools.
	 *
	 * @type array
	 */
	protected $_pools = array();

	/**
	 * Mapping of rounds.
	 *
	 * @type array
	 */
	protected $_rounds = array();

	/**
	 * Store the event.
	 *
	 * @param array $event
	 */
	public function __construct($event) {
		$this->_event = $event;
	}

	/**
	 * Calculate how many matches should be present for this round.
	 * Only applies to elimination games.
	 *
	 * @param $round
	 * @return int
	 */
	public function calculateRoundMatches($round) {
		$max = $this->_event['startingMatches'];
		$round--;

		while ($round > 0) {
			$max = ceil($max / 2);
			$round--;
		}

		return $max;
	}

	/**
	 * Verify that the standing can be shown.
	 *
	 * @param int $round
	 * @return bool
	 */
	public function canShowStanding($round) {
		if ($this->isElimination()) {
			return $this->isRound($round, self::FINALS);
		}

		return $this->isRoundRobin();
	}

	/**
	 * Return the count of how many rounds have been completed.
	 *
	 * @return int
	 */
	public function getCompletedRounds() {
		return (int) $this->_event['round'];
	}

	/**
	 * Return a single match by ID.
	 *
	 * @param int $id
	 * @return array
	 * @throws Exception
	 */
	public function getMatch($id) {
		return isset($this->_matches[$id]) ? $this->_matches[$id] : null;
	}

	/**
	 * Return all matches filtered by round, pool or participant.
	 *
	 * @param int $round
	 * @param int $pool
	 * @param int $participant_id
	 * @return array
	 */
	public function getMatches($round, $pool = null, $participant_id = null) {
		if ($pool) {
			$ids = $this->_pools[$pool][$round];

		} else if (empty($this->_rounds[$round])) {
			return array();

		} else {
			$ids = $this->_rounds[$round];
		}

		// Filter down again to participant
		if ($participant_id) {
			$ids = $ids[$participant_id];
		}

		$matches = array();

		foreach ($ids as $match_id) {
			$match = $this->getMatch($match_id);
			$matches[$match['Match']['order']] = $match;
		}

		return $matches;
	}

	/**
	 * Return the max amount of participants that will play.
	 *
	 * @return int
	 */
	public function getMaxParticipants() {
		return (int) $this->_event['maxParticipants'];
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
		return isset($this->_participants[$id]) ? $this->_participants[$id] : null;
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
			$id = $participant['EventParticipant']['player_id'] ?: $participant['EventParticipant']['team_id'];

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
	 * @return Bracket
	 * @throws Exception
	 */
	public function setPools(array $pools) {
		$this->_pools = $pools;

		if (empty($this->_matches)) {
			throw new Exception('Matches must be set before pools');
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
			throw new Exception('Matches must be set before rounds');
		}

		return $this;
	}

}