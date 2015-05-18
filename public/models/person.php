<?php
class Person
{
  // property declaration
  public $first_name;
  public $last_name;
  public $dob;


  function Person($new_first_name, $new_last_name, $new_dob) {
    $this->first_name = $new_first_name;
    $this->last_name = $new_last_name;
    $this->dob = $new_dob;
  }

  // method declaration
  public function displayName() {
    echo $this->first_name.' '.$this->last_name;
  }

  public function json() {
    return json_encode(get_object_vars($this));
  }

  public static function find($id){
    require_once 'assets/config.php';

    $person = new Person(null,null,null);

    $link = Db::open();
    if($id){
      $query = $link -> prepare("SELECT id, first_name, last_name, dob FROM people WHERE id = ?");
      $query -> bind_param('s', $id);
    }
    else{
      $query = $link -> prepare("SELECT id, first_name, last_name, dob FROM people LIMIT 1");
    }
    $query -> bind_result($person->id, $person->first_name, $person->last_name, $person->dob);
    $query -> execute();
    $query -> fetch();
    $query -> close();

    return $person;
  }

  function save(){
    require_once 'assets/config.php';

    $link = Db::open();
    $query = $link -> prepare("INSERT INTO people (first_name, last_name, dob) VALUES (?,?,?)");
    $query -> bind_param('sss', $this->first_name, $this->last_name, $this->dob);
    $success = $query -> execute();
    $this->id = $query->insert_id;
    $query -> close();

    return $success;
  }
}
?>