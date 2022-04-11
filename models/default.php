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

        public function index() {
            $query = 'SELECT * FROM ' . $this->table;
            return $this->conn->query($query)->fetchAll();
        }

        public function show() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $this->id;
            return $this->conn->query($query)->fetch();
        }

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

        public function update() {
            $query = 'UPDATE tokens SET collun = "' . $this->collun . '" WHERE id = ' . $this->id;
            try {
                $this->conn->prepare($query)->execute();

                return $this->show();
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

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