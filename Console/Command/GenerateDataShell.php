<?php

App::uses('AppShell', 'Console/Command');

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
		'Tournament.EventParticipant'
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
		$this->cleanup();
		$this->generateUsers();
		$this->generatePlayers();
		$this->generateTeams();
		$this->generateDivisions();
		$this->generateGames();
		$this->generateLeagues();
		$this->generateEvents();
	}

	/**
	 * Truncate tables before testing.
	 */
	public function cleanup() {
		$this->out('Truncating tables');

		$this->User->deleteAll(array(
			'User.' . Configure::read('Tournament.userMap.username') . ' LIKE' => 'User #%'
		));

		$models = array(
			$this->Player, $this->Team, $this->TeamMember, $this->Division,
			$this->Game, $this->League, $this->Event, $this->EventParticipant
		);

		foreach ($models as $model) {
			$model->getDataSource()->truncate($model->tablePrefix . $model->useTable);
		}
	}

	/**
	 * Generate 250 users.
	 */
	public function generateUsers() {
		$this->out('Generating users');

		$userMap = Configure::read('Tournament.userMap');
		$statusMap = Configure::read('Tournament.statusMap');

		for ($i = 0; $i < 250; $i++) {
			$this->User->create();
			$this->User->save(array(
				$userMap['username'] => 'User #' . $i,
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

		$userIndex = 0;

		for ($i = 0; $i < 25; $i++) {
			$leader_id = $this->users[$userIndex]['id'];

			$this->Team->create();
			$this->Team->save(array(
				'name' => 'Team #' . $i,
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

		for ($i = 0; $i < 10; $i++) {
			$type = rand(0, 3);
			$for = rand(0, 1);
			$pool = null;

			if ($type == Event::SINGLE_ELIM || $type == Event::DOUBLE_ELIM) {
				$max = 32;
			} else if ($type == Event::ROUND_ROBIN) {
				$max = 50;
			} else {
				$max = 16;
				$pool = 10;
			}

			if ($for == Event::TEAM) {
				$max = $max / 2;
			}

			$this->Event->create();
			$this->Event->save(array(
				'game_id' => rand(1, 3),
				'league_id' => rand(1, 6),
				'division_id' => rand(1, 3),
				'type' => $type,
				'for' => $for,
				'seed' => rand(0, 1),
				'name' => 'Event #' . $i,
				'maxParticipants' => $max,
				'poolSize' => $pool,
				'start' => date('Y-m-d H:i:s', strtotime('+1 week')),
				'end' => date('Y-m-d H:i:s', strtotime('+5 weeks')),
				'signupStart' => date('Y-m-d H:i:s', strtotime('-4 weeks')),
				'signupEnd' => date('Y-m-d H:i:s')
			));

			$this->events[] = array(
				'id' => $this->Event->id
			);

			// Create $max participants per event
			for ($p = 0; $p < $max; $p++) {
				$query = array(
					'event_id' => $this->Event->id,
					'status' => EventParticipant::ACTIVE,
					'isReady' => rand(0, 1)
				);

				if ($for == Event::TEAM) {
					$query['team_id'] = $this->teams[$p]['id'];
				} else {
					$query['player_id'] = $this->users[rand(0, count($this->users) - 1)]['player_id'];
				}

				$this->EventParticipant->create();
				$this->EventParticipant->save($query);
			}
		}
	}

}