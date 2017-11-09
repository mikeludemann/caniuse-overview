<?php

//============================================================+
// File Name   : evaluation.php
// Begin       : 2014-08-01
// Last Update : 2017-10-25
// 
// Author: Mike Ludemann
// 
//============================================================+

//include("init.php");

echo "<html>
<head>
<title>Data Matching</title>";
include('include/meta.html');

echo '</head>
<body>';
        
    // JSON-String request and convert into a PHP-Array
    
    if(isset($_REQUEST['art'])){
        
        // Request - Variable on the Server
        $art = $_REQUEST['art'];
    
        // Get Source / API of "Can I Use"
        $url = "https://raw.githubusercontent.com/Fyrd/caniuse/master/features-json/$art.json"; 
        
        // Initialisation with curl
        $ch = curl_init(); 
        
        // curl - Set Parameter
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL verification
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return source
        curl_setopt($ch, CURLOPT_URL, $url); // Return URL

        // curl execution

        $result=curl_exec($ch);

        $json_array = json_decode($result, true);

        // Darstellung der Konvertierung des JSON-Array zu PHP

        $data = $json_array['stats'];
        $description = $json_array['description'];
        
        $mb   = array(); // Access to the value of browser - XML File
        $mbv  = array(); // Access to the value of Browser-Version - XML File
        $c_v  = array(); // Access to the value of Capability - XML File
        
        // Get XML - API and save it in a variable
        $config  = simplexml_load_file('files/config/config.xml');
        $capability = simplexml_load_file('files/capability.xml');
        
        // The DOM - Structur of the XML - API in a XPath and save it in a variable
        $browser    = $config->browser;
        $vs         = $config->version;
        $va         = $capability->variation;
        $browser2   = $capability->browser->name;
        
        // The XML - API in a XPath and converted it in a PHP-Array
        $browsername = json_decode(json_encode($browser), true); // Name of browser: XML-File as converted PHP-Array - Example: $array["name"][$i]["CDR"]
		$browserversion = json_decode(json_encode($vs), true); // Version of browser: XML-File as converted PHP-Array - Example: $array2["number"][$i]["CDR"]
		$capability_wert = json_decode(json_encode($va), true); // Value of capability: XML-File as converted PHP-Array - Example: $array3["wert"][$i]["CDR"]
		$capability_name = json_decode(json_encode($browser2), true); // Name of capability: XML-File as converted PHP-Array - Example: $array4["name"]["CDR"]
        
        // Mapping the name of capability from "Can I Use" with XML - API
        
        echo "<div class=\"row result\">
            <div class=\"col-sm-12 text-left\">
                Description of capability: <br/>" . $description. " <sup>3</sup>
            </div>
        </div>
        <hr class=\"evaluation\"></hr>";
        
        foreach($data as $mobile_browser => $version){
            
            // Mapping the name of browser from "Can I Use" with XML - API
            
            for($b = 0; $b<=count($browsername["name"]); $b++){
                        
                if($browsername["name"][$b]["CIU"] == (string)$mobile_browser){
                    
                    $mb = $browsername["name"][$b]["Translate"];
                    echo '<div class="row result">
			      <div class="col-sm-12 text-left">
				  <h4>
				      '. $mb .' <sup>1</sup>
				  </h4>
			      </div>
			  </div>';
                    
                } 
                
            }
                
                foreach($version as $mobile_browser_version => $cap){
                    
                    echo '<div class="row border-bottom" id="result">
                            <div class="col-xs-5 col-sm-3 pull-left text-right border-right">';
                    
                    
                    // Mapping the versions of browser from "Can I Use" with XML - API
                    
                    for($br_ver = 0; $br_ver<=count($browserversion["number"]); $br_ver++){
                        
                        if($browserversion["number"][$br_ver]["CIU"] == (string)$mobile_browser_version){

                            $mbv = $browserversion["number"][$br_ver]["Translate"];
                            echo $mbv . " <sup>2</sup>";
                            
                        } 
                        
                    }
                    
                            echo '</div>
                            <div class="col-xs-7 col-sm-9 pull-left text-center border-right">';
                    
                    // Mapping the value of capability from "Can I Use" with XML - API
                    
                    for($cp = 0; $cp<=count($capability_wert["wert"]); $cp++){
                        
                        if($capability_wert["wert"][$cp]["CIU"] == (string)$cap){
 
                            $c_v = $capability_wert["wert"][$cp]["Translate"];
                            echo $c_v . " <sup>4</sup>";
 
                        }
                        
                    }
                    
                        echo '</div>
                        </div>';
                    
            }
         
    }
    
        echo '<div class="row">
            <div class="col-sm-12 text-left">
                Legends: <br>
                1. Name of browser, <br>
                2. Version of browser, <br>
                3. Description of capability, <br>
                4. Value from Caniuse
            </div>
        </div>';
        
    }

    echo '</body>
</html>';


?>
