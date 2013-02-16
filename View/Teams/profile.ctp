<div class="page-title">
	<?php if ($user && $team['Team']['status'] == Team::ACTIVE) { ?>
		<div class="buttons">
			<?php if ($user['id'] == $team['Team']['user_id']) {
				echo $this->Html->link('Manage Team', array('plugin' => 'tournament', 'action' => 'edit', 'slug' => $team['Team']['slug']), array('class' => 'button'));
			}

			if ($member) {
				echo $this->Html->link('Leave Team', array('plugin' => 'tournament', 'action' => 'leave', 'slug' => $team['Team']['slug']), array('class' => 'button'));
			} else {
				echo $this->Html->link('Join Team', array('plugin' => 'tournament', 'action' => 'join', 'slug' => $team['Team']['slug']), array('class' => 'button'));
			} ?>
		</div>
	<?php } ?>

	<h2><?php echo $team['Team']['name']; ?></h2>
	<?php if ($desc = $team['Team']['description']) {
		echo $desc . '<br>';
	} ?>
	<b><?php echo __d('tournament', 'Members'); ?>:</b> <?php echo $team['Team']['team_member_count']; ?><br>
	<b><?php echo __d('tournament', 'Points'); ?>:</b> <?php echo $team['Team']['points']; ?><br>
	<b><?php echo __d('tournament', 'Wins'); ?>:</b> <?php echo $team['Team']['wins']; ?><br>
	<b><?php echo __d('tournament', 'Losses'); ?>:</b> <?php echo $team['Team']['losses']; ?><br>
	<b><?php echo __d('tournament', 'Ties'); ?>:</b> <?php echo $team['Team']['ties']; ?>
</div>

<?php if (!empty($team['TeamMember'])) { ?>
	<div class="container">
		<div class="container-head">
			<h3><?php echo __d('tournament', 'Roster'); ?></h3>
		</div>

		<div class="container-body">
			<div class="table no-paging">
				<table>
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
	</div>
<?php } ?>

<?php if (!empty($team['Event'])) { ?>
	<div class="container">
		<div class="container-head">
			<h3><?php echo __d('tournament', 'Events'); ?></h3>
		</div>

		<div class="container-body">
			<div class="table no-paging">
				<table>
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
		</div>
	</div>
<?php } ?>