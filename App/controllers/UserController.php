<?php

namespace App\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\User;
use Framework\Validation;
use Framework\Session;

class UserController {
    protected $user;
    protected $jobseeker;
    protected $employer;

    public function __construct() {
        $this->user = new User();
        $this->jobseeker = new JobSeeker();
        $this->employer = new Employer();
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
        $userRole = $_POST['user_role'];

       

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
        // validate user role 
        $allowed_field = ['job_seeker', 'employer'];
        if(!in_array($userRole, $allowed_field)) {
            $errors['user_role'] = "Invalid user role!";
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
        $user = $this->user->findByEmail($email);
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
            'role' => $userRole,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        
        // Insert user into users table
        $this->user->insert($params);

        // Get New user ID
        $userId = $this->user->getLastInsertId();

        $userRoleData = [
            'id' => $userId
        ];

        // insert user id into database table based on user role
        if($userRole === 'job_seeker') {
            $this->jobseeker->insert($userRoleData);
        } else if($userRole === 'employer') {
            $this->employer->insert($userRoleData);
        }

        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'role' => $userRole
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
        // Clear all session data
        Session::clearAll();
    
        // Get session cookie parameters
        $params = session_get_cookie_params();
    
        // Destroy the session cookie by setting its expiration time in the past
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    
        // Redirect to homepage
        redirect('/');
    }
    

    /**
     * Authenticate a user with email and passsword 
     * 
     * @return void
     */
    public function authenticate() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        // Validation
        if(!Validation::email($email)) {
            $errors['email'] = "Please enter a valid email";
        }

        if(!Validation::string($password, 6)) {
            $errors['password'] = 'Pasword must be at least 6 character';
        }

        // Check for errors
        if(!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check for email 
        $user = $this->user->findByEmail($email);

        if(!$user) {
            $errors['email'] = 'Incorrect crendentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // Check if password is correct 
        if(!$this->user->verifyPassword($password, $user->password)) {
            $errors['password'] = 'Incorrect Password';
            loadView('users/login', [
                'errors' => $errors,
                'email' => $user->email
            ]);
            exit;
        }

        // Set user Session 
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
            'role' => $user->role
        ]);

        redirect('/');
    }
}