<?php 

  $sessionId = $_POST['sessionId'];
  $serviceCode = $_POST['serviceCode'];  
  $phoneNumber = $_POST['phoneNumber'];
  $text = $_POST['text']; 


  if($text == ""){
  	$response = 'CON Welcome to Accra Institute of Technology \n\n';
  	$response .= 'Select option:\n';
  	$response .= '1. Apply Admission\n';
  	$response .= '2. View courses\n';
  	$response .= '3. Pay fees\n';

  }

  echo $response;

?>
