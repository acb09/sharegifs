<?php

namespace App;

use App\Database;
use Exception;

class Heart
{

    public function __construct()
    {
        $this->id = isset($_GET['id']) ? addslashes($_GET['id']) : null;
        $this->not = isset($_GET['not']) ? true : null;

        if (!$this->id)
            throw new Exception('Ocorreu um erro. Tente novamente mais tarde!');

        $this->heart();
    }





    public function heart()
    {
        $database = new Database;

        $operator = $this->not ? -1 : 1;
        $amount = $database->custom('SELECT COUNT(*) as amount_hearts FROM hearts as h WHERE id_publication = ' . $this->id)->fetch()['amount_hearts'] + $operator;


        if ($operator == 1) {
            $hearts = $database
                ->setTable('hearts')
                ->create(
                    ['id_user', 'id_publication', 'signature'],
                    [$_SESSION['user']['id'], $this->id, md5($_SESSION['user']['id'] . 'ShArEgIfS' . $this->id)]
                );
        } else if ($operator == -1) {
            $hearts = $database
                ->setTable('hearts')
                ->delete()
                ->where([
                    ['id_user', '=', $_SESSION['user']['id']],
                    ['id_publication', '=', $this->id]
                ])
                ->exec();
        }


        if (!$hearts) {
            echo json_encode(['message' => 'Ocorreu um erro. Tente novamente mais tarde!', 'status' => 401]);
            return false;
        }

        $updated = $database
            ->setTable('publications')
            ->update(['amount_hearts'], [$amount])
            ->where([['id', '=', $this->id]])
            ->exec();

        echo $updated
            ? json_encode(['message' => 'Tudo certo!', 'status' => 200, 'amount_hearts' => $amount])
            : json_encode(['message' => 'Ocorreu um erro. Tente novamente mais tarde!', 'status' => 400, 'heart' => false]);

        return $this;
    }
}
