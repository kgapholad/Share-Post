<?php
class Pages extends Controller {
    
    public function __construct(){
        
    }
    // Index Page is a default, so it has to be the first page. 
    public function index(){
       if(isLoggedIn()){
        redirect('posts');
       }
       $data = [
           'title'=>'Shareposts',
           'description'=>'Simple Social Network'
        ];
       $this->view('pages/index', $data);
    }
    public function about(){
        $data = [
            'title'=>'About Us',
            'description'=>'App to share Posts with other users.'
    ];
        $this->view('pages/about',$data);
    }
}