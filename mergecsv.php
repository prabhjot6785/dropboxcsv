<?php

ini_set('display_errors', 1);

require_once "dropbox/Dropbox/autoload.php";

use \Dropbox as dbx;

	$dropbox_config = array(
		'key'    => 'c8jlvwm238snfvt',
		'secret' => 'ij8qwmb3schujpr'
	);

	/*$appInfo = dbx\AppInfo::loadFromJson($dropbox_config);
	$webAuth = new dbx\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

	$authCode = trim('4DK3_Q9x-YAAAAAAAAAANH7Nbq9GAa42leQ3914ju_8');

	$authorizeUrl = $webAuth->start();
	echo "1. Go to: " . $authorizeUrl . "<br>";
	echo "2. Click \"Allow\" (you might have to log in first).<br>";
	echo "3. Copy the authorization code and insert it into $authCode.<br>";

	list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
	echo "Access Token: " . $accessToken . "<br>";*/

    $accessToken ='4DK3_Q9x-YAAAAAAAAAANZ5B_dVR1tKG78PDXJR4WTWXdCmo0vTSi_enWGrupgSW';
    $dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
   
   
   /****** Download csv file from dropbox account ***********/
   
    $fa = fopen("data-a.csv", "w+b");
	$fileMetadata_a = $dbxClient->getFile("/data-a.csv", $fa);
	fclose($fa);
	print_r($fileMetadata_a);

	$fb = fopen("data-b.csv", "w+b");
	$fileMetadata_b = $dbxClient->getFile("/data-b.csv", $fb);
	fclose($fb);
	print_r($fileMetadata_b);


    /****** Get csv data from data-a.csv file ***********/
    
	if (false !== ($ih = fopen('data-a.csv', 'r'))) {
		
		while (false !== ($data = fgetcsv($ih))) {
			$csvDataA[] = array($data[0], $data[2], $data[4], $data[5]);
		}
	}
    
    /****** Get csv data from data-b.csv file ***********/
    
	if (false !== ($ih = fopen('data-b.csv', 'r'))) {
		
		while (false !== ($data = fgetcsv($ih))) {
			$csvDataB[] = array($data[0], $data[2], $data[3]);
		}
	}

    /****** Merge Data *********/
    
	$final_csv = array();
	foreach ($csvDataB as $key => $value){
		$final_csv[] = array_merge((array)$csvDataA[$key], (array)$value);
	}

    /****** Create new csv after merge csv data *******/
    
	$fp = fopen('result.csv', 'w');

	foreach ($final_csv as $fields) {
		fputcsv($fp, $fields);
	}

	fclose($fp);
	
	
	// Uploading the file
	$f = fopen("result.csv", "rb");
	$result = $dbxClient->uploadFile("/result.csv", dbx\WriteMode::add(), $f);
	fclose($f);
	print_r($result);
	
?>
