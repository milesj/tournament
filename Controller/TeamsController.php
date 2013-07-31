<?php

App::uses('TournamentAppController', 'Tournament.Controller');
App::uses('Team', 'Tournament.Model');

/**
 * @property Team $Team
 * @property TeamMember $TeamMember
 * @property Player $Player
 */
class TeamsController extends TournamentAppController {

	/**
	 * Models.
	 *
	 * @type array
	 */
	public $uses = array('Tournament.Team', 'Tournament.TeamMember', 'Tournament.Player');

	/**
	 * Pagination.
	 *
	 * @type array
	 */
	public $paginate = array(
		'Team' => array(
			'contain' => array('Leader'),
			'limit' => 25
		)
	);

	/**
	 * Paginate all the teams.
	 */
	public function index() {
		$this->set('teams', $this->paginate('Team', array(
			'Team.status' => Team::ACTIVE
		)));
	}

	/**
	 * Create a team.
	 */
	public function create() {
		$autoApprove = $this->settings['autoApproveTeams'];
		$user_id = $this->Auth->user('id');

		// Must not be part of a team
		if ($this->TeamMember->inTeam($user_id)) {
			$this->Session->setFlash(__d('tournament', 'You may not create a team as you are currently part of a team or have a pending join request'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'index'));
		}

		if ($this->request->is('post')) {
			$this->Team->create();

			$this->request->data['Team']['user_id'] = $user_id;

			if ($autoApprove) {
				$this->request->data['Team']['status'] = Team::ACTIVE;
			}

			if ($this->Team->save($this->request->data, true, array('name', 'password', 'slug', 'description', 'user_id', 'status'))) {
				$player = $this->Player->getPlayer($user_id);

				$this->TeamMember->join(
					$this->Team->id,
					$player['Player']['id'],
					$user_id,
					TeamMember::LEADER,
					TeamMember::ACTIVE
				);

				if ($autoApprove) {
					$this->Session->setFlash(__d('tournament', 'Your team was successfully created.'));
					$this->redirect(array('action' => 'profile', $this->Team->data['Team']['slug']));

				} else {
					$this->Session->setFlash(__d('tournament', 'Your team was successfully created. It will be usable once approved by the staff.'));
					$this->redirect(array('action' => 'index'));
				}
			}
		}
	}

	/**
	 * View a team profile.
	 *
	 * @param string $slug
	 * @throws NotFoundException
	 */
	public function profile($slug) {
		$team = $this->Team->getTeamProfile($slug);

		if (!$team) {
			throw new NotFoundException();
		}

		$this->set('team', $team);
		$this->set('member', $this->TeamMember->getByUserId($team['Team']['id'], $this->Auth->user('id'), true));
	}

	/**
	 * Attempt to join a team.
	 *
	 * @param string $slug
	 * @throws NotFoundException
	 * @throws UnauthorizedException
	 */
	public function join($slug) {
		$team = $this->Team->getBySlug($slug);

		if (!$team) {
			throw new NotFoundException();

		} else if ($team['Team']['status'] != Team::ACTIVE) {
			throw new UnauthorizedException();
		}

		$user_id = $this->Auth->user('id');
		$member = $this->TeamMember->getByUserId($team['Team']['id'], $user_id);

		// If a record already exists, redirect
		if ($member) {
			if ($member['TeamMember']['status'] == TeamMember::PENDING) {
				$this->Session->setFlash(__d('tournament', 'You currently have a pending join request for %s', $team['Team']['name']));
				$this->redirect(array('action' => 'profile', 'slug' => $slug));

			} else if ($member['TeamMember']['status'] == TeamMember::ACTIVE) {
				$this->Session->setFlash(__d('tournament', 'You are already in the %s team', $team['Team']['name']));
				$this->redirect(array('action' => 'profile', 'slug' => $slug));
			}
		}

		if ($this->request->is('post')) {
			$status = TeamMember::PENDING;
			$continue = true;

			// If password is set, attempt to verify
			if (!empty($this->request->data['Team']['password'])) {
				if ($this->request->data['Team']['password'] !== $team['Team']['password']) {
					$this->Team->invalidate('password', __d('tournament', 'Invalid password'));
					$continue = false;
				} else {
					$status = TeamMember::ACTIVE;
				}
			}

			$player = $this->Player->getPlayer($user_id);

			// Attempt to join
			if ($continue && $this->TeamMember->join(
				$team['Team']['id'],
				$player['Player']['id'],
				$user_id,
				TeamMember::MEMBER,
				$status
			)) {
				if ($status === TeamMember::PENDING) {
					$this->Session->setFlash(__d('tournament', 'Your request to join %s has been sent', $team['Team']['name']));
				} else {
					$this->Session->setFlash(__d('tournament', 'You have joined %s', $team['Team']['name']));
				}

				$this->redirect(array('action' => 'profile', 'slug' => $slug));
			}
		}

		$this->set('team', $team);
		$this->Team->validate = array();
	}

	/**
	 * Attempt to leave a team.
	 *
	 * @param string $slug
	 * @throws NotFoundException
	 * @throws UnauthorizedException
	 */
	public function leave($slug) {
		$team = $this->Team->getBySlug($slug);

		if (!$team) {
			throw new NotFoundException();

		} else if ($team['Team']['status'] != Team::ACTIVE) {
			throw new UnauthorizedException();
		}

		$member = $this->TeamMember->getByUserId($team['Team']['id'], $this->Auth->user('id'));

		if (!$member || $member['TeamMember']['status'] >= TeamMember::REMOVED) {
			$this->redirect(array('action' => 'profile', 'slug' => $slug));
		}

		if ($member['TeamMember']['role'] == TeamMember::LEADER) {
			$this->Session->setFlash(__d('tournament', 'Only non-leaders may leave the team'), 'default', array('class' => 'error'));
			$this->redirect(array('action' => 'profile', 'slug' => $slug));
		}

		$this->TeamMember->updateStatus($member['TeamMember']['id'], TeamMember::QUIT);

		$this->Session->setFlash(__d('tournament', 'You have left %s', $team['Team']['name']));
		$this->redirect(array('action' => 'profile', 'slug' => $slug));
	}

	/**
	 * Edit a team. Only the owner can edit.
	 *
	 * @param string $slug
	 * @throws NotFoundException
	 * @throws UnauthorizedException
	 */
	public function edit($slug) {
		$team = $this->Team->getBySlug($slug);

		if (!$team) {
			throw new NotFoundException();

		} else if ($this->Auth->user('id') != $team['Team']['user_id']) {
			throw new UnauthorizedException();

		} else if ($team['Team']['status'] != Team::ACTIVE) {
			throw new UnauthorizedException();
		}

		if ($this->request->is('put')) {
			$this->Team->id = $team['Team']['id'];

			if ($this->Team->save($this->request->data, true, array('name', 'password', 'slug', 'description', 'logo', 'user_id'))) {
				switch ($this->request->data['Team']['action']) {
					case 'owner':
						if ($this->request->data['Team']['user_id'] != $team['Team']['user_id']) {
							$oldOwner = $this->TeamMember->getByUserId($team['Team']['id'], $team['Team']['user_id']);
							$newOwner = $this->TeamMember->getByUserId($team['Team']['id'], $this->request->data['Team']['user_id']);

							// Demote old and promote new
							$this->TeamMember->demote($oldOwner['TeamMember']['id'], TeamMember::MEMBER);
							$this->TeamMember->promote($newOwner['TeamMember']['id'], TeamMember::LEADER);

							// Remove from team if true
							if ($this->request->data['Team']['leave']) {
								$this->TeamMember->updateStatus($oldOwner['TeamMember']['id'], TeamMember::QUIT);
								$this->Session->setFlash(__d('tournament', 'Left %s and changed ownership', $team['Team']['name']));

							} else {
								$this->Session->setFlash(__d('tournament', '%s ownership changed', $team['Team']['name']));
							}
						}
					break;
					case 'logo':
						$this->Session->setFlash(__d('tournament', '%s logo successfully uploaded', $team['Team']['name']));
					break;
					case 'disband':
						if ($this->request->data['Team']['disband']) {
							$this->Team->disband($team['Team']['id']);

							$this->Session->setFlash(__d('tournament', '%s has been disbanded', $team['Team']['name']));
							$this->redirect(array('action' => 'index'));
						}
					break;
					default:
						$this->Session->setFlash(__d('tournament', '%s successfully updated', $team['Team']['name']));
					break;
				}
			}
		}

		// Merge since there are multiple forms
		$this->request->data = Hash::merge($team, $this->request->data);

		$this->set('team', $team);
		$this->set('users', $this->TeamMember->getListByRole($team['Team']['id'], array(TeamMember::LEADER, TeamMember::CO_LEADER, TeamMember::MANAGER)));
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('index', 'profile');
	}

}