<?php

namespace App;

use App\Database;
use Exception;

class Follow
{

    public function __construct()
    {
        $this->id_followed = addslashes($_GET['id_followed']);
        $this->id_follower = $_SESSION['user']['id'];
        $this->signature = md5($this->id_follower . 'ShArEgIfS' . $this->id_followed);

        if ($this->id_followed === $this->id_follower)
            throw new Exception('Você não pode seguir a si mesmo.');

        if ($this->checkIfFieldsEmpty())
            throw new Exception("Campos vazios!");

        $this->register();
    }





    public function register()
    {
        $database = new Database;

        // registra o seguidor
        $follow = $database
            ->setTable('followers')
            ->create(
                ['id_follower', 'id_followed', 'signature'],
                [$this->id_follower, $this->id_followed, $this->signature]
            );

        // recupera número de seguidores
        $amount_followers = $database
            ->setTable('users')
            ->select(['amount_followers'])
            ->where([['id', '=', $this->id_followed]])
            ->first() ?? 0;
        $amount_followers = array_shift($amount_followers);

        //atualiza número de seguidores
        $database
            ->update(['amount_followers'], [++$amount_followers])
            ->where([['id', '=', $this->id_followed]])
            ->exec();

        if ($follow)
            echo json_encode([
                'message' => 'Você agora está seguindo.',
                'status' => 201
            ]);
        else
            echo json_encode([
                'message' => 'Ocorreu um erro. Tente novamente mais tarde!',
                'status' => 401
            ]);

        return $this;
    }





    public function checkIfFieldsEmpty()
    {
        return ((!isset($this->id_followed) || $this->id_followed == '') or (!isset($this->id_follower) || $this->id_follower == ''));
    }
}
