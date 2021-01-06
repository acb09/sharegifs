<?php

namespace App;

use App\Database;

class Followers
{

    public function __construct()
    {
        self::getFollowers();
    }




    static public function getFollowers()
    {
        $database = new Database;

        $users = $database
            ->setTable('users', 'u')
            ->select(['u.id, u.name, u.avatar, amount_followers'])
            ->join('followers', 'f', ['u.id', '=', 'f.id_followed'])
            ->where([
                ['f.id_follower', '=', $_SESSION['user']['id']]
            ])
            ->get();

        $profiles = [];

        foreach ($users as $user) {
            $user["id"] = (int) $user["id"];
            $user["follow"] = true;
            $profiles[] = $user;
        }

        echo json_encode($profiles);

        return self::class;
    }
}
