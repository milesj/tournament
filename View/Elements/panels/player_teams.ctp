<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __d('tournament', 'Teams'); ?></h3>
	</div>

	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th> </th>
				<th><?php echo __d('tournament', 'Team'); ?></th>
				<th><?php echo __d('tournament', 'Status'); ?></th>
				<th><?php echo __d('tournament', 'Role'); ?></th>
				<th><?php echo __d('tournament', 'Joined / Left'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($player['Team'] as $team) {
				echo $this->element('rows/team_member_for', array(
					'team' => $team
				));
			} ?>
		</tbody>
	</table>
</div>