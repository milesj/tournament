<div class="title">
	<h2><?php echo __d('tournament', 'Events'); ?></h2>
</div>

<div class="container">
	<?php
	echo $this->element('Admin.pagination', array('class' => 'top'));
	echo $this->element('tables/event_list');
	echo $this->element('Admin.pagination', array('class' => 'bottom')); ?>
</div>