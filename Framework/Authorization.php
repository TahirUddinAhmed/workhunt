<?php 

namespace Framework;
use Framework\Session;
use App\Models\JobSeeker;

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
     * @return bool
     */
    public static function isJobSeeker() {
        // get the role 
        $sessionUser = Session::get('user') ?? '';

        if($sessionUser['role'] == null || $sessionUser['role'] === '') {
            return false;
        }

        if($sessionUser['role'] == 'job_seeker') {
            $userId = (int) $sessionUser['id'];
            $jobSeekerModel = new JobSeeker();

            return $jobSeekerModel->isJobSeeker($userId);
        }
            

        return false;
    }
}