<?php

App::uses('TournamentAppController', 'Tournament.Controller');

class TournamentController extends TournamentAppController {

	public $uses = array('Tournament.League');

	public function index() {

	}

	public function schedule() {

	}

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow();
	}

}