<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController {
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show the login Page
     * 
     * @return void
     */
    public function login() {
        loadView('users/login');
    }

    /**
     * Show the register page
     * 
     * @return void
     */
    public function create() {
        loadView('users/create');
    }

    /**
     * Store user in database
     * 
     * @return void
     */
    public function store() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        $errors = [];

        // Validation 
        if(!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if(!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 character';
        }

        if(!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if(!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if(!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        } 
        // before insert the data into database, first check if the user is already there
        // Check if email exists
        $params = [
            'email' => $email
        ];

        $query = 'SELECT * FROM users WHERE email = :email';
        $user = $this->db->query($query, $params)->fetch();
        
        if($user) {
            $errors['email'] = 'That email already exists';
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        // Create user account
        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $query = 'INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password);';
        $this->db->query($query, $params);

        // Get New user ID
        $userId = $this->db->conn->lastInsertId();

        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);

        

        // redirect to listing
        redirect('/');
        
    }

    /**
     * Logout a user and kill session
     * 
     * @return void
     */
    public function logout() {
        Session::clearAll();

        $params = session_get_cookie_params();
        setCookie('PHPSESSID', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }

}