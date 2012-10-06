<?php
/*
Plugin Name: wp_dyb
Plugin URI: out
Description: Publiez votre profile Doyoubuzz ou des sections de votre profile
Version: 1.0
Author: Sonia SAUGRIN
Author URI: http://www.saugrin-sonia.fr/
*/


/*  Copyright 2011 SAUGRIN Sonia 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License,
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

 require WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/lib/oauth.php';   

class wp_dyb {


 function __construct(){
 
  $this->token = get_option( 'token_dyb', 'FALSE' ); 
  $this->token_secret = get_option( 'token_dyb_secret', 'FALSE' );

	add_action('admin_menu', array(&$this,'dyb_menu'));
  register_activation_hook( __FILE__, array( $this, 'dyb_activation' ) );
  
  add_action('dyb_maj', 'dyb_cron');
  
  add_action('init', 'startSession', 1);
  add_action('wp_logout', 'endSession');
  add_action('wp_login', 'endSession');
	
	wp_register_sidebar_widget(
    	
    	'wp_dyb_intro',        
    	'Statut DoYouBuzz',          
    	array(&$this,'dyb_intro'),  
    	array(                 
        'description' => "Affiche la disponibilité pour un poste ou des opportunités"
    	
    	));

	wp_register_sidebar_widget(
    	
    	'wp_dyb_skill',        
    	'Compétences DoYouBuzz',          
    	array(&$this,'dyb_skill'),  
    	array( 
    	                
        'description' => "Affiche la liste des compétences de votre CV"
    	
    	));

	wp_register_sidebar_widget(
    	
    	'wp_dyb_employment',        
    	'Experience DoYouBuzz',          
    	array(&$this,'dyb_employment'), 
    	array(  
    	                
        'description' => "Affiche vos experiences professionnelle"
    	
    	));

  }

 function startSession() {
    
    if(!session_id()) {
        
        session_start();
    
    }
  }

function endSession() {
    
    session_destroy ();

} 

function dyb_activation() {

  //$this->dyb_cron();

  wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'dyb_maj');

 } 


function dyb_menu() {
	
	add_menu_page( 'Dyb Options', 'WP_DYB', 'manage_options', 'dyb-zourite',  array(&$this,'dyb_views'),plugins_url('wp_dyb/img/doyoubuzz_16.png') );
  
  add_option("token_dyb", $_SESSION['access_token']);
  add_option("token_dyb_secret", $_SESSION['token_access_secret']);

}

function dyb_info_api() {

  

  $this->key = 'ZK8Pkir-htOxEKgy7x8O';
  $this->secret = 'WnIiPN6Z3t7EnHCjwTY_uZG6f';
  $format = 'json';
  $site_url = admin_url().'admin.php'; 
  $this->callback_url = '?page=dyb-zourite'; 

  return $OAUTH = new Oauth($site_url);

}

function info_user() {
  
  $info = file_get_contents(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/user.xml');

  return $this->xmlstring($info);
}

function info_cv($id = NULL) {

  if($id == NULL):

    $info = $this->info_user();

    if ($info['user']['premium'] == '1') :
      
        $id = $info['user']['resumes']['resume'][0]['id'];
  
    else :

       $id = $info['user']['resumes']['resume']['id'];

    endif;

  endif;  

    $cv = file_get_contents(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/'.$id.'_cv.xml');

    return $this->xmlstring($cv);

} 

function dyb_cron() {

   $OAUTH = $this->dyb_info_api();

   $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);

   $info = $OAUTH->request('http://api.doyoubuzz.com/user', array(), $this->token, $this->token_secret);

   file_put_contents(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/user.xml', $info);

   $info = $this->info_user();

   if ($info['user']['premium'] == '1') :
      
      foreach ($info['user']['resumes']['resume'] as $key => $value) :

        $id[] = $value['id'];

      endforeach;
    
    else :

      $id[] = $info['user']['resumes']['resume']['id'];

      
    endif;

    foreach ($id as $key => $value) :
          
        $cv = $OAUTH->request("http://api.doyoubuzz.com/cv/$value", array(), $this->token, $this->token_secret);

        file_put_contents(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/'.$value.'_cv.xml', $cv);

    endforeach;
  
}

function dyb_views() {
  
  session_start();

  $OAUTH = $this->dyb_info_api();

  if ($this->token == ''):

    if (isset($_GET['oauth_token'])):

      $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);
      $OAUTH->set_callback($this->callback_url);

      if(!isset($_SESSION['access_token'])) { 
        
        $token = $OAUTH->get_access_token($_GET['oauth_token'], $_GET['oauth_verifier'], $_SESSION['token_secret']);
        $_SESSION['access_token'] = $token['access_token'];
        $_SESSION['token_access_secret'] = $token['token_secret'];
      
      }

      update_option("token_dyb", $_SESSION['access_token']);
      update_option("token_dyb_secret", $_SESSION['token_access_secret']);
      
      $this->views_user();

    else :
    
      session_unset();

      $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);
      $OAUTH->set_callback($this->callback_url);
  
      $OAUTH->get_request_token();

      echo '<a href="'.$OAUTH->get_user_authorization().'">Se connecter</a>';
  
    endif;
  
  else :  

    $this->views_user();

  endif;
}


function views_user() {

  $this->dyb_cron();

  $info = $this->info_user();

  include 'view.php';

}

	function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
     $arrData = array();
    
    ///// if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    } //*/

    return $arrData;
}

function xmlstring($xmlStr){
	
	$xmlObj = simplexml_load_string($xmlStr);
		
	return $this->objectsIntoArray($xmlObj);
	
}

	
function dyb_skill($id = NULL){
	
	$arr = $this->info_cv($id) ;

  $competences = $arr['resume']['skills']['skill'];

     	foreach($competences as $compet) :
     	
     	 		echo '<p><strong>'.$compet['title'].'</strong></p>';
     	 	
     	 		echo '<ul>';
     	 		
     	 			foreach($compet['children'] as $value) :

            if(isset($value[0])):


              foreach($value as $listskill) :

              echo '<li>'.$listskill['title'].'</li>';

              endforeach;

            else : 
     	 		
     	 			  echo '<li>'.$value['title'].'</li>';
     	 		 
            endif;
   

     	 			endforeach;
     	 			
     	 		echo '</ul>';	
     	 		
     	 	endforeach;

}

	 function dyb_employment($id = NULL)
	 
	 {
	 
    global $wp_locale;

		include(dirname(__FILE__) .'/country.php');
		
    $arr = $this->info_cv($id);
		
    $employment = $arr['resume']['experiences']['experience'];
	
		foreach($employment as $value) : 

      $img = $value['logo'];

      ?>
     	 		      
   			<p>
   			<strong><?php echo $value['title'] ?></strong> chez <?php echo $value['company'] ?><br/>
<small> <?php echo date_i18n(get_option('date_format') ,strtotime($value['start']))  ?> au <?php echo date_i18n(get_option('date_format'), strtotime($value['end']))  ?> | <?php echo $value['city'] ?> - <?php echo $country[$value['country']['isoCode']]['name'] ?></small>
   			</p>

         <?php if(!empty($img)):?>

          <img style="float:right" src="http://doyoubuzz.com/<?php echo $img ?>" />

          <?php endif; ?>
 
     <ul>	 		
     	<?php 
     
     	foreach($value['missions']['mission'] as $mission) :
     
     	echo '<li>'.$mission['description'].'</li>';
     
    	endforeach; ?>
     </ul>
     <?php endforeach;
	
	}
		
	function dyb_intro($id = NULL)
	 
	 {

    $arr = $this->info_cv($id);
		
    $baselist = file_get_contents(WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/baseslist.xml');
    $baselist = $this->xmlstring($baselist);
                 
    $infoutcv = array( 'availability' => 'Disponibilité', 'seniority' =>'seniority','professionalStatus' => 'Status Professionnel');

    foreach($baselist['base'] as $value):

      foreach ($infoutcv as $key => $infocomp) :

        if ($value['title'] == $infocomp) :

            foreach($value['elements']['element'] as $element1):
            
              if($element1['id'] == $arr['resume'][$key]):

                  $specs[$infocomp] = $element1['title'];

              endif;  

            endforeach;  

        endif; 

      endforeach;

    endforeach;

    $translatespecs = array('seniority' => 'Experience Proféssionnelle', 'Status Professionnel' => 'Statut', 'Disponibilité' => 'Disponibilité');

    $summary = $arr['resume']['presentation']['text'];
	
	  echo '<p>'; 			
		
    echo $summary.'</p><p>';
    

    foreach ($specs as $key => $value) : ?>

   <strong><?php echo $translatespecs[$key] ?></strong> : <?php echo $value ?><br/>
      
  <?php  endforeach;
    
	 	echo '</p>';	
	}
	
	function dyb_contact($id = NULL)
	 
	 {
	 	
    $arr = $this->info_cv($id);

		$contact = $arr['resume']['links']['link'];
				
		foreach($contact as $value):
		

				$position = strpos($value['url'], 'www.' );
				$position2 = strlen($value['url']) - strpos($value['url'], '.' , $position+4);
				$service = substr($value['url'], 11, -$position2);
				
				if($position == FALSE) :
				
          $position =  strpos($value['url'], '.' );
				  $position2 = strlen($value['url']) - strpos($value['url'], '.' , $position);
				  $service = substr($value['url'], 7, -$position2);
				
        endif;
					
					if(!preg_match('/'.$service.'/', $_SERVER['HTTP_HOST'])):
						
            echo '<a href="'.$value['url'].'">'.'<img src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/img/'.$service.'.png" alt="'.$service.'.png"/></a> ';
					
          endif;

		
		endforeach;

	}
		
	function dyb_formation($id = NULL) {
		
		$arr = $this->info_cv($id) ;

		$formation = $arr['resume']['educations']['education'] ;
		
    echo '<ul>';
		  
      foreach ($formation as $value) :
			
       echo '<li><p><strong>'.$value['school'].' - ';
			 
       echo $value['degree'].'</strong></p>';

       echo '<p>'.$value['description'].'</p></li>';
		  
      endforeach;
		
		echo '</ul>';
	} 
	
}

$wp_dyb = new wp_dyb();	

function wp_dyb($section, $id = NULL) {

  global $wp_dyb;
  
  if($section == 'status'){
  	
  	return $wp_dyb->dyb_status($id);
  	
  }
  
  if($section == 'employment'){
  	
  	return $wp_dyb->dyb_employment($id);
  	
  }
 
  if($section == 'skill'){
  	
  	return $wp_dyb->dyb_skill($id);
  	
  }
  
   if($section == 'contact'){
  	
  	return $wp_dyb->dyb_contact($id);
  	
  }

   if($section == 'intro'){
  	
  	return $wp_dyb->dyb_intro($id);
  	
  }

    if($section == 'formation'){
  	
  	return $wp_dyb->dyb_formation($id);
  	
  }
    
  
}

?>
