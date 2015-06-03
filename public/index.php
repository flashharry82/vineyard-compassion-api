<?php

require '../vendor/autoload.php';

$app = new \Slim\Slim();

$app->post('/people/:id', function ($id) { 
  include 'controllers/people.php';
  if(authenticate()){
    person($id);
  }
  else{
    echo "No valid signature";
  }
});
$app->post('/people', function () {
  include 'controllers/people.php';
  if(authenticate()){
    people();
  }
  else{
    echo "No valid signature";
  }
});

$app->run();
?>
