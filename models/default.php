<?php

    class Users {
        /* DB Data */
        private $conn;
        private $table = '';

        public $id;
        public $collun;

        public function __construct($db) {
            $this->conn = $db;
        }

        /** 
         * Get all data
        */
        public function index() {
            $query = 'SELECT * FROM ' . $this->table;
            return $this->conn->query($query)->fetchAll();
        }

        /** 
         * Get specific data
        */
        public function show() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $this->id;
            return $this->conn->query($query)->fetch();
        }

        /** 
         * Create a new entry
        */
        public function store() {
            $query = 'INSERT INTO ' . $this->table . ' (collun) VALUES ("' . $this->collun . '")';
            try {
                $this->conn->prepare($query)->execute();
            
                $this->id = $this->conn->lastInsertId();
                return $this->show();
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        /** 
         * Update an entry
        */
        public function update() {
            $query = 'UPDATE tokens SET collun = "' . $this->collun . '" WHERE id = ' . $this->id;
            try {
                $this->conn->prepare($query)->execute();

                return $this->show();
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        /** 
         * Delete an entry
        */
        public function destroy() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = ' . $this->id;
            try {
                $this->conn->prepare($query)->execute();
                return ['message' => 'Successfully deleted'];
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }
    }
?>