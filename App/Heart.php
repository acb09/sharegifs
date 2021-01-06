<?php

namespace App;

use App\Database;
use Exception;

class Heart
{

    public function __construct()
    {
        $this->id = addslashes($_GET['id']);

        if ($this->id)
            throw new Exception('Ocorreu um erro. Tente novamente mais tarde!');

        $this->heart();
    }





    public function heart()
    {
        $database = new Database;

        $amount = $database->select(['amount_hearts'])->setTable('publications')->where([['id' => $this->id]])->exec();
        $database->update(['amount_hearts'], [$amount + 1]);

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
