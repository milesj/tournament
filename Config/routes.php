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
foreach (array('teams') as $controller) {
	Router::connect('/tournament/' . $controller, array('plugin' => 'tournament', 'controller' => $controller, 'action' => 'index'));
	Router::connect('/tournament/' . $controller . '/:action/*', array('plugin' => 'tournament', 'controller' => $controller));
}

Router::connect('/tournament/schedule/*', array('plugin' => 'tournament', 'controller' => 'tournament', 'action' => 'schedule'));

/**
 * Profiles.
 */
Router::connect('/tournament/player/:id/*',
	array('plugin' => 'tournament', 'controller' => 'players', 'action' => 'profile'),
	array('pass' => array('id')));

Router::connect('/tournament/team/:slug/*',
	array('plugin' => 'tournament', 'controller' => 'teams', 'action' => 'profile'),
	array('pass' => array('slug'), 'slug' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/team/:slug/:action/*',
	array('plugin' => 'tournament', 'controller' => 'teams'),
	array('pass' => array('slug'), 'slug' => '[-_a-zA-Z0-9]+'));

/**
 * Leagues.
 */
Router::connect('/tournament/:league/:event/teams/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'teams'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/players/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'players'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/matches/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'matches'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/:event/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'event'),
	array('pass' => array('league', 'event'), 'league' => '[-_a-zA-Z0-9]+', 'event' => '[-_a-zA-Z0-9]+'));

Router::connect('/tournament/:league/*',
	array('plugin' => 'tournament', 'controller' => 'leagues', 'action' => 'index'),
	array('pass' => array('league'), 'league' => '[-_a-zA-Z0-9]+'));
