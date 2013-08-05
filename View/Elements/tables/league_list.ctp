<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th colspan="2"><?php echo $this->Paginator->sort('League.name', __d('tournament', 'League')); ?></th>
			<th><?php echo $this->Paginator->sort('Game.name', __d('tournament', 'Game')); ?></th>
			<th><?php echo $this->Paginator->sort('Region.name', __d('tournament', 'Region')); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($leagues)) {
			foreach ($leagues as $league) { ?>

		<tr>
			<td class="col-league-image">
				<?php if ($logo = $league['League']['logo']) {
					echo $this->Html->image($logo, array('url' => array('action' => 'view', 'league' => $league['League']['slug'])));
				} ?>
			</td>
			<td class="col-league">
				<?php echo $this->Html->link($league['League']['name'], array('action' => 'view', 'league' => $league['League']['slug'])); ?>
				<?php echo $league['League']['description']; ?>
			</td>
			<td class="col-game-name"><?php echo $league['Game']['name']; ?></td>
			<td class="col-region-name"><?php echo $league['Region']['name']; ?></td>
		</tr>

			<?php }
		} else { ?>

		<tr>
			<td colspan="4" class="no-results">
				<?php echo __d('tournament', 'There are no results to display'); ?>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>