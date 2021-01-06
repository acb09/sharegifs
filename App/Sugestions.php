<?php

namespace App;

use App\Database;

class Sugestions
{

    public function __construct()
    {
        $this->getSugestions();
    }




    public function getSugestions()
    {
        $database = new Database;

        // $results = $database
        //     ->select(['u.id', 'u.name', 'u.avatar', 'u.email', 'u.amount_followers'])
        //     ->setTable('users', 'u')
        //     ->join(
        //         'followers',
        //         'f',
        //         [
        //             [$_SESSION['user']['id'], '!=', 'f.id_follower']
        //         ],
        //         'INNER'
        //     )
        //     ->where([
        //         ['u.id', '!=', $_SESSION['user']['id']]
        //     ])
        //     ->limit(10)
        //     ->get();

        $sql = 'SELECT u.id, u.name, u.avatar, u.email, amount_followers FROM users as u  WHERE u.id NOT IN ';
        $sql .= '(SELECT f.id_followed FROM followers as f WHERE f.id_follower = ' . $_SESSION['user']['id'] . ') AND U.id <> ' . $_SESSION['user']['id'];

        $results = $database->custom($sql);

        $profiles = [];

        foreach ($results as $result) {
            $result["id"] = (int) $result["id"];
            $result["follow"] = false;
            $profiles[] = ["profile" => $result];
        }

        echo json_encode($profiles);

        return $this;
    }
}
