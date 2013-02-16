<div class="page-title">
	<h2><?php echo $player['User'][$config['userMap']['username']]; ?></h2>
	<b><?php echo __d('tournament', 'Points'); ?>:</b> <?php echo $player['Player']['points']; ?><br>
	<b><?php echo __d('tournament', 'Wins'); ?>:</b> <?php echo $player['Player']['wins']; ?><br>
	<b><?php echo __d('tournament', 'Losses'); ?>:</b> <?php echo $player['Player']['losses']; ?><br>
	<b><?php echo __d('tournament', 'Ties'); ?>:</b> <?php echo $player['Player']['ties']; ?>
</div>

<?php if (!empty($player['Team'])) { ?>
	<div class="container">
		<div class="container-head">
			<h3><?php echo __d('tournament', 'Teams'); ?></h3>
		</div>

		<div class="container-body">
			<div class="table no-paging">
				<table>
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
		</div>
	</div>
<?php } ?>

<?php if (!empty($player['Event'])) { ?>
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
						<?php foreach ($player['Event'] as $event) {
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