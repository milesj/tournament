<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __d('tournament', 'Events'); ?></h3>
	</div>

	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th><?php echo __d('tournament', 'Event'); ?></th>
				<th><?php echo __d('tournament', 'Game'); ?></th>
				<th><?php echo __d('tournament', 'League'); ?></th>
				<th><?php echo __d('tournament', 'Division'); ?></th>
				<th><?php echo __d('tournament', 'Points'); ?></th>
				<th><?php echo __d('tournament', 'Wins'); ?></th>
				<th><?php echo __d('tournament', 'Losses'); ?></th>
				<th><?php echo __d('tournament', 'Ties'); ?></th>
				<th><?php echo __d('tournament', 'Joined'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($team['Event'] as $event) {
				echo $this->element('rows/event_participant_for', array(
					'event' => $event
				));
			} ?>
		</tbody>
	</table>
</div>