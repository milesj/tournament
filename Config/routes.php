<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/cakephp/tournament
 */

/**
 * Enable RSS feeds.
 */
Router::parseExtensions('rss');

/**
 * Defaults.
 */
Router::connect('/tournament/schedule/*', array('plugin' => 'tournament', 'controller' => 'tournament', 'action' => 'schedule'));

/**
 * Players.
 */
Router::connect('/tournament/players/*', array('plugin' => 'tournament', 'controller' => 'players', 'action' => 'index'));

Router::connect('/tournament/player/:id/*',
	array('plugin' => 'tournament', 'controller' => 'players', 'action' => 'profile'),
	array('pass' => array('id')));

/**
 * Teams.
 */
Router::connect('/tournament/teams/*', array('plugin' => 'tournament', 'controller' => 'teams', 'action' => 'index'));

Router::connect('/tournament/teams/:id/*',
	array('plugin' => 'tournament', 'controller' => 'teams', 'action' => 'profile'),
	array('pass' => array('id')));

/**
 * Leagues.
 */
Router::connect('/tournament/:league/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'league'),
	array('pass' => array('league'), 'league' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'event'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/teams/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'teams'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/players/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'players'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/matches/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'matches'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));
