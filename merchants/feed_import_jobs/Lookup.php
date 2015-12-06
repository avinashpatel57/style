<?php
abstract class Lookup{
    protected static $__resources = [];
    protected static $__table;

    private static function init(){

        $query = "SELECT name, id FROM " . static::$__table;
        $result = execute_query($query);

        for($i=0; $i<count($result); $i++){
            self::$__resources[$result[0]] = $result[1];
        }
    }

    public static function getId($name){
        if(count(self::$__resources) == 0){
            self::init();
        }

        //var_dump($name);
        //var_dump(static::$__resources[$name]);die;

        return isset(self::$__resources[$name]) ? self::$__resources[$name] : null;
    }

    public static function addResource($name){
        if(self::getId($name) != null){
            return self::getId($name);
        }

        $resource_exists_query = "SELECT name, id FROM " . static::$__table . " WHERE name='" . addslashes($name) . "'";
        $result = execute_query($resource_exists_query);

        //var_dump($result);die;

        if(mysql_num_rows($result) > 0){
            $row = mysql_fetch_assoc($result);
            self::$__resources[$row["name"]] = $row["id"];
            return $row["id"];
        }
        else {
            $resource_add_query = "INSERT INTO " . static::$__table . "(name) VALUES ('" . addslashes($name) . "')";
            $ins_result = execute_query($resource_add_query);

            if($ins_result){
                self::$__resources[$name] = last_insert_id();
                return self::getId($name);
            }
        }
        return '';
    }

}

Class Category extends Lookup{
    protected static $__table = 'categories';
}

Class Brand extends Lookup{
    protected static $__table = 'brands';
}
