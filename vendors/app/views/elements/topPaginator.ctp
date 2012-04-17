<?php echo $paginator->prev(__('Previous', true), array('rel'=>'Go to Previous Page','class'=>'boxes prev'));?>  
				  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
				  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
				  <span>|</span>
				  <?php } ?>
				<?php echo $paginator->next(__('Next', true), array('rel'=>'Go to Next Page','class' => 'boxes next'));?>