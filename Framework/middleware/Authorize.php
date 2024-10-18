<?php 

namespace Framework\Middleware;

use Framework\Session;

class Authorize {
    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    public function isAuthenticated() {
        return Session::has('user');
    }

    /**
     * Handle the users request
     * 
     * @param string $role
     * @return bool
     */
    public function handle($role) {
        if($role === 'guest' && $this->isAuthenticated()) {
            return redirect('/');
        } else if($role == 'auth' && !$this->isAuthenticated()) {
            return redirect('/auth/login');
        }
    }
}