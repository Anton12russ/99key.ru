       <!--Дочерние категории для категорий на главной странице--> 
	   
                   <?php  foreach($parents as $parent) {?>				 
				       <li> 
                          <a href="<?= $parent['url']?>">
						    <span><?= $parent['name']?></span> 
							<span class="counter_parent"><?=$parent['count']?></span>
						  </a> 
                       </li>	
					<?php } ?>
		
<?php exit();?>