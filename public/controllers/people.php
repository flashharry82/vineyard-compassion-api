<?php

function authenticate(){

  if(isset($_POST['signature']) && isset($_POST['public_key'])){
    $data = $_POST['data'];
    $received_signature = $_POST['signature'];
    $public_key = $_POST['public_key'];

    $retrieved_key = ApiKey::get_private_key($public_key);

    $decoded_signature = urldecode($received_signature);

    $computed_signature = base64_encode(hash_hmac("sha256", $data, $retrieved_key, TRUE));

    if(hash_equals($decoded_signature,$computed_signature)){
      return true;
    }
    else{
      return false;
    }
  }
  else{
    return false;
  }
}

function people(){
  echo Person::all();
  #include 'views/people/index.php';
}

function person($id){
  echo Person::find($id);
}



?>

