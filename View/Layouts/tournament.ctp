<?php
echo $this->Html->docType();
echo $this->OpenGraph->html(array('xmlns' => 'http://www.w3.org/1999/xhtml')); ?>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $this->Breadcrumb->pageTitle($settings['name'], array('separator' => $settings['titleSeparator'])); ?></title>
	<?php
	echo $this->Html->css('Tournament.normalize');
	echo $this->Html->css('//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css');
	echo $this->Html->css('Tournament.titon-0.2.0.min');
	echo $this->Html->css('Tournament.style');
	echo $this->Html->script('//ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js');
	echo $this->Html->script('Tournament.mootools-more-1.4.0.1');
	echo $this->Html->script('Tournament.titon-0.2.0.min');
	$this->OpenGraph->name($settings['name']);

	echo $this->OpenGraph->fetch();
	echo $this->fetch('css');
	echo $this->fetch('script'); ?>
</head>
<body>
	<div class="skeleton">
		<div class="head">
			<div class="navbar navbar-fixed-top">
				<?php
				echo $this->Html->link(__d('tournament', 'Tournament'), array('controller' => 'tournament', 'action' => 'index'), array('class' => 'navbar-brand'));
				echo $this->element('navigation'); ?>
			</div>
		</div>

		<div class="body">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>

		<div class="foot">
			<?php echo $this->element('copyright'); ?>
		</div>
	</div>

	<?php if (!CakePlugin::loaded('DebugKit')) {
		echo $this->element('sql_dump');
	} ?>
</body>
</html>