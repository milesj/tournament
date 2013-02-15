<?php

if (empty($args)) {
	$args = $this->passedArgs;
}

$this->Paginator->options(array('url' => $args));

if ($this->Paginator->counter(array('format' => '%pages%')) > 1) { ?>
	<nav class="pagination <?php echo $class; ?>">
		<ol>
			<?php echo $this->Paginator->numbers(array(
				'tag' => 'li',
				'separator' => '',
				'first' => __d('tournament', 'First'),
				'last' => __d('tournament', 'Last')
			)); ?>
		</ol>

		<span class="clear"></span>
	</nav>
<?php } ?>