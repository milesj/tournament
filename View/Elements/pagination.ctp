<?php

if (empty($args)) {
	$args = $this->passedArgs;
}

$this->Paginator->options(array('url' => $args));

if ($this->Paginator->counter(array('format' => '%pages%')) > 1) { ?>
	<nav class="pagination">
		<ol>
			<?php echo $this->Paginator->numbers(array(
				'tag' => 'li',
				'separator' => '',
				'first' => 'First',
				'last' => 'Last'
			)); ?>
		</ol>

		<span class="clear"></span>
	</nav>
<?php } ?>