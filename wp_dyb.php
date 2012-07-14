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

setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

class wp_dyb {


 function __construct(){
 
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
}

function dyb_views() {

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	if ( array_key_exists('submit',$_POST) ):
			
			$this->save_link();
	
	else :
	 
	include 'view.php';
	
	endif;

}


 function save_link() {

	$fp = fopen(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/liens.txt', "w");
	
	fwrite($fp,$_POST['hr-xml']);
	
	fseek($fp, 0);
	
	echo "<p><strong>Liens sauvegarder avec succes</strong></p>";

	fclose($fp);
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

	function xmlUrl(){
	
	$fp = fopen(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/liens.txt', "r");
	$contents = fread($fp,filesize(WP_PLUGIN_DIR.'/'.basename(dirname(__FILE__)).'/liens.txt'));
	fclose($fp);
	
	$carac = array('oa:', '\oa:');	
	$xmlUrl = $contents; // XML feed file/URL
	$xmlStr = file_get_contents($xmlUrl);
	$xmlStr = str_replace ( $carac , '', $xmlStr);
	$xmlObj = simplexml_load_string($xmlStr);
		
	return $this->objectsIntoArray($xmlObj);
	
	}	
	
	function dyb_skill(){
	
	$arr = $this->xmlUrl();
	
	$competences = $arr['CandidateProfile']['PersonQualifications']['PersonCompetency'];

	$id = array();
    $cat = array();
     	 	
     	 	foreach ($competences as $compet): 
    
     	 		$position = strpos($compet['CompetencyID'], '_' );
     	 	
     	 			if ($position == FALSE  ) :
     	 	
     	 			$cat[$compet[CompetencyID]] .= $compet[CompetencyName];
     	 
     	 			else :
     	 	
     	 			$position = strlen($compet['CompetencyID']) -$position;
     	 
     	 			$id[$compet[CompetencyName]] .= substr($compet['CompetencyID'], 0, -$position);
    	
     	 			endif;
     	 
     	 	endforeach;  
     	 	
     	 	foreach($cat as $key=>$value) :
     	
     	 		echo '<p><strong>'.$value.'</strong></p>';
     	 	
     	 		echo '<ul>';
     	 		
     	 		$comp = array_keys($id,$key);
     	 		
     	 			foreach($comp as $value) :
     	 		
     	 			echo '<li>'.$value.'</li>';
     	 		
     	 			endforeach;
     	 			
     	 		echo '</ul>';	
     	 		
     	 	endforeach;
	
	
}

	 function dyb_employment()
	 
	 {
	
		include(dirname(__FILE__) .'/country.php');
		$arr = $this->xmlUrl();
		$employment = $arr['CandidateProfile']['EmploymentHistory']['EmployerHistory'];
	
		foreach($employment as $value) : ?>
     	 		
   			<p>
   			<strong><?php echo $value['PositionHistory']['PositionTitle'] ?></strong> chez <a href="<?php echo $value['InternetDomainName'] ?>"> <?php echo $value['OrganizationName']; ?></a><br/>
<small> <?php echo strftime ("%B-%Y",strtotime($value['EmploymentPeriod']['StartDate']['FormattedDateTime']))  ?> à <?php echo strftime ("%B-%Y",strtotime($value['EmploymentPeriod']['EndDate']['FormattedDateTime']))  ?> | <?php echo $value['PositionHistory']['PositionLocation']['ReferenceLocation']['CityName'] ?> - <?php echo $ar_countries[$value['PositionHistory']['PositionLocation']['ReferenceLocation']['CountryCode']] ?></small>
   			</p>
     <ul>	 		
     	<?php 
     		$desc = explode("\n", $value['PositionHistory']['Description']); 
     
     	foreach($desc as $value) :
     
     	echo '<li>'.$value.'</li>';
     
    	endforeach; ?>
     </ul>
     <?php endforeach;
	
	}
	
	function dyb_status() {
		
		$arr = $this->xmlUrl();
		$status = $arr['PositionSeekingStatusCode'];
		$img_active = '<img style="vertical-align : middle" src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/img/circle-green.png"/> ';
		
		if($status == 'Active') echo "Je suis à la recherche d'un poste";
		if($status == 'Passive') echo $img_active.'Ouvert aux opportunités';
		
	}
	
	function dyb_intro()
	 
	 {
	 	$arr = $this->xmlUrl();		
		$summary = $arr['CandidateProfile']['ExecutiveSummary'];
		$contact = $arr['CandidatePerson']['Communication'];
				
		echo $summary;
				
	}
	
	function dyb_contact()
	 
	 {
	 	$arr = $this->xmlUrl();		
		$contact = $arr['CandidatePerson']['Communication'];
				
		foreach($contact as $value):
		
			if($value['ChannelCode'] == 'Web'):
				
				$position = strpos($value['URI'], 'www.' );
				$position2 = strlen($value['URI']) - strpos($value['URI'], '.' , $position+4);
				$service = substr($value['URI'], 11, -$position2);
				
				if($position == FALSE) :
				$position =  strpos($value['URI'], '.' );
				$position2 = strlen($value['URI']) - strpos($value['URI'], '.' , $position);
				$service = substr($value['URI'], 7, -$position2);
				endif;
					
					if(!preg_match('/'.$service.'/', $_SERVER['HTTP_HOST'])):
						echo '<a href="'.$value['URI'].'">'.'<img src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/img/'.$service.'.png" alt="'.$service.'.png"/></a> ';
					endif;
			endif;
		
		endforeach;
		
		
	}
		
	function dyb_formation() {
		
		$arr = xmlUrl();
		$formation = $arr['CandidateProfile']['EducationHistory']['EducationOrganizationAttendance'] ;
		echo '<ul>';
		foreach ($formation as $value) :
			echo '<li>'.$value['OrganizationName'].' - ';
			echo $value['EducationDegree']['DegreeName'].'</li>';
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
    
  
}

?>
