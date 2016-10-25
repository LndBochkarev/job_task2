<?php

class RecursiveArrayAccess {
    
    
    /**
     * Takes the value of array specified by path and returns it
     * @param array $path
     * @param type $data
     * @return mixed 
     * @throws Exception when data for given key is not found
     */
    public static function get($path, $data) {
        $part = array_shift($path);
        
        if (count($path) == 0) {
            return $data[$part];
        }        
        
        if (!isset($data[$part])) {
            throw new Exception('There is no data for determined key');
        }
        
        return self::get($path, $data[$part]);
    }
    
    /**
     * Sets the value of array specified by path and returns the array
     * @param array $path
     * @param mixed $value Value to set     
     * @return array $data Array with changed value of cell with given path
     */
    
    public static function set($path, $data, $value) {
        $part = array_shift($path);
        
        if(count($path) == 0) {
            $data[$part] = $value;
            return $data;
        }
        
        $data[$part] = self::set($path, $data[$part], $value);    
        
        return $data;
    }
}