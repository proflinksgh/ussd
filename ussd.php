<?php 

  $sessionId = $_POST['sessionId'];
  $serviceCode = $_POST['serviceCode'];  
  $phoneNumber = $_POST['phoneNumber'];
  $text = $_POST['text']; 

//          $sessionId = $req->get('sessionId');
//          $serviceCode = $req->get('serviceCode');
//          $phoneNumber = $req->get('phoneNumber');
//          $text = $req->get('text');
         
          if($text == ""){
      	$response = "CON Welcome to Barry Financial Services\n\n";
      	$response .= "Please select option:\n";
      	$response .= "1. Open account\n";
      	$response .= "2. Make deposit\n";
      	$response .= "3. Make withdrawal\n";
        $response .= "4. View branches\n";
      }else if($text == "1"){      
      	$response = "CON Open an account\n\n";
      	$response .= "Please select account:\n";
      	$response .= "1. Savings account\n";
      	$response .= "2. Current account\n";
      	$response .= "3. Fixed deposit\n";
      	$response .= "99. Back\n";
      }  else if($text == "99"){
      	$response = "CON Enter full name:\n\n";
      	$response .= "98. Back\n";
      }

?>
