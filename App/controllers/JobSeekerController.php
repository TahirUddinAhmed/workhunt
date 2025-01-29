<?php 

namespace App\Controllers;

use App\Models\JobSeeker;
use App\Models\User;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

class JobSeekerController {
    protected $user;
    protected $jobseeker;

    public function __construct()
    {
        $this->user = new User();
        $this->jobseeker = new JobSeeker();

        if(!Authorization::isJobSeeker()) {
            Session::setFlashMessage('error_message', 'You are not authorized to access this resource');
            return redirect('/');
        }
    }

    /**
     * Job seeker dashboard view
     *
     * @return void
     */
    public function dashboard() {
        $user = $this->jobseeker->getUser();
        $jobseeker = $this->user->getJobSeeker();

        if(!empty($jobseeker->skills)) {
            // convert the coma separated string into an array
            // Use preg_split to match commas outside parentheses
            $jobseeker->arraySkills = preg_split('/,(?![^()]*\))/', $jobseeker->skills);

            // Trim any whitespace around the skills
            $jobseeker->arraySkills = array_map('trim', $jobseeker->arraySkills);
        }
        
        // inspect($jobseeker);
        loadView('users/jobseeker/dashboard', [
            'user' => $user,
            'jobseeker' => $jobseeker
        ]);
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
    public function uploadResume($fileObj,  $filename, $allowed_ext = ['pdf'], $maxSize = '1') {
        $resume_name = $fileObj['name'];
        $resume_size = $fileObj['size'];
        $resume_temp = $fileObj['tmp_name'];

        $ext = pathinfo($resume_name, PATHINFO_EXTENSION);
            if(!in_array($ext, $allowed_ext)) {
                $errors['resume'] = "Only PDF are allowed";
            } else {

                // size 
                $maxSize = 3 * 1024 * 1024;

                if($resume_size > $maxSize) {
                    $errors['resume_size'] = "File size exceeds 3MB";
                    loadView('users/jobseeker/dashboard', [
                        'user' => $this->jobseeker->getUser(),
                        'jobseeker' => $this->user->getJobSeeker(),
                        'errors' => $errors
                    ]);
                    exit;
                }
                // inspectAndDie($errors);
                $resume = 'workhunt-' . $filename . '-resume' . '.' .$ext;
                $target_dir = basePath("public/uploads/resumes/");
                if(!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                $target_path = $target_dir . $resume;
                move_uploaded_file($resume_temp, $target_path);

                return $resume;
            }

    }

    /**
     * Job seeker info store 
     * 
     * @return void
     */
    public function update() {
        // inspectAndDie($_FILES);
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $contact = sanitize($_POST['contact']);
        $qualification = sanitize($_POST['qualification']);
        $skills = sanitize($_POST['skills']);
        $city = sanitize($_POST['city']);
        $state = sanitize($_POST['state']);
        $resume = '';
        

        // Validation
        $errors = [];

        if(!Validation::string($name, 3, 50)) {
            $errors['name'] = "Please Enter a valid name";
        }

        if(!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if(isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
           $resume = $this->uploadResume($_FILES['resume'], $email, ['pdf'], 3); 
        } else {
            $resume = $this->user->getJobSeeker()->resume;
            
        }

        // update data 
        $jobSeekerId = Session::get('user')['id'];
        $userData = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ];
        $this->user->update($jobSeekerId, $userData);

        $jobSeekerData = [
            'contact' => $contact,
            'qualification' => $qualification,
            'skills' => $skills,
            'resume' => $resume
        ];

        $this->jobseeker->update($jobSeekerId, $jobSeekerData);

        // message 
        Session::set('success_message', 'User Profile Updated');

        redirect('/users/jobseeker/dashboard');
    }


    /**
     * Download resume 
     * 
     * @return void
     */
    public function downloadResume() {
        $jobSeeker = $this->user->getJobSeeker();
        
        $resumePath = basePath("public/uploads/resumes/{$jobSeeker->resume}");

        if(!file_exists($resumePath)) {
            Session::set('error_message', 'Resume not found may be you delete it.');
            redirect('/users/jobseeker/dashboard');
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($resumePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($resumePath));
        readfile($resumePath);

        exit;

    }
}