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

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
new wp_dyb;

class wp_dyb {


 function __construct(){
 
 	
	add_action('admin_menu', array(&$this,'dyb_menu'));
 	
     }

function dyb_menu() {
	
	add_menu_page( 'Dyb Options', 'Dyb', 'manage_options', 'dyb-zourite',  array(&$this,'dyb_views'),'' );
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
	$fp = fopen('toto.txt', "w");
	fwrite($fp, '1');
	fclose($fp);
	//echo 'toto';
	print_r($fp);
	
}

	function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

	function xmlUrl(){
	
	$carac = array('oa:', '\oa:');	
	$xmlUrl = "http://www.doyoubuzz.com/mickael-oulia/cv/export?format=hr-xml&key=AUpsZZwZfyMLpEfRyPsw"; // XML feed file/URL
	$xmlStr = file_get_contents($xmlUrl);
	$xmlStr = str_replace ( $carac , '', $xmlStr);
	$xmlObj = simplexml_load_string($xmlStr);
	
	return $arrXml = objectsIntoArray($xmlObj);
	
	}	
	
	function dyb_skill(){
	
	$arr = xmlUrl();
	
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
     	
     	 		echo $value.'</br>';
     	 	
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
		$arr = xmlUrl();
		$employment = $arr['CandidateProfile']['EmploymentHistory']['EmployerHistory'];
	
		foreach($employment as $value) : ?>
     	 		
   			<p>
   			<?php echo $value['PositionHistory']['PositionTitle'] ?> chez <a href="<?php echo $value['InternetDomainName'] ?>"> <?php echo $value['OrganizationName']; ?></a></br>
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
	
		 function dyb_intro()
	 
	 {
	
		$arr = xmlUrl();
		$status = $arr['PositionSeekingStatusCode'];
		$summary = $arr['CandidateProfile']['ExecutiveSummary'];
		$contact = $arr['CandidatePerson']['Communication'];
		echo $summary.$status;
		
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
				
		
		
		
		echo $service.'<br/>';
		echo $position.'1<br/>';
		echo $position2.'2<br/>';
		
			endif;
		
		endforeach;
		
		echo '<pre>';
		print_r($contact);
		echo '</pre>';

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
?>