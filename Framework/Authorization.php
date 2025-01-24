<?php 

namespace Framework;
use Framework\Session;

class Authorization {
    /**
     * Check if current logged in user owns a resource 
     * 
     * @param int $resourceId
     * @return bool
     */
    public static function isOwner($resourceId) {
        $sessionUser = Session::get('user');

        if($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int) $sessionUser['id'];
            return $sessionUserId === $resourceId;
        } 

        return false;
    }

    /**
     * Check if your can apply for job or not 
     * 
     * @param int $userId
     * @return bool
     */
    public static function isJobSeeker($userId) {
        $sessionUser = Session::get('user');
        
        if($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int) $sessionUser['id'];
            if($sessionUserId == $userId) {
                // get the role 
                $sessionUserRole = Session::get('user')['role'] ?? '';

                if($sessionUserRole == null || $sessionUserRole === '') {
                    return false;
                }

                if($sessionUserRole == 'job_seeker') {
                    return true;
                }
            }
        }

        return false;
    }
}