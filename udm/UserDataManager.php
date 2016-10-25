<?php

require_once 'RecursiveArrayAccess.php';
require_once 'QueryHandler.php';

class UserDataManager {

    /**
     * Instance of this class
     * @var UserDataManager
     */
    private static $instance;
    
    /**
     * Data from database
     * @var array
     */
    private $storage;
    
    /**
     * Delimiter which separates parts of path in incoming strings in set and get methods
     * @var string
     */
    private $delimiter;
    
    /**
     * Handles the connection and operations with database
     * @var QueryHandler
     */
    private $queryHandler;

    /**
     * @param int $userId
     * @throws PDOException
     */
    private function __construct() {
        $this->delimiter = '\\';

        $this->queryHandler = new QueryHandler();
    }

    /**
     * @param int $userId
     * @return UserDataManager instance
     * @throws PDOException
     * @throws Exception
     */
    public static function getInstance($userId) {

        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        $this->queryHandler->setUserId($userId);
        $this->storage = unserialize($this->queryHandler->getStorage());

        return static::$instance;
    }

    /**
     * Get data from $storage array specified by path
     * @param string $path
     * @return mixed $data
     * @throws PDOException
     * @throws Exception
     */
    public function get($path) {

        $path = $this->preparePath($path);

        $data = RecursiveArrayAccess::get($path, $this->storage);

        return $data;
    }

    /**
     * Set data in $storage array specified by path and update storage in database
     * @param string $path
     * @param mixed $data
     * @throws PDOException
     * @throws Exception
     */
    public function set($path, $data) {

        $path = $this->preparePath($path);
        /**
         * sanitize data?       
         * filter_var_array($data, FILTER_SANITIZE_SPECIAL_CHARS);?
         */

        $this->storage = RecursiveArrayAccess::set($path, $this->storage, $data);

        $this->queryHandler->updateStorage(serialize($this->storage));
    }

    /**
     * Sanitize the path and make an array from in
     * @param string $path
     * @return array 
     */
    private function preparePath($path) {
        $path = filter_var($path, FILTER_SANITIZE_STRING);
        return explode($this->delimiter, $path);
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

}
