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
	 * @type array
	 */
	public $uses = array('Tournament.Event', 'Tournament.Match');

	/**
	 * Helpers.
	 *
	 * @type array
	 */
	public $helpers = array('Tournament.Bracket');

	/**
	 * Pagination.
	 *
	 * @type array
	 */
	public $paginate = array(
		'Event' => array(
			'contain' => array('League', 'Division', 'Game'),
			'limit' => 25
		),
		'EventParticipant' => array(
			'order' => array(
				'EventParticipant.standing' => 'ASC',
				'EventParticipant.isReady' => 'DESC',
				'EventParticipant.created' => 'ASC'
			),
			'limit' => 50
		),
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

		$this->paginate['EventParticipant']['contain'] = array('Team' => array('Leader'));
		$this->paginate['EventParticipant']['conditions'] = array('EventParticipant.event_id' => $event['Event']['id']);

		$this->set('event', $event);
		$this->set('participants', $this->paginate('EventParticipant'));
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

		$this->paginate['EventParticipant']['contain'] = array('Player' => array('User'));
		$this->paginate['EventParticipant']['conditions'] = array('EventParticipant.event_id' => $event['Event']['id']);

		$this->set('event', $event);
		$this->set('participants', $this->paginate('EventParticipant'));
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
		$this->set('winner', $this->Event->EventParticipant->getWinner($event['Event']['id']));
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}