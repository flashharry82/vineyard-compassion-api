<?php
class Object{

  public static function class_properties(){
    $properties = array();
    $ref = new ReflectionClass(get_called_class());
    foreach($ref->getProperties(ReflectionProperty::IS_PUBLIC) as $property){
      array_push($properties,$property->getName());
    }
    return $properties;
  }

  public static function class_property_types(){
    $types = array();
    $ref = new ReflectionClass(get_called_class());
    foreach($ref->getProperties(ReflectionProperty::IS_PUBLIC) as $property){
      if($property->getName() != 'id'){
        array_push($types,substr(gettype($property->name),0,1));
      }
    }
    return $types;
  }

  public static function find($id=null){
    require_once "../functions/Db.php";

    $object = new static();

    $link = Db::open();
    if($id){
      $query = $link -> prepare("SELECT ".implode(", ", static::class_properties())." FROM ".static::$db_table_name." WHERE id = ?");
      $query -> bind_param('s', $id);
    }
    else{
      $query = $link -> prepare("SELECT ".implode(", ", static::class_properties())." FROM ".static::$db_table_name." LIMIT 1");
    }

    $query -> execute();
    $result = $query->get_result();
    $row = $result->fetch_array();

    foreach(static::class_properties() as $property){
      $object->$property = $row[$property];
    }

    $query -> close();

    return $object;
  }

  function save(){
    require_once 'assets/config.php';

    $link = Db::open();

    $properties = static::class_properties();
    $index = array_search('id', $properties);
    unset($properties[$index]);

    $value_points = array();
    foreach($properties as $property){
      array_push($value_points,'?');
    }
    $values_string = implode(",",$value_points);
    
    $a_params[] = & $param_type;

    $query = $link -> prepare("INSERT INTO ".static::$db_table_name." (".implode(", ", $properties).") VALUES (".$values_string.")");

    $values = get_object_vars($this);
    unset($values['id']);

    $params = array_merge(array(implode(static::class_property_types())), $values);
    call_user_func_array(array($query, 'bind_param'), static::refValues($params));

    $success = $query -> execute();
    $this->id = $query->insert_id;
    $query -> close();

    return $success;

  }


  public function json() {
    return json_encode(get_object_vars($this));
  }

  public static function refValues($arr){
    $refs = array();
    foreach($arr as $key => $value)
      $refs[$key] = &$arr[$key];
    return $refs;
  }

}

?>