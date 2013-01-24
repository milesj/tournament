<?php

class TournamentAppController extends AppController {

	/**
	 * Remove parent models.
	 *
	 * @access public
	 * @var array
	 */
	public $uses = array();

	/**
	 * Components.
	 *
	 * @access public
	 * @var array
	 */
	public $components = array('Session', 'Security', 'Cookie', 'Auth', 'Utility.AutoLogin');

	/**
	 * Helpers.
	 *
	 * @access public
	 * @var array
	 */
	public $helpers = array('Html', 'Session', 'Form', 'Time', 'Text', 'Utility.Breadcrumb', 'Utility.OpenGraph');

	/**
	 * Plugin configuration.
	 *
	 * @access public
	 * @var array
	 */
	public $config = array();

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		// Settings
		$this->config = Configure::read('Tournament');

		// Localization
		$locale = $this->Auth->user('locale') ?: 'eng';
		Configure::write('Config.language', $locale);
		setlocale(LC_ALL, $locale . 'UTF8', $locale . 'UTF-8', $locale, 'eng.UTF8', 'eng.UTF-8', 'eng', 'en_US');

		// Authorization
		$referrer = $this->referer();
		$routes = $this->config['routes'];

		if (!$referrer || $referrer === '/tournament/users/login' || $referrer === '/admin/tournament/users/login') {
			$referrer = array('plugin' => 'tournament', 'controller' => 'tournament', 'action' => 'index');
		}

		$this->Auth->loginAction = $routes['login'];
		$this->Auth->loginRedirect = $referrer;
		$this->Auth->logoutRedirect = $referrer;

		// AutoLogin
		$this->AutoLogin->settings = array(
			'model' => TOURNAMENT_USER,
			'username' => $this->config['userMap']['username'],
			'password' => $this->config['userMap']['password'],
			'plugin' => $routes['login']['plugin'],
			'controller' => $routes['login']['controller'],
			'loginAction' => $routes['login']['action'],
			'logoutAction' => $routes['logout']['action']
		);
	}

	/**
	 * Before render.
	 */
	public function beforeRender() {
		$this->set('user', $this->Auth->user());
		$this->set('config', $this->config);
	}

}
