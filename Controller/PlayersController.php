<?php

App::uses('TournamentAppController', 'Tournament.Controller');
App::uses('Player', 'Tournament.Model');

/**
 * @property Player $Player
 */
class PlayersController extends TournamentAppController {

	/**
	 * Models.
	 *
	 * @var array
	 */
	public $uses = array('Tournament.Player');

	/**
	 * Pagination.
	 *
	 * @var array
	 */
	public $paginate = array(
		'Player' => array(
			'contain' => array('User', 'CurrentTeam' => array('Team')),
			'limit' => 25
		)
	);

	/**
	 * Paginate all the teams.
	 */
	public function index() {
		$this->set('players', $this->paginate('Player', array(
			'User.status' => Configure::read('Tournament.statusMap.active')
		)));
	}

	/**
	 * View a player profile.
	 *
	 * @param int $user_id
	 * @throws NotFoundException
	 */
	public function profile($user_id) {
		$player = $this->Player->getPlayerProfile($user_id);

		if (!$player) {
			throw new NotFoundException();
		}

		$this->set('player', $player);
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}