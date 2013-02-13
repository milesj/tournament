<?php
echo $this->Html->docType();
echo $this->OpenGraph->html(array('xmlns' => 'http://www.w3.org/1999/xhtml')); ?>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $this->Breadcrumb->pageTitle($settings['name'], array('separator' => $settings['titleSeparator'])); ?></title>
	<?php
	echo $this->Html->css('Tournament.normalize');
	echo $this->Html->css('Tournament.style');
	$this->OpenGraph->name($settings['name']);

	echo $this->OpenGraph->fetch();
	echo $this->fetch('css');
	echo $this->fetch('script'); ?>
</head>

<body>
	<div class="wrapper">
		<div class="head">
			Head
		</div>

		<div class="body">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>

		<div class="foot">
			Foot
		</div>
	</div>

	<?php if (!CakePlugin::loaded('DebugKit')) {
		echo $this->element('sql_dump');
	} ?>
</body>
</html>