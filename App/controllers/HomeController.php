<?php

namespace App\Controllers;

use Framework\Database;

class HomeController {
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function index() {
        $query = 'SELECT * FROM listings';
        $listings = $this->db->query($query);

        loadView('home', [
            'listings' => $listings
        ]);
    }
}