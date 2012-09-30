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

require WP_PLUGIN_DIR.'/wp_dyb/lib/oauth.php';

class wp_dyb {


 function __construct(){
 
  $this->token = get_option( 'token_dyb', 'FALSE' ); 
  $this->token_secret = get_option( 'token_secret', 'FALSE' );

	add_action('admin_menu', array(&$this,'dyb_menu'));
	
	wp_register_sidebar_widget(
    	
    	'wp_dyb_status',        // your unique widget id
    	'Statut DoYouBuzz',          // widget name
    	array(&$this,'dyb_status'),  // callback function
    	array(                  // options
        'description' => "Affiche la disponibilité pour un poste ou des opportunités"
    	
    	));

	wp_register_sidebar_widget(
    	
    	'wp_dyb_skill',        // your unique widget id
    	'Compétences DoYouBuzz',          // widget name
    	array(&$this,'dyb_skill'),  // callback function
    	array( 
    	                 // options
        'description' => "Affiche la liste des compétences de votre CV"
    	
    	));

	wp_register_sidebar_widget(
    	
    	'wp_dyb_employment',        // your unique widget id
    	'Experience DoYouBuzz',          // widget name
    	array(&$this,'dyb_employment'),  // callback function
    	array(  
    	                // options
        'description' => "Affiche vos experiences professionnelle"
    	
    	));

  }


function dyb_menu() {
	
	add_menu_page( 'Dyb Options', 'WP_DYB', 'manage_options', 'dyb-zourite',  array(&$this,'dyb_views'),plugins_url('wp_dyb/img/doyoubuzz_16.png') );
  add_option("token_dyb", $_SESSION['access_token']);
  add_option("token_secret", $_SESSION['token_access_secret']);

}

function dyb_info_api() {

  $this->key = 'ZK8Pkir-htOxEKgy7x8O';
  $this->secret = 'WnIiPN6Z3t7EnHCjwTY_uZG6f';
  $format = 'json';
  $site_url = admin_url().'admin.php'; // Your site url (example : http://sandbox.local/dyb/)
  $this->callback_url = '?page=dyb-zourite'; // Your relative callback URL

  return $OAUTH = new Oauth($site_url);

}

function dyb_views() {

  session_start();
  
  $OAUTH = $this->dyb_info_api();

  if ($this->token == ''):

    if (isset($_GET['oauth_token'])):

      $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);
      //$OAUTH->set_callback($callback_url);

      if(!isset($_SESSION['access_token'])) { 
      $token = $OAUTH->get_access_token($_GET['oauth_token'], $_GET['oauth_verifier'], $_SESSION['token_secret']);
      $_SESSION['access_token'] = $token['access_token'];
      $_SESSION['token_access_secret'] = $token['token_secret'];
      }

      update_option("token_dyb", $_SESSION['access_token']);
      update_option("token_secret", $_SESSION['token_access_secret']);
      
      $this->views_user();

    else :

      session_unset();
    
      $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);
      $OAUTH->set_callback($this->callback_url);
  
      $OAUTH->get_request_token();

      echo '<a href="'.$OAUTH->get_user_authorization().'">test</a>';
  
    endif;
  
  else :  

    $this->views_user();

  endif;
}

function info_user() {

   $OAUTH = $this->dyb_info_api();

   $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);

	 $info = $OAUTH->request('http://api.doyoubuzz.com/user', array(), $this->token, $this->token_secret);

   return $info = $this->xmlstring($info);
  
}


function views_user() {

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

function info_cv() {

    $OAUTH = $this->dyb_info_api();

    $info = $this->info_user();

    $OAUTH->set_site("http://www.doyoubuzz.com/fr/", $this->key, $this->secret);
    
    if ($info['user']['premium'] == '1') :
  
      $id = $xml['user']['resumes']['resume'][0]['id'];
    
    else :

      $id = $info['user']['resumes']['resume']['id'];

    endif;
  

    $cv = $OAUTH->request("http://api.doyoubuzz.com/cv/$id", array(), $this->token, $this->token_secret);

    $cv = $this->xmlstring($cv);

    return $cv;

}	
	
function dyb_skill(){
	
	$arr = $this->info_cv() ;

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

	 function dyb_employment()
	 
	 {
	 
    global $wp_locale;
		include(dirname(__FILE__) .'/country.php');
		$arr = $this->info_cv();
		
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
     		//$desc = explode("\n", $value['PositionHistory']['Description']); 
     
     	foreach($value['missions']['mission'] as $mission) :
     
     	echo '<li>'.$mission['description'].'</li>';
     
    	endforeach; ?>
     </ul>
     <?php endforeach;
	
	}
	
	function dyb_status() {
		
		$arr = $this->info_cv();

                      //
    echo "<pre>";
    print_r($arr['resume']['availability']);
    echo "</pre>";
   
    //*/
		$status = $arr['PositionSeekingStatusCode'];
		$img_active = '<img style="vertical-align : middle" src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/img/circle-green.png"/> ';
		
		if($status == 'Active') echo "Je suis à la recherche d'un poste";
		if($status == 'Passive') echo $img_active.'Ouvert aux opportunités';
		
	}
	
	function dyb_intro()
	 
	 {

    $arr = $this->info_cv();
		

    $summary = $arr['resume']['presentation']['text'];
	
				
		echo $summary;
				
	}
	
	function dyb_contact()
	 
	 {
	 	
    $arr = $this->info_cv();

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
		
	function dyb_formation() {
		
		$arr = $this->info_cv() ;

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

function wp_dyb($section) {

  global $wp_dyb;
  
  if($section == 'status'){
  	
  	return $wp_dyb->dyb_status();
  	
  }
  
  if($section == 'employment'){
  	
  	return $wp_dyb->dyb_employment();
  	
  }
 
  if($section == 'skill'){
  	
  	return $wp_dyb->dyb_skill();
  	
  }
  
   if($section == 'contact'){
  	
  	return $wp_dyb->dyb_contact();
  	
  }

   if($section == 'intro'){
  	
  	return $wp_dyb->dyb_intro();
  	
  }

    if($section == 'formation'){
  	
  	return $wp_dyb->dyb_formation();
  	
  }
    
  
}

?>
