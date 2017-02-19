<?php

/* 
 * Main class for handing Suggested Jobs table operations.
 */
class SuggestedJobs extends DbObject{
    //name of database table
    const DB_TABLE = "suggested_jobs";
    
    //database fields
    protected $id;
    protected $title;
    protected $location;
    protected $job_desc;
    protected $summary;
    protected $img_url;
    protected $link_url;
    
    // constructor
    public function __construct($args = array()){
        $defaultArgs = array(
          'id' => null,
          'title' => '',
          'location'=> '',
          'job_desc'=> '',
          'summary'=> '',
          'img_url'=>'default.png', //a default image from the local storage
          'link_url'=>''
        );
        
        $args += $defaultArgs;
        
        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->location = $args['location'];
        $this->job_desc = $args['job_desc'];
        $this->summary = $args['summary'];
        $this->img_url = $args['img_url'];
        $this->link_url = $args['link_url'];
    }
    
    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'title' => $this->title,
            'location' => $this->location,
            'job_desc' => $this->job_desc,
            'summary' => $this->summary,
            'img_url' => $this->img_url,
            'link_url' => $this->link_url
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
    public static function getAllJobs($limit=null) {
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
}
