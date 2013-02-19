<?php

App::uses('TournamentAppController', 'Tournament.Controller');

/**
 * @property Event $Event
 * @property Match $Match
 */
class EventsController extends TournamentAppController {

	/**
	 * Models.
	 *
	 * @var array
	 */
	public $uses = array('Tournament.Event', 'Tournament.Match');

	/**
	 * Helpers.
	 *
	 * @var array
	 */
	public $helpers = array('Tournament.Bracket');

	/**
	 * Pagination.
	 *
	 * @var array
	 */
	public $paginate = array(
		'Event' => array(
			'contain' => array('League', 'Division', 'Game'),
			'limit' => 25
		)
	);

	/**
	 * Paginate all the events.
	 */
	public function index() {
		$this->set('events', $this->paginate('Event'));
	}

	/**
	 * Event detailed view.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function view($league, $event) {
		$event = $this->Event->getBySlug($event);

		if (!$event) {
			throw new NotFoundException();
		}

		$this->set('event', $event);
		$this->set('bracket', $this->Match->getBrackets($event));
	}

	/**
	 * All participating teams within an event.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function teams($league, $event) {
		$event = $this->Event->getBySlug($event);

		if (!$event || $event['Event']['for'] == Event::PLAYER) {
			throw new NotFoundException();
		}

		$this->set('event', $event);
		$this->set('participants', $this->Event->EventParticipant->getParticipantsByType($event['Event']['id'], Event::TEAM));
	}

	/**
	 * All participating teams within an event.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function players($league, $event) {
		$event = $this->Event->getBySlug($event);

		if (!$event || $event['Event']['for'] == Event::TEAM) {
			throw new NotFoundException();
		}

		$this->set('event', $event);
		$this->set('participants', $this->Event->EventParticipant->getParticipantsByType($event['Event']['id'], Event::PLAYER));
	}

	/**
	 * Event matches organized into a bracket.
	 *
	 * @param string $league
	 * @param string $event
	 * @throws NotFoundException
	 */
	public function bracket($league, $event) {
		$event = $this->Event->getBySlug($event);

		if (!$event) {
			throw new NotFoundException();
		}

		$this->set('event', $event);
		$this->set('bracket', $this->Match->getBrackets($event));
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}