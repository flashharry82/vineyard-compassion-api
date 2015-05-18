<?php
include 'models/person.php';

$person1 = Person::find(1);
echo 'Hello ';
echo $person1->displayName();
echo "<br />";
echo $person1->json();

?>