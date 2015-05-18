<?php
class Person
{
  // property declaration
  public $first_name;
  public $last_name;
  public $dob;

  // method declaration
  public function displayName() {
    echo $this->first_name.' '.$this->last_name;
  }

  public function json() {
    return json_encode(get_object_vars($this));
  }

  function set_first_name($new_first_name) {
    $this->name = $new_first_name;
  }

  function get_first_name() {
    return $this->first_name;
  }

  public static function find($id){
    require 'assets/config.php';

    $person = new Person();

    $link = Db::open();
    $query = $link -> prepare("SELECT id, first_name, last_name, dob FROM people WHERE id = ?");
    $query -> bind_param('s', $id);
    $query -> bind_result($person->id, $person->first_name, $person->last_name, $person->dob);
    $query -> execute();
    $query -> fetch();
    $query -> close();

    return $person;
  }
}
?>