<?php

App::uses('TournamentAppModel', 'Tournament.Model');

class TeamMember extends TournamentAppModel {

	// Roles
	const LEADER = 0;
	const CO_LEADER = 1;
	const MANAGER = 2;
	const MEMBER = 3;
	const SUB = 4;

	// Status
	const REMOVED = 2; // Removed by leader
	const QUIT = 3; // Left team personally
	const DISBANDED = 4;

	/**
	 * Belongs to.
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Team' => array(
			'className' => 'Tournament.Team',
			'counterCache' => true
		),
		'Player' => array(
			'className' => 'Tournament.Player'
		),
		'User' => array(
			'className' => TOURNAMENT_USER
		)
	);

	/**
	 * Enum mappings.
	 *
	 * @var array
	 */
	public $enum = array(
		'role' => array(
			self::LEADER => 'LEADER',
			self::CO_LEADER => 'CO_LEADER',
			self::MANAGER => 'MANAGER',
			self::MEMBER => 'MEMBER',
			self::SUB => 'SUB'
		),
		'status' => array(
			self::PENDING => 'PENDING',
			self::ACTIVE => 'ACTIVE',
			self::REMOVED => 'REMOVED',
			self::QUIT => 'QUIT',
			self::DISBANDED => 'DISBANDED'
		)
	);

	/**
	 * Demote a member.
	 *
	 * @param int $id
	 * @param int $role
	 * @return mixed
	 */
	public function demote($id, $role) {
		$this->id = $id;

		return $this->save(array(
			'role' => $role,
			'promotedOn' => null
		), false);
	}

	/**
	 * Return a member based on user ID.
	 *
	 * @param int $team_id
	 * @param int $user_id
	 * @param bool $nonEx
	 * @return bool
	 */
	public function getByUserId($team_id, $user_id, $nonEx = false) {
		$conditions = array(
			'TeamMember.team_id' => $team_id,
			'TeamMember.user_id' => $user_id
		);

		if ($nonEx) {
			$conditions['TeamMember.status'] = array(self::PENDING, self::ACTIVE);
		}

		return $this->find('first', array(
			'conditions' => $conditions
		));
	}

	/**
	 * Return all members of a team.
	 *
	 * @param int $team_id
	 * @return array
	 */
	public function getRoster($team_id) {
		return $this->find('all', array(
			'conditions' => array(
				'TeamMember.team_id' => $team_id
			),
			'contain' => array('Player', 'User'),
			'order' => array(
				'TeamMember.role' => 'ASC',
				'TeamMember.created' => 'ASC'
			),
			'cache' => array(__METHOD__, $team_id)
		));
	}

	/**
	 * Return a list of members based on role.
	 *
	 * @param int $team_id
	 * @param int|array $role
	 * @return array
	 */
	public function getListByRole($team_id, $role) {
		$list = array();
		$results = $this->find('all', array(
			'conditions' => array(
				'TeamMember.team_id' => $team_id,
				'TeamMember.status' => self::ACTIVE,
				'TeamMember.role' => $role
			),
			'contain' => array('User')
		));

		if ($results) {
			foreach ($results as $result) {
				$list[$result['User']['id']] = sprintf('%s - %s',
					__d('tournament', 'team.role.' . strtolower($result['TeamMember']['role_enum'])),
					$result['User'][Configure::read('Tournament.userMap.username')]);
			}
		}

		return $list;
	}

	/**
	 * Join a team.
	 *
	 * @param int $team_id
	 * @param int $player_id
	 * @param int $user_id
	 * @param int $role
	 * @param int $status
	 * @return mixed
	 */
	public function join($team_id, $player_id, $user_id, $role = self::MEMBER, $status = self::PENDING) {
		if ($member = $this->isMember($team_id, $player_id)) {
			// Update status in case they are QUIT or REMOVED
			return $this->updateStatus($member['TeamMember']['id'], $status);
		}

		$this->create();

		$data = array(
			'team_id' => $team_id,
			'player_id' => $player_id,
			'user_id' => $user_id,
			'role' => $role,
			'status' => $status
		);

		if ($role != self::MEMBER && $role != self::SUB) {
			$data['promotedOn'] = date('Y-m-d H:i:s');
		}

		return $this->save($data, false);
	}

	/**
	 * Check if a user is an active or pending member of a team.
	 *
	 * @param int $user_id
	 * @return array
	 */
	public function inTeam($user_id) {
		return $this->find('count', array(
			'conditions' => array(
				'TeamMember.user_id' => $user_id,
				'TeamMember.status' => array(self::PENDING, self::ACTIVE)
			)
		));
	}

	/**
	 * Check if a player is already a member of a team.
	 *
	 * @param int $team_id
	 * @param int $player_id
	 * @return bool
	 */
	public function isMember($team_id, $player_id) {
		return $this->find('first', array(
			'conditions' => array(
				'TeamMember.team_id' => $team_id,
				'TeamMember.player_id' => $player_id
			)
		));
	}

	/**
	 * Promote a member.
	 *
	 * @param int $id
	 * @param int $role
	 * @return mixed
	 */
	public function promote($id, $role) {
		$this->id = $id;

		return $this->save(array(
			'role' => $role,
			'promotedOn' => date('Y-m-d H:i:s')
		), false);
	}

}