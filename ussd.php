<?php 

  $text = $_POST['text']; 

  if(!isset($text)){
  	$response = "Welcome to Links engineering"."\n\n"."Select option:"."\n"."1. Make payment"."\n"."2. Check status";
  }

  return $text;

<?