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
			'Create Database Tables' => 'createTables',
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