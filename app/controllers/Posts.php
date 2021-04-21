<?php
class Posts extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('users/login');
        }
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }
    public function index(){
        // Get Posts
        $posts = $this->postModel->getPosts();
        $data = [
            'posts' => $posts
        ];
        $this->view('posts/index', $data);
    }
    public function add(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process Form
            //SANITIZE POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
                $data = [
                    'title' => trim($_POST['title']),
                    'body' =>  trim($_POST['body']),
                    'user_id' =>  $_SESSION['user_id'],
                    'title_err' =>'',
                    'body_err' =>''
                ]; 
                // Validation
                if(empty($data['title'])){
                    $data['title_err'] = 'Please enter title';
                }
                if(empty($data['body'])){
                    $data['body_err'] = 'Please enter body';
                }
               // Make sure errors are empty
               if(empty($data['title_err']) && empty($data['body_err'])){
                   // Validated
                   if($this->postModel->addPost($data)){
                         flash('post_message', 'Post added');
                         redirect('posts');

                   }else{
                        flash('post_message', 'Something went wrong','alert alert-danger');
                        redirect('posts/add');
                   }

               }else{
                 $this->view('posts/add', $data);
               }

        }else{ 
            $data = [
                'title' => '',
                'body' =>  ''
            ]; 
            $this->view('posts/add', $data);
        }
       
    }
    public function edit($id){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process Form
            //SANITIZE POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
                $data = [
                    'id'=> $id,
                    'title' => trim($_POST['title']),
                    'body' =>  trim($_POST['body']),
                    'user_id' =>  $_SESSION['user_id'],
                    'title_err' =>'',
                    'body_err' =>''
                ]; 
                // Validation
                if(empty($data['title'])){
                    $data['title_err'] = 'Please enter title';
                }
                if(empty($data['body'])){
                    $data['body_err'] = 'Please enter body';
                }
               // Make sure errors are empty
               if(empty($data['title_err']) && empty($data['body_err'])){
                   // Validated
                   if($this->postModel->updatePost($data)){
                         flash('post_message', 'Post Updated');
                         redirect('posts');

                   }else{
                        flash('post_message', 'Something went wrong','alert alert-danger');
                        redirect('posts/edit');
                   }

               }else{
                 $this->view('posts/edit', $data);
               }

        }else{ 
            // Get existing Post from the Model
            $post = $this->postModel->getPostById($id);
            //Check for Owner
            if($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' =>  $post->body
            ]; 
            $this->view('posts/edit', $data);
        }
       
    }
    public function show($id){
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
       $data = [
           'post' => $post,
           'user' => $user
       ];
        $this->view('posts/show', $data);
    }
    public function delete($id){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Get existing Post from the Model
            $post = $this->postModel->getPostById($id);
            //Check for Owner
            if($post->user_id != $_SESSION['user_id']){
                redirect('posts');
            }
            // Deleting the Post by ID
            if($this->postModel->deletePostById($id)){
                flash('post_message', 'Post Deleted');
                redirect('posts');
            }else{
                flash('post_message', 'Something went wrong!','alert alert-danger');
                redirect('posts');
            }
        }else{
            redirect('posts');
        }
    }
    
}