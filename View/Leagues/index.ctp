
<div class="page-title">
	<h2><?php echo __d('tournament', 'Leagues'); ?></h2>
</div>

<div class="container">
	<div class="container-body">
		<?php echo $this->element('pagination', array('class' => 'top')); ?>

		<div class="table">
			<table>
				<thead>
					<tr>
						<th> </th>
						<th><?php echo $this->Paginator->sort('League.name', __d('tournament', 'League')); ?></th>
						<th><?php echo $this->Paginator->sort('Game.name', __d('tournament', 'Game')); ?></th>
						<th><?php echo $this->Paginator->sort('Region.name', __d('tournament', 'Region')); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($leagues) {
						foreach ($leagues as $league) { ?>

					<tr>
						<td class="col-logo">
							<?php if ($logo = $league['League']['logo']) {
								echo $this->Html->image($logo, array('url' => array('action' => 'view', 'league' => $league['League']['slug'])));
							} ?>
						</td>
						<td>
							<b><?php echo $this->Html->link($league['League']['name'], array('action' => 'view', 'league' => $league['League']['slug'])); ?></b>
							<?php echo $league['League']['description']; ?>
						</td>
						<td><?php echo $league['Game']['name']; ?></td>
						<td><?php echo $league['Region']['name']; ?></td>
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
		</div>

		<?php echo $this->element('pagination', array('class' => 'bottom')); ?>
	</div>
</div>