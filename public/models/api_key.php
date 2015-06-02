<?php
require_once 'object.php';
class ApiKey extends Object
{
  // property declaration
  public $id;
  public $public;
  public $private;
  protected static $db_table_name = 'api_keys';

  public static function get_private_key($public_key){

    $object = new static();

    $link = Db::open();
    if($public_key){
      $query = $link -> prepare("SELECT ".implode(", ", static::class_properties())." FROM ".static::$db_table_name." WHERE public = ?");
      $query -> bind_param('s', $public_key);
    

      $query -> execute();
      $result = $query->get_result();
      $row = $result->fetch_array();

      foreach(static::class_properties() as $property){
        $object->$property = $row[$property];
      }

      $query -> close();
    }

    return $object->private;
  }
}
?>