<?php
if (empty($args)) {
	$args = $this->passedArgs;
}

$this->Paginator->options(array('url' => $args));

if ($this->Paginator->counter(array('format' => '%pages%')) > 1) { ?>
	<ol class="pagination <?php echo $class; ?>">
		<?php echo $this->Paginator->numbers(array(
			'tag' => 'li',
			'separator' => '',
			'currentTag' => 'a',
			'currentClass' => 'active',
			'first' => __d('tournament', 'First'),
			'last' => __d('tournament', 'Last')
		)); ?>
	</ol>
<?php } ?>