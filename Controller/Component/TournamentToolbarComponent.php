<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/cakephp/Tournament
 */

class TournamentToolbarComponent extends Component {

	/**
	 * Components.
	 *
	 * @type array
	 */
	public $components = array('Session');

	/**
	 * Controller instance.
	 *
	 * @type Controller
	 */
	public $Controller;

	/**
	 * Store the Controller.
	 *
	 * @param Controller $Controller
	 * @return void
	 */
	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;
	}

	/**
	 * Initialize the session and all data.
	 *
	 * @param Controller $Controller
	 * @return void
	 */
	public function startup(Controller $Controller) {
		$this->Controller = $Controller;

		if ($this->Session->check('Tournament.isBrowsing')) {
			return;
		}

		$user_id = $this->Controller->Auth->user('id');
		$banned = ($this->Controller->Auth->user(Configure::read('User.fieldMap.status')) == Configure::read('User.statusMap.banned'));
		$lastVisit = date('Y-m-d H:i:s');
		$isAdmin = false;
		$isSuper = false;
		$groups = array(0); // 0 is everything else
		$permissions = array(); // @todo

		if ($user_id && !$banned) {
			$player = ClassRegistry::init('Tournament.Player')->getPlayer($user_id);

			// @todo ACL
		// If not logged in or banned
		} else {
			$permissions = false;
		}

		$this->Session->write('Tournament.isAdmin', $isAdmin);
		$this->Session->write('Tournament.isSuper', $isSuper);
		$this->Session->write('Tournament.groups', array_values(array_unique($groups)));
		$this->Session->write('Tournament.permissions', $permissions);
		$this->Session->write('Tournament.lastVisit', $lastVisit);
		$this->Session->write('Tournament.isBrowsing', true);
	}

}
