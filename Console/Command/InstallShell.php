<?php

App::uses('BaseInstallShell', 'Utility.Console/Command');

class InstallShell extends BaseInstallShell {

	/**
	 * Trigger install.
	 */
	public function main() {
		$userMap = Configure::read('Tournament.userMap');
		unset($userMap['status'], $userMap['avatar']);

		$this->setSteps(array(
			'Check Database Configuration' => 'checkDbConfig',
			'Set Table Prefix' => 'checkTablePrefix',
			'Set Users Table' => 'checkUsersTable',
			'Check Table Status' => 'checkRequiredTables',
			'Setup ACL' => 'setupAcl',
			'Create Database Tables' => 'createTables',
			'Create Administrator' => 'createAdmin',
			'Finish Installation' => 'finish'
		))
		->setDbConfig(TOURNAMENT_DATABASE)
		->setTablePrefix(TOURNAMENT_PREFIX)
		->setRequiredTables(array('aros', 'acos', 'aros_acos'))
		->setUserFields($userMap);

		$this->out('Plugin: Tournament v' . Configure::read('Tournament.version'));
		$this->out('Copyright: Miles Johnson, 2010-' . date('Y'));
		$this->out('Help: http://milesj.me/code/cakephp/tournament');

		parent::main();
	}

	/**
	 * Setup all the ACL records.
	 */
	public function setupAcl() {
		$this->out('<info>Creating ACL records...</info>');

		$admin = Configure::read('Tournament.aroMap.admin');
		$acl = ClassRegistry::init('Tournament.Access')->installAcl();

		foreach ($acl['aro'] as $id => $alias) {
			if ($alias === $admin) {
				$this->config['acl_admin'] = $id;
			}
		}

		$this->out('<info>ACL setup, proceeding...</info>');
		return true;
	}

	/**
	 * Setup the admin user.
	 *
	 * @return bool
	 */
	public function createAdmin() {
		$answer = strtoupper($this->in('<question>Would you like to [c]reate a new user, or use an [e]xisting user?</question>', array('C', 'E')));

		if ($answer === 'C') {
			$id = $this->createUser();
		} else if ($answer === 'E') {
			$id = $this->findUser();
		} else {
			$id = $this->createAdmin();
		}

		$result = ClassRegistry::init('Tournament.Access')->add(array(
			'parent_id' => $this->config['acl_admin'],
			'foreign_key' => $id
		));

		if (!$result) {
			$this->out('<error>An error occurred while granting administrator access</error>');
			return $this->createAdmin();
		}

		$this->out('<info>Administrator setup, proceeding...</info>');
		return true;
	}

	/**
	 * Finalize the installation.
	 *
	 * @return bool
	 */
	public function finish() {
		$this->hr(1);
		$this->out('Tournament installation complete!');
		$this->out('Please read the documentation for further instructions:');
		$this->out('http://milesj.me/code/cakephp/tournament');
		$this->hr(1);

		return true;
	}

}