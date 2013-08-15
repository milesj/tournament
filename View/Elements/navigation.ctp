<nav class="nav clear-after">
	<div class="nav-buttons">
		<?php
		if ($user) {
			echo $this->Html->link($user[$userFields['username']], $this->Admin->getUserRoute('profile', $user), array('class' => 'button'));
			echo $this->Html->link(__d('tournament', 'Logout'), $userRoutes['logout'], array('class' => 'button error'));

		} else {
			echo $this->Html->link(__d('tournament', 'Login'), $userRoutes['login'], array('class' => 'button'));

			if (!empty($userRoutes['signup'])) {
				echo $this->Html->link(__d('tournament', 'Sign Up'), $userRoutes['signup'], array('class' => 'button'));
			}

			if (!empty($userRoutes['forgotPass'])) {
				echo $this->Html->link(__d('tournament', 'Forgot Password'), $userRoutes['forgotPass'], array('class' => 'button'));
			}
		} ?>
	</div>

	<?php echo $this->Html->link(__d('tournament', 'Tournament'), array(
		'controller' => 'tournament',
		'action' => 'index'
	), array('class' => 'nav-brand', 'escape' => false)); ?>

	<ul class="nav-menu">
		<?php // Loop over items
		$nav = array(
			'leagues' => __d('tournament', 'Leagues'),
			'events' => __d('tournament', 'Events'),
			'players' => __d('tournament', 'Players'),
			'teams' => __d('tournament', 'Teams')
		);

		foreach ($nav as $navItem => $navTitle) { ?>
			<li<?php if ($navItem === $this->request->params['controller']) echo ' class="is-active"'; ?>><?php echo $this->Html->link($navTitle, array('controller' => $navItem, 'action' => 'index')); ?></li>
		<?php }

		// Admin link
		if ($user && $this->Admin->isAdmin()) { ?>
			<li><?php echo $this->Html->link(__d('tournament', 'Admin'), array('controller' => 'admin', 'action' => 'index', 'plugin' => 'admin', 'admin' => false)); ?></li>
		<?php } ?>
	</ul>
</nav>