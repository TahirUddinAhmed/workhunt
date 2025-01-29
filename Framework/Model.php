<?php

namespace Framework;

use Framework\Database;

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
     * Count number of records in a table
     *
     * @param int $id
     * @return object|bool
     */
    public function withCount($id) {
        $params = [
            'id' => $id
        ];
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = :id", $params)->fetchColumn();
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