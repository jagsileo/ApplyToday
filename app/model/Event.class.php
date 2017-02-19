<?php

/* 
 * Main class for handing Event table operations.
 */
class Event extends DbObject{
    //name of database table
    const DB_TABLE = "events";
    
    //database fields
    protected $id;
	protected $user;
    protected $event;
    protected $event_type;
    protected $job;
	
    // constructor
    public function __construct($args = array()){
        $defaultArgs = array(
          'id' => null,
          'user' => '',
		  'event' => '',
          'event_type'=> '',
          'job'=> '',
		  'timestamp'=>''
        );
        
        $args += $defaultArgs;
        
        $this->id = $args['id'];
        $this->user = $args['user'];
		$this->event = $args['event'];
        $this->event_type = $args['event_type'];
        $this->job = $args['job'];
		$this->timestamp = $args['timestamp'];
    }
    
    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'user' => $this->user,
            'event_type' => $this->event_type,
            'job' => $this->job
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }
    
     // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }
    
    // load all events
    public static function getAllEvents($limit=null) {
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
	
	// load all events
    public static function getEventByUserAndJob($limit=null, $usr, $event, $jb) {
        $query = sprintf(" SELECT id FROM %s where user = '%s' and event_type = '%s' and job = '%s'",
            self::DB_TABLE, $usr, $event, $jb
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
	
	// load all friends' events
    public static function getEventsOfFriends($limit=null, $types, $user_id) {
        $query = sprintf(" select events.id, user.email, event_types.name, suggested_jobs.title, events.timestamp FROM %s, `user`, `suggested_jobs`, `event_types` where events.user = user.id and events.job = suggested_jobs.id and events.event_type = event_types.id and events.event_type in %s and events.job in (SELECT events.job FROM %s, `user`, `suggested_jobs`, `event_types` WHERE events.user = user.id and events.job = suggested_jobs.id and events.event_type = event_types.id and user = %s or 2) order by events.timestamp desc",
            self::DB_TABLE, $types, self::DB_TABLE, $user_id
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
	
	// load all events for a given user id
    public static function getAllEventsById($id) {
        $query = sprintf("SELECT id FROM `%s` where user = %d order by events.timestamp desc",
            self::DB_TABLE, $id
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
        $query = sprintf(" DELETE FROM %s WHERE id = %s",
            self::DB_TABLE, $rowid
            );
        //echo $query;
        $db->execute($query);
    }
    
   //load all events for a given Event Id
    public static function getAllEventsByEventId($id) {
        $query = sprintf("SELECT id FROM `%s` where event_type = %d",
            self::DB_TABLE, $id
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
