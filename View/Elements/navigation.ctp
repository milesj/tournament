<div class="nav-buttons pull-right">
	<?php // Display user stuff as buttons
	if ($user) {
		echo $this->Html->link($user[$userFields['username']], $this->Tournament->profileUrl($user), array('class' => 'btn btn-default navbar-btn'));
		echo $this->Html->link(__d('tournament', 'Logout'), $userRoutes['logout'], array('class' => 'btn btn-danger navbar-btn'));

	} else {
		echo $this->Html->link(__d('tournament', 'Login'), $userRoutes['login'], array('class' => 'btn btn-default navbar-btn'));

		if (!empty($userRoutes['signup'])) {
			echo $this->Html->link(__d('tournament', 'Sign Up'), $userRoutes['signup'], array('class' => 'btn btn-default navbar-btn'));
		}

		if (!empty($userRoutes['forgotPass'])) {
			echo $this->Html->link(__d('tournament', 'Forgot Password'), $userRoutes['forgotPass'], array('class' => 'btn btn-default navbar-btn'));
		}
	} ?>
</div>

<ul class="nav navbar-nav">
	<li><?php echo $this->Html->link(__d('tournament', 'Home'), $settings['url']); ?></li>

	<?php // Loop over items
	$nav = array(
		'leagues' => __d('tournament', 'Leagues'),
		'events' => __d('tournament', 'Events'),
		'players' => __d('tournament', 'Players'),
		'teams' => __d('tournament', 'Teams')
	);

	foreach ($nav as $navItem => $navTitle) { ?>
		<li<?php if ($navItem === $this->request->params['controller']) echo ' class="active"'; ?>><?php echo $this->Html->link($navTitle, array('controller' => $navItem, 'action' => 'index')); ?></li>
	<?php }

	// Admin link
	if ($user && $this->Tournament->isAdmin()) { ?>
		<li><?php echo $this->Html->link(__d('tournament', 'Admin'), array('controller' => 'admin', 'action' => 'index', 'plugin' => 'admin', 'admin' => false)); ?></li>
	<?php } ?>
</ul>