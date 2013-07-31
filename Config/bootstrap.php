<?php

/**
 * Tie Breaking:
 * http://en.wikipedia.org/wiki/Buchholz_system
 * http://www.wizards.com/dci/downloads/Tiebreakers.pdf
 */

App::uses('ClassRegistry', 'Utility');
App::uses('Sanitize', 'Utility');

/**
 * Tournament critical constants.
 */
define('TOURNAMENT_PLUGIN', dirname(__DIR__) . '/');

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
	'showQuitTeamMembers' => true,

	// Events
	'defaultWinPoints' => 3,
	'defaultLossPoints' => 0,
	'defaultTiePoints' => 1,

	// Bracket
	'showBracketSeed' => true,

	// Misc
	'defaultLocale' => 'eng',
	'defaultTimezone' => '-8'
));

/**
 * File dimensions and settings for uploads.
 */
Configure::write('Tournament.uploads', array(
	'leagueLogo' => array(250, 125),
	'teamLogo' => array(250, 125),
	'transport' => array()
));