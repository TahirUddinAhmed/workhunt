<?php

namespace Framework;

use Framework\Database;
use PDO;
class Model {
    protected $db;
    protected $table;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Get the last insert id
     * 
     * @return int 
     */
    public function getLastInsertId() {
        return $this->db->conn->lastInsertId();
    }

    /**
     * Fetch all the data 
     * 
     * @return object
     */
    public function findAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC";
        
        return $this->db->query($query)->fetchAll();
    }

    /**
     * Fetch record by id 
     * 
     * @param int $id
     * @return object 
     */
    public function find($id) {
        $params = [
            'id' => $id
        ];
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = :id", $params)->fetch();
    }

    /**
     * Get table Columns
     *
     * @param string $table
     * @return object
     */
    public function getTableColumns($table) {
        return $this->db->query("SHOW COLUMNS FROM {$table}")->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Count number of records in a table
     *
     * @param int $id
     * @return object|bool
     */
    public function withCount($id) {
        $params = [
            'id' => $id
        ];
        return $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE id = :id", $params)->fetchColumn();
    }
    
    /**
     * Count number of records in a table
     *
     * @param array $id (associative)
     * @return string $table
     * @return object|bool
     */
    public function getCount($params, $table) {
        
        $fields = [];

        foreach($params as $field => $value) {
            $fields[] = "{$field} = :{$field}";
        }

        // convert array to string 
        $fields = implode("", $fields);

        // inspectAndDie($params);

        return $this->db->query("SELECT COUNT(*) FROM {$table} WHERE {$fields}", $params)->fetchColumn();
    }
    /**
     * Insert record into database table
     * 
     * @param array $data (associative array with key and value)
     * @return object
     */
    public function insert($data) {
        $fields = [];

        foreach($data as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode (',', $fields);

        $placeholder = [];

        foreach($data as $field => $value) {
           if($value === '') {
            $data[$field] = null;
           }

           $placeholder[] = ":{$field}";
        }

        // convert into string
        $placeholder = implode(',', $placeholder);

        $query = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";

        $this->db->query($query, $data);

        
    }

    /**
     * Update record in datable table 
     * 
     * @param int $id
     * @param array $updatedValues (associative array with fields and values)
     * @return object
     */
    public function update($id, $updatedValues) {
        $updatedFields = [];

        foreach($updatedValues as $field => $value) {
            $updatedFields[] = "{$field} = :{$field}";
        }

        // convert the array into string 
        $updatedFields = implode(',', $updatedFields);

        $query = "UPDATE {$this->table} SET {$updatedFields} WHERE id = :id";

        // make sure the updatedValues has id attribute 
        if(array_key_exists('id', $updatedValues)) {
            return $this->db->query($query, $updatedValues);
        } else {
            $updatedValues['id'] = $id;
            return $this->db->query($query, $updatedValues);
        }
    }

    /**
     * Delete record from database table 
     * 
     * @param int $id
     * @return object|null
     */
    public function delete($id) {
        $params = [
            'id' => $id
        ];

        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", $params);
    }
}