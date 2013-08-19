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
	 * @type array
	 */
	public $uses = array(
		USER_MODEL,
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
	 * @type array
	 */
	public $users = array();

	/**
	 * Team and leader IDs.
	 *
	 * @type array
	 */
	public $teams = array();

	/**
	 * Game data.
	 *
	 * @type array
	 */
	public $games = array();

	/**
	 * Event IDs.
	 *
	 * @type array
	 */
	public $events = array();

	/**
	 * Generate fake test data.
	 */
	public function main() {
		$this->out($this->OptionParser->help());
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
		$boxSizing = $this->params['boxSizing'];
		$maxRounds = 10;
		$multiplier = 0;

		$this->out('Margin: ' . $boxMargin);
		$this->out('Inner Height: ' . $boxInnerHeight);
		$this->out('Outer Height (with margin): ' . $boxOuterHeight);
		$this->out('Half Outer Height (with margin): ' . $boxHalfHeight);
		$this->out('Line Width: ' . $lineBorderWidth);
		$this->out('CSS Box Sizing: ' . $boxSizing);
		$this->out();

		for ($i = 2; $i <= $maxRounds; $i++) {
			$multiplier = ($multiplier * 2) + 1;

			// The gap between each match is dependent on the round multiplier
			// Each gap is equal to: round multiplier * match height + extra margin
			$topMargin = ceil($multiplier * $boxOuterHeight) + $boxMargin;
			$topMarginFirst = ceil($multiplier * $boxHalfHeight);

			// Generate the sizes for the bracket arrow lines
			$lineHeight = floor(($multiplier + 1) * $boxOuterHeight / 2) - $lineBorderWidth;
			$lineTop = round($lineHeight / 2) - $boxHalfHeight + ($lineBorderWidth * 2);

			// If CSS box-sizing is border-box, add more height
			if ($boxSizing === 'border-box') {
				$lineHeight += ($lineBorderWidth * 2);
				$lineTop += $lineBorderWidth;

				if ($lineBorderWidth % 2 !== 0) {
					$lineTop += 1;
				}
			}

			$this->out(sprintf('.round-%s li { margin-top: %spx; }', $i, $topMargin));
			$this->out(sprintf('.round-%s li:first-child { margin-top: %spx; }', $i, $topMarginFirst));
			$this->out(sprintf('.round-%s li .bracket-line { height: %spx; top: -%spx; }', $i, $lineHeight, $lineTop));
		}
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
	 * Generate 250 users.
	 */
	public function generateUsers() {
		$this->out();
		$this->out('Generating users');

		if ($this->params['filter'] === 'all') {
			$this->User->deleteAll(array(
				'User.' . Configure::read('User.fieldMap.username') . ' LIKE' => 'User #%'
			));

			$userMap = Configure::read('User.fieldMap');
			$statusMap = Configure::read('User.statusMap');

			for ($i = 0; $i < 250; $i++) {
				$this->User->create();
				$this->User->save(array(
					$userMap['username'] => 'User #' . ($i + 1),
					$userMap['status'] => $statusMap['active'],
					$userMap['email'] => 'email' . ($i + 1) . '@tournament.com'
				));

				$this->users[] = array('id' => $this->User->id);

				$this->out('-', 0);
			}

		} else {
	        $users = $this->User->find('all');

			foreach ($users as $user) {
				$this->users[] = array('id' => $user['User']['id']);

				$this->out('-', 0);
			}
		}
	}

	/**
	 * Generate players for each user.
	 */
	public function generatePlayers() {
		$this->out();
		$this->out('Generating players');

		if ($this->params['filter'] === 'all') {
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

				$this->out('-', 0);
			}

		} else {
			foreach ($this->users as $i => $user) {
				$player = $this->Player->getPlayer($user['id'], array());

				$this->users[$i]['player_id'] = $player['Player']['id'];

				$this->out('-', 0);
			}
		}
	}

	/**
	 * Generate 25 teams with 10 members per team.
	 */
	public function generateTeams() {
		$this->out();
		$this->out('Generating teams and members');

		if ($this->params['filter'] === 'all') {
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

				$this->out('-', 0);

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

					$this->out('+', 0);
				}

				$this->out();
			}

		} else {
			$teams = $this->Team->find('all');

			foreach ($teams as $team) {
				$this->teams[] = array(
					'id' => $team['Team']['id'],
					'leader_id' => $team['Team']['user_id']
				);

				$this->out('-', 0);
			}
		}
	}

	/**
	 * Generate 3 divisions.
	 */
	public function generateDivisions() {
		$this->out();
		$this->out('Generating divisions');

		if ($this->params['filter'] === 'all') {
			$this->Division->getDataSource()->truncate($this->Division->tablePrefix . $this->Division->useTable);

			foreach (array('Open', 'Pro', 'Invite') as $div) {
				$this->Division->create();
				$this->Division->save(array('name' => $div));

				$this->out('-', 0);
			}
		} else {
			$this->out('---', 0);
		}
	}

	/**
	 * Generate 3 games.
	 */
	public function generateGames() {
		$this->out();
		$this->out('Generating games');

		if ($this->params['filter'] === 'all') {
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

				$this->out('-', 0);
			}

		} else {
			$games = $this->Game->find('all');

			foreach ($games as $game) {
				$this->games[] = $game['Game'];

				$this->out('-', 0);
			}
		}
	}

	/**
	 * Generate 2 leagues for each game.
	 */
	public function generateLeagues() {
		$this->out();
		$this->out('Generating leagues');

		if ($this->params['filter'] === 'all') {
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

					$this->out('-', 0);
				}
			}
		} else {
			$this->out('------', 0);
		}
	}

	/**
	 * Generate 10 events.
	 */
	public function generateEvents() {
		$this->out();
		$this->out('Generating events and participants');

		if ($this->params['filter'] === 'all' || $this->params['filter'] === 'events') {
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
					$max = rand(2, 32);

				} else if ($type == Event::ROUND_ROBIN) {
					$sizes = array(0, 5, 10, 15);
					$r = rand(0, 3);
					$pools = $sizes[$r];
					$max = ($pools * $r) + 10;
					$rounds = rand(1, 3);

				} else {
					$max = rand(8, 20);
					$rounds = rand(1, 8);
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

				$this->out('-', 0);

				// Create $max participants per event
				$this->EventParticipant->cacheQueries = false;

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

					$this->out('+', 0);
				}

				$this->out();
			}

		} else {
			$events = $this->Event->find('all');

			foreach ($events as $event) {
				$this->events[] = $event['Event'];

				$this->Event->id = $event['Event']['id'];
				$this->Event->save(array(
					'round' => null,
					'isGenerated' => Event::NO,
					'isRunning' => Event::NO,
					'isFinished' => Event::NO
				));

				$this->out('-', 0);
			}
		}
	}

	/**
	 * Generate matches for each event.
	 */
	public function generateMatches() {
		$this->out();
		$this->out('Generating matches');

		// Matches should always be regenerated
		$this->Match->getDataSource()->truncate($this->Match->tablePrefix . $this->Match->useTable);

		$events = $this->Event->find('list', array(
			'fields' => array('Event.id')
		));

		foreach ($events as $event_id) {
			try {
				Tournament::factory($event_id)->generateMatches();
				$this->out('-', 0);

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
			$this->out('T', 0);
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
			$this->out('P', 0);
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

		if ($matches = $this->Match->getPendingMatches($event_id, $event['Event']['round'])) {
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
					$query['homePoints'] = $settings['defaultWinPoints'];
					$query['awayPoints'] = $settings['defaultLossPoints'];

					$this->EventParticipant->updateStatistics($event_id, $home_id, array('wins' => 1, 'points' => $query['homePoints']));
					$this->EventParticipant->updateStatistics($event_id, $away_id, array('losses' => 1, 'points' => $query['awayPoints']));

				} else if ($winner == Match::AWAY) {
					$query['homeOutcome'] = Match::LOSS;
					$query['awayOutcome'] = Match::WIN;
					$query['homePoints'] = $settings['defaultLossPoints'];
					$query['awayPoints'] = $settings['defaultWinPoints'];

					$this->EventParticipant->updateStatistics($event_id, $home_id, array('losses' => 1, 'points' => $query['homePoints']));
					$this->EventParticipant->updateStatistics($event_id, $away_id, array('wins' => 1, 'points' => $query['awayPoints']));

				} else {
					$query['homeOutcome'] = Match::TIE;
					$query['awayOutcome'] = Match::TIE;
					$query['homePoints'] = $settings['defaultTiePoints'];
					$query['awayPoints'] = $settings['defaultTiePoints'];

					$this->EventParticipant->updateStatistics($event_id, $home_id, array('ties' => 1, 'points' => $query['homePoints']));
					$this->EventParticipant->updateStatistics($event_id, $away_id, array('ties' => 1, 'points' => $query['awayPoints']));
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
				'description' => 'This command will truncate all tables and generate new test data.',
				'options' => array(
					'filter' => array(
						'short' => 'f',
						'help' => 'Filter what to generate for',
						'choices' => array('all', 'events', 'matches'),
						'default' => 'all'
					)
				)
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
						'help' => 'Box margin bottom (must be an odd number)',
						'default' => 15
					),
					'boxHeight' => array(
						'short' => 'h',
						'help' => 'Box height excluding margin',
						'default' => 91
					),
					'lineWidth' => array(
						'short' => 'l',
						'help' => 'Box connecting line width',
						'default' => 3
					),
					'boxSizing' => array(
						'short' => 's',
						'help' => 'CSS box sizing property',
						'default' => 'border-box'
					)
				)
			)
		));

		return $parser;
	}

}