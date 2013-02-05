<?php

App::uses('TournamentAppController', 'Tournament.Controller');
App::uses('Team', 'Tournament.Model');

/**
 * @property Team $Team
 */
class TeamsController extends TournamentAppController {

	/**
	 * Models.
	 *
	 * @var array
	 */
	public $uses = array('Tournament.Team');

	/**
	 * Pagination.
	 *
	 * @var array
	 */
	public $paginate = array(
		'Team' => array(
			'contain' => array('Owner'),
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
	 * View a team profile.
	 *
	 * @param string $slug
	 * @throws NotFoundException
	 */
	public function profile($slug) {
		$team = $this->Team->getBySlug($slug);

		if (!$team) {
			throw new NotFoundException();
		}

		$this->set('team', $team);
		$this->set('roster', $this->Team->TeamMember->getRoster($team['Team']['id']));
	}

	/**
	 * Create a team.
	 */
	public function create() {
		$autoApprove = $this->config['settings']['autoApproveTeams'];

		if ($this->request->is('post')) {
			$this->Team->create();

			$this->request->data['Team']['user_id'] = $this->Auth->user('id');

			if ($autoApprove) {
				$this->request->data['Team']['status'] = Team::ACTIVE;
			}

			if ($this->Team->save($this->request->data, true, array('name', 'password', 'slug', 'description', 'user_id', 'status'))) {
				$this->Team->TeamMember->join(
					$this->Team->id,
					$this->Auth->user('TournamentPlayer.id'),
					$this->Auth->user('id'),
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
	 * Edit a team. Only the owner can edit.
	 *
	 * @param int $id
	 * @throws NotFoundException
	 * @throws UnauthorizedException
	 */
	public function edit($id) {
		$team = $this->Team->getById($id);

		if (!$team) {
			throw new NotFoundException();
		} else if ($this->Auth->user('id') != $team['Team']['user_id']) {
			throw new UnauthorizedException();
		}

		if ($this->request->is('put')) {
			$this->Team->id = $id;

			// Only change password if value is set
			if (empty($this->request->data['Team']['password'])) {
				unset($this->request->data['Team']['password']);
			}

			if ($this->Team->save($this->request->data, true, array('name', 'password', 'slug', 'description', 'logo', 'user_id'))) {
				switch ($this->request->data['Team']['action']) {
					case 'owner':
						if ($this->request->data['Team']['user_id'] != $team['Team']['user_id']) {
							$oldOwner = $this->Team->TeamMember->getByUserId($team['Team']['id'], $team['Team']['user_id']);
							$newOwner = $this->Team->TeamMember->getByUserId($team['Team']['id'], $this->request->data['Team']['user_id']);

							// Demote old and promote new
							$this->Team->TeamMember->demote($oldOwner['TeamMember']['id'], TeamMember::MEMBER);
							$this->Team->TeamMember->promote($newOwner['TeamMember']['id'], TeamMember::LEADER);

							// Remove from team if true
							if ($this->request->data['Team']['leave']) {
								$this->Team->TeamMember->updateStatus($oldOwner['TeamMember']['id'], TeamMember::QUIT);
								$this->Session->setFlash(__d('tournament', 'Left %s and changed ownership', $team['Team']['name']));

							} else {
								$this->Session->setFlash(__d('tournament', '%s ownership changed', $team['Team']['name']));
							}
						}
					break;
					case 'logo':
						$this->Session->setFlash(__d('tournament', '%s logo successfully uploaded', $team['Team']['name']));
					break;
					default:
						$this->Session->setFlash(__d('tournament', '%s successfully updated', $team['Team']['name']));
					break;
				}

				unset($this->request->data['Team']);
			}
		} else {
			unset($team['Team']['password']);
			$this->request->data = $team;
		}

		$this->set('team', $team);
		$this->set('users', $this->Team->TeamMember->getListByRole($team['Team']['id'], array(TeamMember::LEADER, TeamMember::CO_LEADER, TeamMember::MANAGER)));
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('index', 'profile');
	}

}