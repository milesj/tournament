<?php

App::uses('TournamentAppController', 'Tournament.Controller');

class TeamsController extends TournamentAppController {

	public $uses = array('Tournament.Team');

	public function index() {

	}

	public function profile($id) {

	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}