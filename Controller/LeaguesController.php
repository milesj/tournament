<?php

App::uses('TournamentAppController', 'Tournament.Controller');

/**
 * @property League $League
 * @property Event $Event
 * @property Match $Match
 */
class LeaguesController extends TournamentAppController {

	/**
	 * Models.
	 *
	 * @var array
	 */
	public $uses = array('Tournament.League', 'Tournament.Event', 'Tournament.Match');

	/**
	 * League detailed view.
	 *
	 * @param string $league
	 * @throws NotFoundException
	 */
	public function index($league) {
		$league = $this->League->getBySlug($league);

		if (!$league) {
			throw new NotFoundException();
		}

		$this->set('league', $league);
		$this->set('events', $this->Event->getEventsInLeague($league['League']['id']));
		$this->set('stats', $this->League->getStats($league['League']['id']));
	}

	/**
	 * Event detailed view.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function event($league, $event) {
		$league = $this->League->getBySlug($league);
		$event = $this->Event->getBySlug($event);

		if (!$league || !$event) {
			throw new NotFoundException();
		}

		$this->set('league', $league);
		$this->set('event', $event);
		$this->set('bracket', $this->Match->getBrackets($event['Event']['id'], $event['Event']['for']));
	}

	/**
	 * All participating teams within an event.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function teams($league, $event) {
		$league = $this->League->getBySlug($league);
		$event = $this->Event->getBySlug($event);

		if (!$league || !$event) {
			throw new NotFoundException();
		}

		$this->set('league', $league);
		$this->set('event', $event);
		$this->set('participants', $this->Event->Participant->getParticipantsByType($event['Event']['id'], Event::TEAM));
	}

	/**
	 * All participating teams within an event.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function players($league, $event) {
		$league = $this->League->getBySlug($league);
		$event = $this->Event->getBySlug($event);

		if (!$league || !$event) {
			throw new NotFoundException();
		}

		$this->set('league', $league);
		$this->set('event', $event);
		$this->set('participants', $this->Event->Participant->getParticipantsByType($event['Event']['id'], Event::PLAYER));
	}

	/**
	 * Event matches organized into a bracket.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function matches($league, $event) {
		$league = $this->League->getBySlug($league);
		$event = $this->Event->getBySlug($event);

		if (!$league || !$event) {
			throw new NotFoundException();
		}

		$this->set('league', $league);
		$this->set('event', $event);
		$this->set('bracket', $this->Match->getBrackets($event['Event']['id'], $event['Event']['for']));
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}