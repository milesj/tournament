<div class="panel">
	<div class="panel-head">
		<h3><?php echo __d('tournament', 'Roster'); ?></h3>
	</div>

	<div class="panel-body">
		<table class="table">
			<thead>
				<tr>
					<th> </th>
					<th><?php echo __d('tournament', 'Player'); ?></th>
					<th><?php echo __d('tournament', 'Status'); ?></th>
					<th><?php echo __d('tournament', 'Role'); ?></th>
					<th><?php echo __d('tournament', 'Joined / Left'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($team['TeamMember'] as $member) {
					echo $this->element('rows/team_member', array(
						'member' => $member
					));
				} ?>
			</tbody>
		</table>
	</div>
</div>