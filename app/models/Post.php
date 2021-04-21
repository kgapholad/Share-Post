<?php

class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
    public function getPosts(){
        
        $this->db->query("SELECT *,
                        posts.id as postsId,
                        users.id as usersId,
                        posts.created_at as postCreated,
                        users.created_at as userCreated
                        FROM posts
                        INNER JOIN users
                        ON posts.user_id = users.id
                        ORDER BY posts.created_at DESC
                        ");
        return $this->db->resultSet();
    }
    public function addPost($data){
        $this->db->query('INSERT INTO posts (title , body, user_id) VALUES(:title ,:body ,:user_id)'); 
        $this->db->bind(':title' , $data['title']);
        $this->db->bind(':body' , $data['body']);
        $this->db->bind(':user_id' , $data['user_id']);
                // Execute
                if($this->db->execute()){
                    return true;
                }else{
                    return false;
                }
    }
    public function updatePost($data){
        $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id'); 
        $this->db->bind(':id' , $data['id']);
        $this->db->bind(':title' , $data['title']);
        $this->db->bind(':body' , $data['body']);        
                // Execute
                if($this->db->execute()){
                    return true;
                }else{
                    return false;
                }
    }

    public function getPostById($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id'); 
        $this->db->bind(':id' , $id);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0){
            return $row;
        }else{
            return false;
        }
    }
    
    public function deletePostById($id){
        $this->db->query('DELETE FROM posts WHERE id = :id'); 
        $this->db->bind(':id' , $id);
        // Check row
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}