<?php
/* 
 * Main class for handing User table operations.
 */
class User extends DbObject {
    // name of database table
    const DB_TABLE = 'user';

    // database fields
    protected $id;
    protected $fname;
    protected $lname;
    protected $uname;   //jagath: first name, last name, user name 
    protected $email;
    protected $password;
    protected $user_type;
    

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'fname' => '',
            'lname' => '',
            'uname' => '',  //jagath: first name, last name, user name 
            'email' => '',
            'password' => '',
            'user_type' => ''
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->fname = $args['fname'];
        $this->lname = $args['lname'];
        $this->uname = $args['uname'];  //jagath: first name, last name, user name 
        $this->email = $args['email'];
        $this->password = $args['password'];
        $this->user_type = $args['user_type'];
    }

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'fname' => $this->fname,
            'lname' => $this->lname,
            'uname' => $this->uname, //jagath: first name, last name, user name 
            'email' => $this->email,
            'password' => $this->password,
            'user_type' => $this->user_type
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

          
        
        //jagath: Deleted previous LoadByUsername where username = email, 
        //jagath: added Load by email ID
        public static function loadByEmail($email=null) {
        if($email === null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE email = '%s' ",
            self::DB_TABLE,
            $email
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }
    
    //jagath: Load by user name
    public static function loadByUsername($uname=null) {
        if($uname === null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE uname = '%s' ",
            self::DB_TABLE,
            $uname
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }
    
     // load all users
    public static function getAllUsers() {
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
    
    public static function getLastRowId(){
        $query = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1",
                self::DB_TABLE
                );
        $db = Db::instance();
        $result = $db->lookup($query);
        $row = mysql_fetch_assoc($result);
        return $row["id"];
    }
    
    

}