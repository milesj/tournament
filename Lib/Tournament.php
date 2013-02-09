<?php

App::uses('EventParticipant', 'Tournament.Model');

abstract class Tournament {

	/**
	 * Event ID.
	 *
	 * @var int
	 */
	protected $_id;

	/**
	 * Event record.
	 *
	 * @var array
	 */
	protected $_event;

	/**
	 * Fetch event information.
	 *
	 * @param int $id
	 * @throws Exception
	 */
	public function __construct($id) {
		$event = ClassRegistry::init('Tournament.Event')->getById($id);

		if (!$event) {
			throw new Exception('Invalid event');

		} else if ($event['Event']['isRunning'] || $event['Event']['isFinished']) {
			throw new Exception('Event has already started');

		} else if ($event['Event']['isGenerated']) {
			throw new Exception('Matches have already been generated for this event');
		}

		$this->_id = $id;
		$this->_event = $event;
	}

	/**
	 * Generate brackets for the current event.
	 *
	 * @return bool
	 */
	abstract public function generateBrackets();

	/**
	 * Get all ready participants for an event. Take into account the event seeding order.
	 *
	 * @return array
	 */
	public function getParticipants() {
		$for = ($this->_event['Event']['for'] == Event::TEAM) ? 'Team' : 'Player';
		$order = 'RAND()';

		if ($this->_event['Event']['seed'] == Event::POINTS) {
			$order = array($for . '.points' => 'ASC');
		}

		return ClassRegistry::init('Tournament.EventParticipant')->find('all', array(
			'conditions' => array(
				'EventParticipant.event_id' => $this->_id,
				'EventParticipant.isReady' => EventParticipant::YES
			),
			'contain' => array($for),
			'order' => $order
		));
	}

}