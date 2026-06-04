<?php

class User {
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    

    public function __construct($name, $email, $password, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getName() { return $this->name; }
}