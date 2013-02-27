<?php

App::uses('AppShell', 'Console/Command');
App::uses('Tournament', 'Tournament.Lib');

Configure::write('debug', 2);

/**
 * @property User $User
 * @property Player $Player
 * @property Team $Team
 * @property TeamMember $TeamMember
 * @property Division $Division
 * @property Game $Game
 * @property League $League
 * @property Event $Event
 * @property EventParticipant $EventParticipant
 * @property Match $Match
 */
class GenerateDataShell extends AppShell {

	/**
	 * Models.
	 *
	 * @var array
	 */
	public $uses = array(
		TOURNAMENT_USER,
		'Tournament.Player',
		'Tournament.Team',
		'Tournament.TeamMember',
		'Tournament.Division',
		'Tournament.Game',
		'Tournament.League',
		'Tournament.Event',
		'Tournament.EventParticipant',
		'Tournament.Match'
	);

	/**
	 * User and player IDs.
	 *
	 * @var array
	 */
	public $users = array();

	/**
	 * Team and leader IDs.
	 *
	 * @var array
	 */
	public $teams = array();

	/**
	 * Game data.
	 *
	 * @var array
	 */
	public $games = array();

	/**
	 * Event IDs.
	 *
	 * @var array
	 */
	public $events = array();

	/**
	 * Generate fake test data.
	 */
	public function main() {
		$this->out($this->OptionParser->help());
	}

	/**
	 * Generate data in all tables.
	 */
	public function generate() {
		$this->generateUsers();
		$this->generatePlayers();
		$this->generateTeams();
		$this->generateDivisions();
		$this->generateGames();
		$this->generateLeagues();
		$this->generateEvents();
		$this->generateMatches();
	}

	/**
	 * Generate CSS to use for bracket positioning.
	 */
	public function css() {
		$boxMargin = $this->params['boxMargin'];
		$boxInnerHeight = $this->params['boxHeight'];
		$boxOuterHeight = ($boxInnerHeight + $boxMargin);
		$boxHalfHeight = round($boxOuterHeight / 2);
		$lineBorderWidth = $this->params['lineWidth'];
		$maxRounds = 10;
		$multiplier = 0;

		for ($i = 2; $i <= $maxRounds; $i++) {
			$multiplier = ($multiplier * 2) + 1;

			$topMargin = ($multiplier * $boxOuterHeight) + $boxMargin;
			$topMarginFirst = ($multiplier * $boxHalfHeight);

			$lineHeight = floor(($multiplier + 1) * $boxOuterHeight / 2) - $lineBorderWidth;
			$lineTop = round($lineHeight / 2) - $boxHalfHeight + ($lineBorderWidth * 2);

			$this->out(sprintf('.round-%s li { margin-top: %spx; }', $i, $topMargin));
			$this->out(sprintf('.round-%s li:first-child { margin-top: %spx; }', $i, $topMarginFirst));
			$this->out(sprintf('.round-%s li .bracket-line { height: %spx; top: -%spx; }', $i, $lineHeight, $lineTop));
		}
	}

	/**
	 * Generate 250 users.
	 */
	public function generateUsers() {
		$this->out('Generating users');
		$this->User->deleteAll(array(
			'User.' . Configure::read('Tournament.userMap.username') . ' LIKE' => 'User #%'
		));

		$userMap = Configure::read('Tournament.userMap');
		$statusMap = Configure::read('Tournament.statusMap');

		for ($i = 0; $i < 250; $i++) {
			$this->User->create();
			$this->User->save(array(
				$userMap['username'] => 'User #' . ($i + 1),
				$userMap['status'] => $statusMap['active']
			));

			$this->users[] = array(
				'id' => $this->User->id
			);
		}
	}

	/**
	 * Generate players for each user.
	 */
	public function generatePlayers() {
		$this->out('Generating players');
		$this->Player->getDataSource()->truncate($this->Player->tablePrefix . $this->Player->useTable);

		foreach ($this->users as $i => $user) {
			$this->Player->create();
			$this->Player->save(array(
				'user_id' => $user['id'],
				'wins' => rand(0, 500),
				'losses' => rand(0, 500),
				'ties' => rand(0, 500),
				'points' => rand(0, 5000)
			));

			$this->users[$i]['player_id'] = $this->Player->id;
		}
	}

	/**
	 * Generate 25 teams with 10 members per team.
	 */
	public function generateTeams() {
		$this->out('Generating teams and members');
		$this->Team->getDataSource()->truncate($this->Team->tablePrefix . $this->Team->useTable);
		$this->TeamMember->getDataSource()->truncate($this->TeamMember->tablePrefix . $this->TeamMember->useTable);

		$userIndex = 0;

		for ($i = 0; $i < 25; $i++) {
			$leader_id = $this->users[$userIndex]['id'];

			$this->Team->create();
			$this->Team->save(array(
				'name' => 'Team #' . ($i + 1),
				'status' => Team::ACTIVE,
				'wins' => rand(0, 100),
				'losses' => rand(0, 100),
				'ties' => rand(0, 100),
				'points' => rand(0, 1000),
				'user_id' => $leader_id
			));

			$this->teams[] = array(
				'id' => $this->Team->id,
				'leader_id' => $leader_id
			);

			// Create 10 members per team
			for ($m = 0; $m < 10; $m++) {
				$this->TeamMember->create();
				$this->TeamMember->save(array(
					'team_id' => $this->Team->id,
					'player_id' => $this->users[$userIndex]['player_id'],
					'user_id' => $this->users[$userIndex]['id'],
					'role' => ($m == 0) ? TeamMember::LEADER : rand(1, 4),
					'status' => ($m == 0) ? TeamMember::ACTIVE : rand(1, 3)
				));

				$userIndex++;
			}
		}
	}

	/**
	 * Generate 3 divisions.
	 */
	public function generateDivisions() {
		$this->out('Generating divisions');
		$this->Division->getDataSource()->truncate($this->Division->tablePrefix . $this->Division->useTable);

		foreach (array('Open', 'Pro', 'Invite') as $div) {
			$this->Division->create();
			$this->Division->save(array('name' => $div));
		}
	}

	/**
	 * Generate 3 games.
	 */
	public function generateGames() {
		$this->out('Generating games');
		$this->Game->getDataSource()->truncate($this->Game->tablePrefix . $this->Game->useTable);

		$this->games = array(
			array('name' => 'Team Fortress 2', 'slug' => 'tf2'),
			array('name' => 'Starcraft 2', 'slug' => 'sc2'),
			array('name' => 'League of Legends', 'slug' => 'lol')
		);

		foreach ($this->games as $i => $game) {
			$this->Game->create();
			$this->Game->save(array('name' => $game['name']));

			$this->games[$i]['id'] = $this->Game->id;
		}
	}

	/**
	 * Generate 2 leagues for each game.
	 */
	public function generateLeagues() {
		$this->out('Generating leagues');
		$this->League->getDataSource()->truncate($this->League->tablePrefix . $this->League->useTable);

		foreach ($this->games as $game) {
			foreach (array('West', 'East') as $conf) {
				$this->League->create();
				$this->League->save(array(
					'game_id' => $game['id'],
					'region_id' => $game['id'],
					'name' =>  $conf,
					'slug' => $game['slug'] . '-' . strtolower($conf)
				), array(
					'callbacks' => false
				));
			}
		}
	}

	/**
	 * Generate 10 events.
	 */
	public function generateEvents() {
		$this->out('Generating events and participants');
		$this->Event->getDataSource()->truncate($this->Event->tablePrefix . $this->Event->useTable);
		$this->EventParticipant->getDataSource()->truncate($this->EventParticipant->tablePrefix . $this->EventParticipant->useTable);

		$settings = Configure::read('Tournament.settings');

		for ($i = 0; $i < 10; $i++) {
			$excludeUsers = array();
			$excludeTeams = array();
			$type = rand(0, 3);
			$for = rand(0, 1);
			$pools = null;
			$rounds = null;

			if ($type == Event::SINGLE_ELIM || $type == Event::DOUBLE_ELIM) {
				$sizes = array(8, 16, 24, 32);
				$max = $sizes[rand(0, 3)];

			} else if ($type == Event::ROUND_ROBIN) {
				$sizes = array(0, 5, 10, 15);
				$r = rand(0, 3);
				$pools = $sizes[$r];
				$max = ($pools * $r) + 10;
				$rounds = rand(1, 3);

			} else {
				$max = rand(8, 20);
				$rounds = rand(0, 3);
			}

			if ($for == Event::TEAM) {
				$max = round($max / 2);
			}

			$this->Event->create();
			$this->Event->save(array(
				'game_id' => rand(1, 3),
				'league_id' => rand(1, 6),
				'division_id' => rand(1, 3),
				'type' => $type,
				'for' => $for,
				'seed' => rand(0, 1),
				'name' => 'Event #' . ($i + 1),
				'maxParticipants' => $max,
				'maxRounds' => $rounds,
				'poolSize' => $pools,
				'start' => date('Y-m-d H:i:s', strtotime('+1 week')),
				'end' => date('Y-m-d H:i:s', strtotime('+5 weeks')),
				'signupStart' => date('Y-m-d H:i:s', strtotime('-4 weeks')),
				'signupEnd' => date('Y-m-d H:i:s'),
				'pointsForWin' => $settings['defaultWinPoints'],
				'pointsForLoss' => $settings['defaultLossPoints'],
				'pointsForTie' => $settings['defaultTiePoints'],
			));

			$this->events[] = array(
				'id' => $this->Event->id
			);

			// Create $max participants per event
			for ($p = 0; $p < $max; $p++) {
				$query = array(
					'event_id' => $this->Event->id,
					'status' => EventParticipant::ACTIVE,
					'isReady' => EventParticipant::YES
				);

				if ($for == Event::TEAM) {
					$query['team_id'] = $this->teams[$this->getRandomTeam($excludeTeams)]['id'];
				} else {
					$query['player_id'] = $this->users[$this->getRandomUser($excludeUsers)]['player_id'];
				}

				$this->EventParticipant->create();
				$this->EventParticipant->save($query);
			}
		}
	}

	/**
	 * Generate matches for each event.
	 */
	public function generateMatches() {
		$this->out('Generating matches');
		$this->Match->getDataSource()->truncate($this->Match->tablePrefix . $this->Match->useTable);

		$events = $this->Event->find('list', array(
			'fields' => array('Event.id')
		));

		foreach ($events as $event_id) {
			try {
				Tournament::factory($event_id)->generateMatches();
			} catch (Exception $e) {
				$this->out(sprintf('Event #%s - %s', $event_id, $e->getMessage()));
			}
		}
	}

	/**
	 * Get a random team index from the list of teams while excluding duplicates.
	 *
	 * @param array $exclude
	 * @return int
	 */
	public function getRandomTeam(array &$exclude) {
		$count = count($this->teams) - 1;
		$id = rand(0, $count);

		while (isset($exclude[$id])) {
			$id = rand(0, $count);
		}

		$exclude[$id] = $id;

		return $id;
	}

	/**
	 * Get a random user index from the list of users while excluding duplicates.
	 *
	 * @param array $exclude
	 * @return int
	 */
	public function getRandomUser(array &$exclude) {
		$count = count($this->users) - 1;
		$id = rand(0, $count);

		while (isset($exclude[$id])) {
			$id = rand(0, $count);
		}

		$exclude[$id] = $id;

		return $id;
	}

	/**
	 * Loop through pending matches and declare a winner and award points.
	 */
	public function advance() {
		$event_id = $this->args[0];
		$event = $this->Event->getById($event_id);
		$settings = Configure::read('Tournament.settings');

		if (!$event) {
			$this->err('Invalid event');
			return;
		}

		// Update matches with fake data
		$this->out('Advancing current round matches');

		if ($matches = $this->Match->getPendingMatches($event_id)) {
			foreach ($matches as $match) {
				$home_id = $match['Match']['home_id'];
				$away_id = $match['Match']['away_id'];

				if ($event['Event']['type'] == Event::SINGLE_ELIM || $event['Event']['type'] == Event::DOUBLE_ELIM) {
					$winner = rand(1, 2);
				} else {
					$winner = rand(1, 3);
				}

				$query = array(
					'winner' => $winner
				);

				if ($winner == Match::HOME) {
					$query['homeOutcome'] = Match::WIN;
					$query['awayOutcome'] = Match::LOSS;
					$query['homeScore'] = $settings['defaultWinPoints'];
					$query['awayScore'] = $settings['defaultLossPoints'];

					$this->EventParticipant->updateStatistics($event_id, $home_id, array('wins' => 1, 'points' => $query['homeScore']));
					$this->EventParticipant->updateStatistics($event_id, $away_id, array('losses' => 1, 'points' => $query['awayScore']));

				} else if ($winner == Match::AWAY) {
					$query['homeOutcome'] = Match::LOSS;
					$query['awayOutcome'] = Match::WIN;
					$query['homeScore'] = $settings['defaultLossPoints'];
					$query['awayScore'] = $settings['defaultWinPoints'];

					$this->EventParticipant->updateStatistics($event_id, $home_id, array('losses' => 1, 'points' => $query['homeScore']));
					$this->EventParticipant->updateStatistics($event_id, $away_id, array('wins' => 1, 'points' => $query['awayScore']));

				} else {
					$query['homeOutcome'] = Match::TIE;
					$query['awayOutcome'] = Match::TIE;
					$query['homeScore'] = $settings['defaultTiePoints'];
					$query['awayScore'] = $settings['defaultTiePoints'];

					$this->EventParticipant->updateStatistics($event_id, $home_id, array('ties' => 1, 'points' => $query['homeScore']));
					$this->EventParticipant->updateStatistics($event_id, $away_id, array('ties' => 1, 'points' => $query['awayScore']));
				}

				$this->Match->id = $match['Match']['id'];
				$this->Match->save($query, false);
			}
		}

		// Generate the next round
		$this->out('Generating next round matches');

		try {
			Tournament::factory($event)->generateMatches();
		} catch (Exception $e) {
			$this->out($e->getMessage());
		}
	}

	/**
	 * Add sub-commands.
	 *
	 * @return ConsoleOptionParser
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubcommand('generate', array(
			'help' => 'Generate fake data',
			'parser' => array(
				'description' => 'This command will truncate all tables and generate new test data.'
			)
		));

		$parser->addSubcommand('advance', array(
			'help' => 'Advance an event round',
			'parser' => array(
				'description' => 'This command will advance the current event round to the next round by flagging current matches as a win, loss or tie.',
				'arguments' => array(
					'event_id' => array('help' => 'Event to advance', 'required' => true)
				)
			)
		));

		$parser->addSubcommand('css', array(
			'help' => 'Generate bracket CSS',
			'parser' => array(
				'description' => 'This command will generate the correct CSS for the bracket tree, lines and grid.',
				'options' => array(
					'boxMargin' => array(
						'short' => 'm',
						'help' => 'Box margin bottom',
						'default' => 7
					),
					'boxHeight' => array(
						'short' => 'h',
						'help' => 'Box height excluding margin',
						'default' => 83
					),
					'lineWidth' => array(
						'short' => 'l',
						'help' => 'Box connecting line width',
						'default' => 3
					)
				)
			)
		));

		return $parser;
	}

}