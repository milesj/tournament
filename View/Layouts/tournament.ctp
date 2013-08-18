<?php
echo $this->Html->docType();
echo $this->OpenGraph->html(); ?>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $this->Breadcrumb->pageTitle($settings['name'], array('separator' => $settings['titleSeparator'])); ?></title>
	<?php
	echo $this->Html->css('Admin.titon.min');
	echo $this->Html->css('Admin.font-awesome.min');
	echo $this->Html->css('Admin.style');
	echo $this->Html->css('Tournament.style');
	echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/mootools/1.4.5/mootools-core-full-nocompat-yc.js');
	echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/mootools-more/1.4.0.1/mootools-more-yui-compressed.min.js');
	echo $this->Html->script('Admin.titon.min');
	echo $this->Html->script('Tournament.tournament');

	$locales = $config['Decoda']['locales'];
	$this->OpenGraph->name($settings['name']);
	$this->OpenGraph->locale(array($locales[Configure::read('Config.language')], $locales[$settings['defaultLocale']]));

	echo $this->OpenGraph->fetch();
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script'); ?>
</head>
<body class="controller-<?php echo $this->request->controller; ?>">
	<div class="skeleton">
		<header class="head">
			<?php echo $this->element('navigation'); ?>
		</header>

		<div class="body action-<?php echo $this->action; ?>">
			<?php
			$this->Breadcrumb->prepend($settings['name'], array('controller' => 'tournament', 'action' => 'index'));

			echo $this->element('Admin.breadcrumbs');
			echo $this->Session->flash();
			echo $this->fetch('content'); ?>
		</div>

		<footer class="foot">
			<div class="copyright">
				<?php printf(__d('tournament', 'Powered by the %s v%s'), $this->Html->link('Tournament Plugin', 'http://milesj.me/code/cakephp/tournament'), mb_strtoupper($config['Tournament']['version'])); ?><br>
				<?php printf(__d('tournament', 'Created by %s'), $this->Html->link('Miles Johnson', 'http://milesj.me')); ?>
			</div>

			<?php if (!CakePlugin::loaded('DebugKit')) {
				echo $this->element('sql_dump');
			} ?>
		</footer>
	</div>
</body>
</html>