<?php 

namespace App\Controllers;

use App\Models\Employer;
use Framework\Authorization;
use Framework\Session;
use Framework\Validation;
use App\Models\User;

class EmployerController {
    private $user;
    private $employer;

    public function __construct()
    {
        $this->user = new User();
        $this->employer = new Employer();

        if(!Authorization::isEmployer()) {
            Session::setFlashMessage('error_message', 'You are not authorized to access this resource');
            return redirect('/');
        }
    }

    /**
     * Get Employer data 
     * 
     * @param mixed $user
     * @return object|null
     */
    public function EmployerData($user) {
        $user->listings_count = $this->employer->countJobs(Session::get('user')['id']);

        $user->application_count = $this->employer->countApplication();

        return $user;
    }

    /**
     * Load the dashboard 
     * 
     * @return void
     */
    public function index() {
        $user = $this->employer->getUser();
        $employer = $this->user->getEmployer();

        
        $user = $this->EmployerData($user);
    
        loadView('users/employer/index', [
            'user' => $user,
            'employer' => $employer
        ]);
    }

    /**
     * Update users profile 
     * 
     * @param array $params
     * @return void
     */
    public function update($params) {
        $userId = $params['id'];

        $user = $this->user->find($userId);

        if(!$user) {
            ErrorController::notFound('Unauthorized users, please log in to our account');
            return;
        }

        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);

        // Validation 
        $errors = [];
        if(!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if(!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 character';
        }

        // check if user email 
        if($user->email !== $email) {
            // check if user entered email if exists in the database
            $checkEmail = $this->user->findByEmail($email);
            if($checkEmail) {
                $errors['email-found'] = "Can't update, {$email} is already exits";
            }
        }

        $user = $this->EmployerData($user);
        if(!empty($errors)) {
            loadView('users/employer/index', [
                'errors' => $errors,
                'user' => $user,
                'employer' => $this->user->getEmployer()
            ]);
            exit;
        } else {
            $userId = (int) Session::get('user')['id'];
            $data = [
                'name' => $name,
                'email' => $email
            ];
            $this->user->update($userId, $data);

            Session::setFlashMessage('success_message', 'Profile updated successfully');

            redirect('/users/employer/dashboard');
        }
    }
    /**
     * Update company information
     * 
     * @return void
     */
    public function companyUpdate() {
        
        $allowedFields = ['company_name', 'company_desc', 'company_website', 'contact', 'location'];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['company_name', 'company_desc', 'contact', 'location'];

        $errors = [];

        // inspectAndDie($updateValues);

        foreach($requiredFields as $field) {
            if(empty($updateValues[$field])) {
                $errors[$field] = ucfirst($field . ' is required');
            }
        }
        
        if(!empty($errors)) {
            loadView('users/employer/index', [
                'errors' => $errors,
                'user' => $this->employer->getUser(),
                'employer' => $this->user->getEmployer()
            ]);
            exit;
        }
        // inspectAndDie($updateValues);

        if(isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === 0) {
            $updateValues['company_logo'] = $this->uploadLogo($_FILES['company_logo'] , ['png', 'jpg', 'jpeg', 'webp'], 2); 
         } else {
            $updateValues['company_logo'] = $this->user->getEmployer()->company_logo;
        }

        
        $userId = (int) Session::get('user')['id'];
        $this->employer->update($userId, $updateValues);

        Session::setFlashMessage('success_message', 'Company information updated successfully');

        redirect('/users/employer/dashboard');
    }

     /**
     * upload Resume 
     * 
     * @param object $fileObj
     * @param array $allowed_ext 
     * @param string $filename
     * @param int $maxSize
     * @return void
     */
    public function uploadLogo($fileObj, $allowed_ext = ['pdf'], $maxSize = '1') {
        $file_name = $fileObj['name'];
        $file_size = $fileObj['size'];
        $file_temp = $fileObj['tmp_name'];

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed_ext)) {
                $errors['company_logo'] = "Only png, jpg, webp are allowed!";
            } else {

                // size 
                $maxSize = 2 * 1024 * 1024;

                if($file_size > $maxSize) {
                    $errors['logo_size'] = "File size exceeds 2MB";
                    loadView('users/employer/index', [
                        'errors' => $errors,
                        'user' => $this->employer->getUser(),
                        'employer' => $this->user->getEmployer()
                    ]);
                    exit;
                }
                // inspectAndDie($errors);
                $logo = 'workhunt-' . uniqid() . '-logo' . '.' .$ext;
                $target_dir = basePath("public/uploads/company/logo/");
                if(!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $target_path = $target_dir . $logo;
                move_uploaded_file($file_temp, $target_path);

                return $logo;
            }

    }
}