<?php

namespace App;

use App\Database;
use App\Followers;

class Search
{

    public function __construct()
    {
        $this->name = addslashes($_GET['name']);
        $this->getSearch();
    }





    public function getSearch()
    {
        if (!isset($this->name) || $this->name == '')
            return Followers::getFollowers();

        $database = new Database;

        $sql = "SELECT u.id, u.name, u.avatar, u.amount_followers, ";
        $sql .= "(SELECT COUNT(f.id) ";
        $sql .= "FROM followers as f ";
        $sql .= "WHERE u.id = f.id_followed AND f.id_follower = " . $_SESSION['user']['id'] . ") as follow ";
        $sql .= "FROM users as u ";
        $sql .= "WHERE u.id <> " . $_SESSION['user']['id'] . " ";
        $sql .= "AND u.name";
        $sql .= " LIKE '" . $this->name . "%'";

        // SELECT u.id, u.name, u.avatar, u.amount_followers, (SELECT COUNT(f.id) FROM followers as f WHERE u.id = f.id_followed AND f.id_follower = 22 ) 

        // as follow 
        
        // FROM users as u 
        
        // WHERE u.id <> 22 AND u.name
        
        // LIKE 'd%'

        $users = $database->custom($sql);

        $profiles = [];

        foreach ($users as $user) {
            $user["id"] = (int) $user["id"];
            $user["follow"] = (bool) $user['follow'];
            $profiles[] = $user;
        }

        echo json_encode($profiles);

        return $this;
    }
}
