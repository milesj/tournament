<div class="page-header">
	<h2><?php echo $player['User'][$userFields['username']]; ?></h2>
</div>

<div class="page">
	<dl class="dl-horizontal">
		<dt><?php echo __d('tournament', 'Points'); ?></dt> <dd><?php echo number_format($player['Player']['points']); ?></dd>
		<dt><?php echo __d('tournament', 'Wins'); ?></dt> <dd><?php echo number_format($player['Player']['wins']); ?></dd>
		<dt><?php echo __d('tournament', 'Losses'); ?></dt> <dd><?php echo number_format($player['Player']['losses']); ?></dd>
		<dt><?php echo __d('tournament', 'Ties'); ?></dt> <dd><?php echo number_format($player['Player']['ties']); ?></dd>
	</dl>

	<?php if (!empty($player['Team'])) { ?>
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
	<?php } ?>

	<?php if (!empty($player['Event'])) { ?>
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
					<?php foreach ($player['Event'] as $event) {
						echo $this->element('rows/event_participant_for', array(
							'event' => $event
						));
					} ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
</div>