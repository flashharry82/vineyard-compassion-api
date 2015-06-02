<h1>Need authentication</h1>
<br />
People Feed -> /people.php

<?php

$person = Person::find();

if(!isset($person)){
  $new_person = new Person('Joe', 'Bloggs');
  if ($new_person->save()){
    $person = $new_person;
  }
  else{
    echo '<br />Error saving person';
  }
}

?>