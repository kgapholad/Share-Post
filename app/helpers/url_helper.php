<?php
// Simple Redirect
function redirect($page){
    header('location: '. URLROOT . '/' . $page);
}