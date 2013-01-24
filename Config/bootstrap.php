<?php

App::uses('ClassRegistry', 'Utility');
App::uses('Sanitize', 'Utility');

/**
 * Name of the User model.
 */
if (!defined('TOURNAMENT_USER')) {
	define('TOURNAMENT_USER', 'User');
}

/**
 * Current version.
 */
Configure::write('Tournament.version', '0.0.0');

/**
 * A map of user fields that are used within this plugin. If your users table has a different naming scheme
 * for the username, email, status, etc fields, you can define their replacement here.
 */
Configure::write('Tournament.userMap', array(
	'username'	=> 'username',
	'password'	=> 'password',
	'email'		=> 'email',
	'status'	=> 'status'
));

/**
 * A map of external user management URLs.
 */
Configure::write('Tournament.routes', array(
	'login' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'login'),
	'logout' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'logout'),
	'signup' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'signup'),
	'forgotPass' => array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'forgot_password')
));

/**
 * Handle exceptions and errors.
 */
//Configure::write('Exception.renderer', 'Forum.ForumExceptionRenderer');