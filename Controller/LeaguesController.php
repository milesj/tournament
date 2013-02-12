<?php

App::uses('TournamentAppController', 'Tournament.Controller');

/**
 * @property League $League
 * @property Event $Event
 */
class LeaguesController extends TournamentAppController {

	/**
	 * Models.
	 *
	 * @var array
	 */
	public $uses = array('Tournament.League', 'Tournament.Event');

	/**
	 * Pagination.
	 *
	 * @var array
	 */
	public $paginate = array(
		'League' => array(
			'contain' => array('Game', 'Region'),
			'limit' => 25
		)
	);

	/**
	 * Paginate all the leagues.
	 */
	public function index() {
		$this->set('leagues', $this->paginate('League'));
	}

	/**
	 * League detailed view.
	 *
	 * @param string $league
	 * @throws NotFoundException
	 */
	public function view($league) {
		$league = $this->League->getBySlug($league);

		if (!$league) {
			throw new NotFoundException();
		}

		$this->set('league', $league);
		$this->set('events', $this->Event->getEventsInLeague($league['League']['id']));
		$this->set('stats', $this->League->getStats($league['League']['id']));
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}