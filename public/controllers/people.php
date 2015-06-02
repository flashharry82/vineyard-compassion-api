<?php

if(authenticate()){
  $page = str_replace('.php','',str_replace('/','',$_SERVER["PHP_SELF"]));
  switch ($page){
    case 'people': people(); break;
    case 'person': person($_GET['id']); break;
  }
}
else{
  echo "No valid signature";
}


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
}

function person($id){
  echo Person::find($id);
}



?>

