<?php

App::uses('TournamentAppController', 'Tournament.Controller');

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
	}

	/**
	 * Create a team.
	 */
	public function create() {
		$autoApprove = $this->config['settings']['autoApproveTeams'];

		if ($this->request->is('post')) {
			$this->request->data['Team']['user_id'] = $this->Auth->user('id');

			if ($autoApprove) {
				$this->request->data['Team']['status'] = Team::ACTIVE;
			}

			if ($this->Team->save($this->Team->create($this->request->data), true, array('name', 'password', 'slug', 'description', 'logo'))) {
				if ($autoApprove) {
					$this->Session->setFlash(__d('tournament', 'Your team was successfully created.'));
					$this->redirect(array('action' => 'profile', $this->Team->data['Team']['slug']));

				} else {
					$this->Session->setFlash(__d('tournament', 'Your team was successfully created. It will be usable once approved by the staff.'));
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		$this->render('form');
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

			if ($this->Team->save($this->request->data, true, array('name', 'password', 'slug', 'description', 'logo'))) {
				$this->Session->setFlash(__d('tournament', 'Your team was updated.'));
				unset($this->request->data['Team']);
			}
		} else {
			$this->request->data = $team;
		}

		$this->set('team', $team);
		$this->render('form');
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('index', 'profile');
	}

}