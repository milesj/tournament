<?php

App::uses('TournamentAppController', 'Tournament.Controller');

class LeaguesController extends TournamentAppController {

	public $uses = array('Tournament.League', 'Tournament.Event');

	public function index() {

	}

	public function league($league) {

	}

	public function event($league, $event) {

	}

	public function teams($league, $event) {

	}

	public function players($league, $event) {

	}

	public function matches($league, $event) {

	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}