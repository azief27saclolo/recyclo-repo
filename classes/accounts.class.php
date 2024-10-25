<?php

require_once 'database.php';

class Account{
    public $id = '';
    public $first_name = '';
    public $last_name = '';
    public $username = '';
    public $password = '';
    public $role = '';
    public $is_buyer = true;
    public $is_seller = false;
    public $is_admin = false;


    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function register(){
        $sql = "INSERT INTO account (first_name, last_name, username, password, role, is_buyer, is_seller, is_admin) 
                VALUES (:first_name, :last_name, :username, :password, :role, :is_buyer, :is_seller, :is_admin);";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':username', $this->username);
        $hashpassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashpassword);
        $query->bindParam(':role', $this->role);
        $query->bindParam(':is_buyer', $this->is_buyer);
        $query->bindParam(':is_seller', $this->is_seller);
        $query->bindParam(':is_admin', $this->is_admin);

        return $query->execute();
    }

    function login($username, $password){
        $sql = "SELECT * FROM account WHERE username = :username LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam('username', $username);

        if($query->execute()){
            $data = $query->fetch();
            if($data && password_verify($password, $data['password'])){
                return true;
            }
        }

        return false;
    }

    function fetchAccounts($search='',$role=''){
        $sql = "SELECT first_name,last_name,username,role FROM account 
                    WHERE (first_name LIKE '%' :search '%' OR last_name LIKE '%' :search '%' OR username LIKE '%' :search '%' OR role LIKE '%' :search '%') 
                    AND role LIKE '%' :role '%';";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':search', $search);
        $query->bindParam(':role', $role);
        $data = null;
        if($query->execute()){
            $data = $query->fetchAll();
        }

        return $data;
    }

    function usernameExist($username, $excludeID){
        $sql = "SELECT COUNT(*) FROM account WHERE username = :username";
        if ($excludeID){
            $sql .= " AND id != :excludeID";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':username', $username);

        if ($excludeID){
            $query->bindParam(':excludeID', $excludeID);
        }

        $count = $query->execute() ? $query->fetchColumn() : 0;

        return $count > 0;
    }
}