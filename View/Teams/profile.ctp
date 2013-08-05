<div class="page-header">
	<?php if ($user && $team['Team']['status'] == Team::ACTIVE) { ?>
		<div class="buttons">
			<?php if ($user['id'] == $team['Team']['user_id']) {
				echo $this->Html->link('Manage Team', array('plugin' => 'tournament', 'action' => 'edit', 'slug' => $team['Team']['slug']), array('class' => 'btn btn-info'));
			}

			if ($member) {
				echo $this->Html->link('Leave Team', array('plugin' => 'tournament', 'action' => 'leave', 'slug' => $team['Team']['slug']), array('class' => 'btn btn-danger'));
			} else {
				echo $this->Html->link('Join Team', array('plugin' => 'tournament', 'action' => 'join', 'slug' => $team['Team']['slug']), array('class' => 'btn btn-default'));
			} ?>
		</div>
	<?php } ?>

	<h2><?php echo $team['Team']['name']; ?></h2>
</div>

<div class="page">
	<?php if ($desc = $team['Team']['description']) { ?>
		<p><?php echo $desc; ?></p>
	<?php } ?>

	<dl class="dl-horizontal">
		<dt><?php echo __d('tournament', 'Members'); ?></dt> <dd><?php echo number_format($team['Team']['team_member_count']); ?></dd>
		<dt><?php echo __d('tournament', 'Points'); ?></dt> <dd><?php echo number_format($team['Team']['points']); ?></dd>
		<dt><?php echo __d('tournament', 'Wins'); ?></dt> <dd><?php echo number_format($team['Team']['wins']); ?></dd>
		<dt><?php echo __d('tournament', 'Losses'); ?></dt> <dd><?php echo number_format($team['Team']['losses']); ?></dd>
		<dt><?php echo __d('tournament', 'Ties'); ?></dt> <dd><?php echo number_format($team['Team']['ties']); ?></dd>
	</dl>

	<?php if (!empty($team['TeamMember'])) { ?>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __d('tournament', 'Roster'); ?></h3>
			</div>

			<table class="table table-striped table-bordered table-hover">
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
	<?php } ?>

	<?php if (!empty($team['Event'])) { ?>
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
	<?php } ?>
</div>