<div class="title">
	<h2><?php echo $league['League']['name']; ?></h2>
</div>

<div class="container">
	<dl class="dl-horizontal">
		<dt><?php echo __d('tournament', 'Game'); ?></dt> <dd><?php echo $league['Game']['name']; ?></dd>
		<dt><?php echo __d('tournament', 'Region'); ?></dt> <dd><?php echo $league['Region']['name']; ?></dd>
	</dl>

	<?php echo $this->element('panels/league_events'); ?>
</div>