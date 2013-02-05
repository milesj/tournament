<?php

App::uses('TournamentAppController', 'Tournament.Controller');

class TournamentController extends TournamentAppController {

	public $uses = array('Tournament.League');

	public function index() {
		debug($this->League->getById(1));
		debug($this->League->getById(2));
	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}