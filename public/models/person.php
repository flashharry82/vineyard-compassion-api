<?php
require_once 'object.php';
class Person extends Object
{
  // property declaration
  public $id;
  public $first_name;
  public $last_name;
  public $dob;
  protected static $db_table_name = 'people';

  function Person($new_first_name=null, $new_last_name=null, $new_dob=null) {
    $this->first_name = $new_first_name;
    $this->last_name = $new_last_name;
    $this->dob = $new_dob;
  }

  // method declaration
  public function displayName() {
    echo $this->first_name.' '.$this->last_name;
  }
}
?>