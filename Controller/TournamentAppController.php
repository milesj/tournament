<?php

class TournamentAppController extends AppController {

	/**
	 * Remove parent models.
	 *
	 * @type array
	 */
	public $uses = array();

	/**
	 * Components.
	 *
	 * @type array
	 */
	public $components = array(
		'Session', 'Security', 'Cookie', 'Acl',
		'Auth' => array(
			'authorize' => array('Controller')
		),
		'Utility.AutoLogin',
		'Tournament.TournamentToolbar',
		'Admin.AdminToolbar'
	);

	/**
	 * Helpers.
	 *
	 * @type array
	 */
	public $helpers = array(
		'Html', 'Session', 'Form', 'Time', 'Text',
		'Utility.Breadcrumb', 'Utility.OpenGraph', 'Utility.Utility', 'Utility.Decoda',
		'Tournament.Tournament'
	);

	/**
	 * Plugin configuration.
	 *
	 * @var array
	 */
	public $config = array();

	/**
	 * Database forum settings.
	 *
	 * @var array
	 */
	public $settings = array();

	public function isAuthorized($user) {
		// TODO
		return true;
	}

	/**
	 * Before filter.
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		// Settings
		$this->config = Configure::read();
		$this->settings = Configure::read('Tournament.settings');
		$this->layout = $this->config['Tournament']['viewLayout'];

		// Localization
		$locale = $this->Auth->user(Configure::read('User.fieldMap.locale')) ?: $this->settings['defaultLocale'];
		Configure::write('Config.language', $locale);
	}

	/**
	 * Before render.
	 */
	public function beforeRender() {
		parent::beforeRender();

		$this->set('user', $this->Auth->user());
		$this->set('userFields', $this->config['User']['fieldMap']);
		$this->set('userRoutes', $this->config['User']['routes']);
		$this->set('config', $this->config);
		$this->set('settings', $this->settings);
	}

}
