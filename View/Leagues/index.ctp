<div class="title">
	<h2><?php echo __d('tournament', 'Leagues'); ?></h2>
</div>

<div class="container">
	<?php
	echo $this->element('Admin.pagination', array('class' => 'top'));
	echo $this->element('tables/league_list');
	echo $this->element('Admin.pagination', array('class' => 'bottom')); ?>
</div>