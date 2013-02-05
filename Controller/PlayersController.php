<?php

App::uses('TournamentAppController', 'Tournament.Controller');

class PlayersController extends TournamentAppController {

	public $uses = array('Tournament.Player');

	public function index() {

	}

	public function profile($id) {

	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}