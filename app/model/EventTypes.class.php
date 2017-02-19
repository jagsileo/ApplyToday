<?php

/* 
 * Main class for handing Suggested Jobs table operations.
 */
class EventTypes extends DbObject{
    //name of database table
    const DB_TABLE = "event_types";
    
    //database fields
    protected $id;
    protected $name;
    protected $description;
    
    // constructor
    public function __construct($args = array()){
        $defaultArgs = array(
          'id' => null,
          'name' => '',
          'description'=> ''
        );
        
        $args += $defaultArgs;
        
        $this->id = $args['id'];
        $this->name = $args['name'];
        $this->description = $args['description'];
    }
    
    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'name' => $this->name,
            'description' => $this->description
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }
    
     // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }
    
    // load all jobs
    public static function getAllEventTypes($limit=null) {
        $query = sprintf(" SELECT id FROM %s",
            self::DB_TABLE
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }
    
    //delete object by ID
    public static function deleteById($rowid){
        $db = Db::instance();
        $query = sprintf(" DELETE FROM %s WHERE id = $rowid",
            self::DB_TABLE
            );
        //echo $query;
        $db->execute($query);
    }
    
     //get event id by event name
    public static function getEventIdByName($name) {
        $query = sprintf("SELECT id FROM `%s` where name = '%s'",
            self::DB_TABLE, $name
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }
}
