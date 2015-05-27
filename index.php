<?php
include 'controllers/people.php';

echo 'Hello ';
echo $person->displayName();
echo "<br />";
echo $person->json();

?>