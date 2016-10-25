<?php

class QueryHandler {

    /**
     * @var int
     */
    private $userId;
    
    /**
     * Instance of PDO class
     * @var PDO
     */
    private $db;
    
    /**
     * Information for connection to database
     * @var array
     */
    private $dbInfo = [
        'db_host' => 'localhost',
        'db_name' => 'jobtask1',
        'db_username' => '',
        'db_pass' => ''
    ];

    public function __construct() {        

        $this->connect();
    }
    
    /**
     * @param int $userId
     */
    public function setUserId($userId) {
        $this->userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);    
    }

    /**
     * Get data field from db by user_id field
     * @return string Serialized array
     * @throws PDOException
     * @throws Exception
     */
    public function getStorage() {

        $query = "SELECT data FROM users WHERE user_id = :user_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue('user_id', $this->userId);
        if(!$statement->execute()) {
            throw new Exception('Select query has failed');
        }

        //decrypt
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if (count($result) == 1) {
            $result = array_shift($result);
        } else {
            throw new Exception('Incorrect data');
        }
        
        return $result;
    }
    
    /**
     * Updates the data field with $serializedData in row specified by user_id var
     * @param string $serializedData
     * @throws Exception
     */
    public function updateStorage($serializedData) {
        
        //crypt
        
        $query = "UPDATE users SET data = :data WHERE user_id = :user_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue('data', $serializedData);
        $statement->bindValue('user_id', $this->userId);
        
        if (!$statement->execute()) {
            throw new Exception('Update query has failed');
        }        
    }
    
    /**
     * Connects to DB
     * @throws PDOException
     */
    private function connect() {

        $dsn = "mysql:host=" . $this->dbInfo['db_host'] .
                ";dbname=" . $this->dbInfo['db_name'];

        $this->db = new PDO($dsn, $this->dbInfo['db_username'], $this->dbInfo['db_pass']);

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

}
