<?php
    /*
    * App Core Class
    * Creates URL & Loads core controller
    * URL FORMAT - /controller/method/params
    */

    class Core {
        
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct(){
            $url  = $this->getUrl();
            // Looks in controllers for the first value. e.g <sitename>/first_value as Controller
            if(file_exists('../app/controllers/'. ucwords($url[0]). '.php')){
                // If exists, set as $currentController
                $this->currentController = ucwords($url[0]);
                //Unset 0 Index
                unset($url[0]);
            }
            // Require the Controller 
            require_once '../app/controllers/'. $this->currentController . '.php';
            // Instatiate the Controller class
            $this->currentController =new $this->currentController;

            // Checks for the second value.e.g<sitename>/first_value/second_value as Method
            if(isset($url[1])){
                // Checks if Method exists, set as $currentController
                if(method_exists($this->currentController, $url[1])){
                      $this->currentMethod = $url[1];
                    //Unset 1 Index
                      unset($url[1]);
                }
                 
             }

             // Get params from the url.
             $this->param = $url ? array_values($url) : [];
             // Call a callback with array of params
             call_user_func_array([$this->currentController, $this->currentMethod], $this->param);
        }
        public function getUrl(){
            if(isset($_GET['url'])){
                $url  = rtrim($_GET['url'], '/');
                $url  = filter_var($url, FILTER_SANITIZE_URL);
                $url  = explode('/', $url);
                #var_dump($url);
                return $url;
            }
           
        }
    }