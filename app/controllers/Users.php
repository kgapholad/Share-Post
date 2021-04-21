<?php

class Users extends Controller {
    public function __construct(){
        $this->userModel = $this->model('User');
    }
    public function register(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process Form
            //SANITIZE POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            //Ini Data
            $data = [
                'name'=> trim($_POST['name']),
                'email'=> trim($_POST['email']),
                'password'=> trim($_POST['password']),
                'confirm_password'=> trim($_POST['confirm_password']),
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>''
            ];
            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }else{
                // Check email
                if($this->userModel->FindUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already used';
                }
            }
            // Validate Name
            if(empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }
            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 charaters';
            }
            // Validate Confirm Password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Please enter password';
            }else{
                if($data['confirm_password']!= $data['password']){
                    $data['confirm_password_err'] = 'Password do not match';
                }
            }
            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Validated
                #die('SUCCESS');
                 //Hash the Password. 
                 $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                 // Registger user
                if($this->userModel->register($data)){

                    flash('register_success', 'You are registered and can log in');
                    redirect('users/login');
                }else{
                    die('Something went wrong!');
                }
            }else{
                // Load view with error
                $this->view('users/register', $data); 
            }

        }else{
           // Load Form
           //Ini Data
           $data = [
               'name'=>'',
               'email'=>'',
               'password'=>'',
               'confirm_password'=>'',
               'name_err'=>'',
               'email_err'=>'',
               'password_err'=>'',
               'confirm_password_err'=>''
           ] ;
           $this->view('users/register', $data);
        }
    }
    public function login(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process Form
                        //SANITIZE POST data
                        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                        //Ini Data
                        $data = [
                            'email'=> trim($_POST['email']),
                            'password'=> trim($_POST['password']),
                            'email_err'=>'',
                            'password_err'=>''
                        ];
                        // Validate Email
                        if(empty($data['email'])){
                            $data['email_err'] = 'Please enter email';
                        }
                        // Validate Password
                        if(empty($data['password'])){
                            $data['password_err'] = 'Please enter password';
                        }
         // Check the user
         if($this->userModel->FindUserByEmail($data['email'])){
             //User found
           
        }else{
            // User not found
            $data['email_err'] = 'No user found';
        }              
        // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);//Row Returned
                if($loggedInUser){
                   // Create Session Variable 
                   $this->createUserSession($loggedInUser);
                   #die('SUCCESS');
                }else{
                    $data['password_err'] = 'Password Incorrect';
                    $this->view('users/login', $data);
                }
               
            }else{
                // Load view with error
                $this->view('users/login', $data); 
            }
        }else{
           // Load Form
           //Ini Data
           $data = [
               'email'=>'',
               'password'=>'',
               'email_err'=>'',
               'password_err'=>''
           ] ;
           $this->view('users/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;

        redirect('posts');
    }
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);

        session_destroy();

        redirect('users/login');
    }

}