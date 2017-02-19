<?php

/* 
 * Main class for handing application table operations.
 */

class Applications extends DbObject{
    const DB_TABLE = "applications";
    const COL1 = "id", COL2 = "creator_id", COL3 = "company_name", COL4 = "position",
          COL5 = "job_url", COL6 = "applied_date", COL7 = "resume_version", COL8 = "contact", 
          COL9 = "status", COL10 = "created_date";
    
    //database fields
    protected $id;
    protected $creator_id;
    protected $company_name;
    protected $position;
    protected $job_url;
    protected $applied_date;
    protected $resume_version;
    protected $contact;
    protected $status;
    
    public function __construct($args = array()){
        $defaultArgs = array(
           self::COL1 => null,
           self::COL2 => '',
           self::COL3 => '',
           self::COL4 => '',
           self::COL5 => '',
           self::COL6 => '',
           self::COL7 => '',
           self::COL8 => '',
           self::COL9 => '',
        );
        
        $args += $defaultArgs;
        
        $this->id = $args[self::COL1];
        $this->creator_id = $args[self::COL2];
        $this->company_name = $args[self::COL3];
        $this->position = $args[self::COL4];
        $this->job_url = $args[self::COL5];
        $this->applied_date = $args[self::COL6];
        $this->resume_version = $args[self::COL7];
        $this->contact = $args[self::COL8];
        $this->status = $args[self::COL9];    
    }
    
    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            self::COL2 => $this->creator_id,
            self::COL3 => $this->company_name,
            self::COL4 => $this->position,
            self::COL5 => $this->job_url,
            self::COL6 => $this->applied_date,
            self::COL7 => $this->resume_version,
            self::COL8 => $this->contact,
            self::COL9 => $this->status
            );
        //print_r($db_properties);
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }
    
     // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }
    
    // load all apps
    public static function getAllApps($limit=null, $creator_id, $start_from=0) {
        $query = sprintf(" SELECT id FROM %s WHERE CREATOR_ID = '%s' LIMIT $start_from, $limit",
            self::DB_TABLE,
            $creator_id    
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
    
    //return number of rows
    public static function getNumOfRows($creator_id){
        $query = sprintf("SELECT COUNT(*) AS ROWS FROM %s WHERE CREATOR_ID = '%s'",
                self::DB_TABLE,
                $creator_id
                );
        $db = Db::instance();
        $result = $db->lookup($query);
        $row = mysql_fetch_assoc($result);
        return $row["ROWS"];
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

    //getting last RowID
    public static function getLastRowId($creator_id){
        $query = sprintf("SELECT id FROM %s WHERE CREATOR_ID = '%s' ORDER BY id DESC LIMIT 1",
                self::DB_TABLE,
                $creator_id
                );
        $db = Db::instance();
        $result = $db->lookup($query);
        $row = mysql_fetch_assoc($result);
        return $row["id"];
    }	
    
}