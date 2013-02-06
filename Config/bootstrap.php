<?php

App::uses('ClassRegistry', 'Utility');
App::uses('Sanitize', 'Utility');

/**
 * Tournament critical constants.
 */
define('TOURNAMENT_PLUGIN', dirname(__DIR__) . '/');

// User Model
if (!defined('TOURNAMENT_USER')) {
	define('TOURNAMENT_USER', 'User');
}

// Table Prefix
if (!defined('TOURNAMENT_PREFIX')) {
	define('TOURNAMENT_PREFIX', 'tourn_');
}

// Database config
if (!defined('TOURNAMENT_DATABASE')) {
	define('TOURNAMENT_DATABASE', 'default');
}

/**
 * Current version.
 */
Configure::write('Tournament.version', file_get_contents(dirname(__DIR__) . '/version.md'));

/**
 * A map of user fields that are used within this plugin. If your users table has a different naming scheme
 * for the username, email, status, etc fields, you can define their replacement here.
 */
Configure::write('Tournament.userMap', array(
	'username'	=> 'username',
	'password'	=> 'password',
	'email'		=> 'email',
	'status'	=> 'status',
	'avatar'	=> 'avatar'
));

/**
 * A map of status values for the users "status" column.
 * This column determines if the user is pending, currently active, or banned.
 */
Configure::write('Tournament.statusMap', array(
	'pending'	=> 0,
	'active'	=> 1,
	'banned'	=> 2
));

/**
 * A map of keys to ACL requester aliases.
 */
Configure::write('Tournament.aroMap', array(
	'admin' 	=> 'administrator',
	'superMod'	=> 'superModerator'
));

/**
 * A map of external user management URLs.
 */
Configure::write('Tournament.routes', array(
	'login' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'login'),
	'logout' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'logout'),
	'signup' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'signup'),
	'profile' => array('plugin' => 'tournament', 'admin' => false, 'controller' => 'players', 'action' => 'profile', 'id' => '{id}')
));

/**
 * Customizable view settings. This allows for layout and template overrides.
 */
Configure::write('Tournament.viewLayout', 'tournament');

/**
 * List of settings that alter the forum systems.
 */
Configure::write('Tournament.settings', array(
	'name' => __d('tournament', 'Tournament'),
	'email' => 'tournament@cakephp.org',
	'url' => 'http://milesj.me/code/cakephp/tournament',
	'titleSeparator' => ' - ',

	// Teams
	'autoApproveTeams' => false,
	'maxTeamsToJoin' => 1,
	'showRemovedTeamMembers' => true,
	'showQuitTeamMembers' => true
));

/**
 * Handle exceptions and errors.
 */
Configure::write('Exception.renderer', 'Tournament.TournamentExceptionRenderer');