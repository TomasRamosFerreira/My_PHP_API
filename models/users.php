<?php

    class Users {
        /* DB Data */
        private $conn;
        private $table = 'users';

        public $idUser;
        public $user;
        public $email;
        public $pass;
        public $token;
        public $isLoggedIn = false;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function login() {
            $query = 'SELECT id, name, email FROM ' . $this->table . ' WHERE email = "' . $this->email . '" AND password = "' . sha1($this->pass) . '" LIMIT 1';
            $data = $this->conn->query($query)->fetch();

            if ($data != null) {
                $this->idUser = $data['id'];
                return $this->updateSession();
            }
            
            return [
                'status' => 'error',
                'message' => 'User or pass incorrect.',
                'isLoggedIn' => $this->isLoggedIn
            ];
        }

        public function updateSession() {
            $query = 'UPDATE tokens SET expired_at = "' . strtotime("+1 month") . '"  WHERE idUserFK = ' . $this->idUser;
            try {
                $this->conn->prepare($query)->execute();

                $query = 'SELECT token, created_at, expired_at FROM tokens WHERE idUserFK = ' . $this->idUser . ' LIMIT 1';
                return $this->conn->query($query)->fetch();
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => 'Error updating token: ' . $e->getMessage()
                ];
                return $response;
            }
        }

        public function getToken($id) {
            $query = 'SELECT token, created_at, expired_at FROM tokens WHERE id = ' . $id;
            return $this->conn->query($query)->fetch();
        }

        public function createAccount() {
            $query = 'INSERT INTO users (name, email, password) VALUES ("' . $this->user . '", "' . $this->email . '", "' . sha1($this->pass) . '")';
            try {
                $this->conn->prepare($query)->execute();
                
                $query = 'INSERT INTO tokens (token, idUserFK, expired_at) VALUES ("' . bin2hex(openssl_random_pseudo_bytes(50)) . '", "' . $this->conn->lastInsertId() . '", "' . strtotime("+1 month") . '")';
                try {
                    $this->conn->prepare($query)->execute();
                    $last_id = $this->conn->lastInsertId();
                    $this->getToken($last_id);
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Error creating token: ' . $e->getMessage()
                    ];
                    return $response;
                }
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => 'Error creating user: ' . $e->getMessage()
                ];
                return $response;
            }
        }

        public function logout() {
            $query = 'UPDATE tokens SET expired_at = "' . strtotime("now") . '" WHERE token = "' . $this->token . '"';
            try {
                $this->conn->prepare($query)->execute();

                $response = [
                    'status' => 'sucess',
                    'message' => 'Logged out'
                ];
                return $response;
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => 'Error updating token: ' . $e->getMessage()
                ];
                return $response;
            }
        }

        public function isLoggedIn() {
            $query = 'SELECT * FROM tokens WHERE token = "' . $this->token . ' AND expired_at != ' . strtotime('now') . '" LIMIT 1';
            $data = $this->conn->query($query)->fetch();

            if ($data != null)
                $this->isLoggedIn = true;
        }

        public function getUserId() {
            $query = 'SELECT users.id FROM ' . $this->table . ' INNER JOIN tokens ON tokens.idUserFK = ' . $this->tabel . '.id WHERE tokens.token = "' . $this->token . '" LIMIT 1';
            $data = $this->conn->query($query)->fetch();

            if ($data != null)
                return $data['idUser'];
            return null;
        }
    }
