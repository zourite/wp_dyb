<p>
   	
    <strong><?php echo $value['title'] ?></strong> chez <?php echo $value['company'] ?><br/>

    <small> 

    <?php echo date_i18n(get_option('date_format') ,strtotime($value['start']))  ?> au <?php echo date_i18n(get_option('date_format'), strtotime($value['end']))  ?> | 
    <?php echo $value['city'] ?> - <?php echo $country[$value['country']['isoCode']]['name'] ?></small>
</p>

         <?php if(!empty($img)):?>

          <img style="float:right" src="http://doyoubuzz.com/<?php echo $img ?>" />

          <?php endif; ?>
 
<ul>	 		
     	
      <?php 
     
     	foreach($value['missions']['mission'] as $mission) :
     
     	echo '<li>'.$mission['description'].'</li>';
     
    	endforeach; 

      ?>

</ul>