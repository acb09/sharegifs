<?php

namespace App;

use App\Database;
use Exception;
use PDOException;

class avatar
{

    public function edit()
    {
        if (!isset($_FILES['avatar']))
            throw new Exception("Não foi carregado um arquivo");

        $filename = basename($_FILES["avatar"]["name"]);
        $parseFileName = explode('.', $filename);
        $extension = end($parseFileName);
        /*
        * Criptografa o nome do arquivo.
        * O prefixo é o id do usuário quem publicou.
        * O sufixo é a extensão do arquivo.
        */
        $filenameCript = $_SESSION['user']['id'] . '.' . md5($filename . date('Y-m-d H-i-s')) . '.' . $extension;

        $targetPath = "./img/avatars/" . $filenameCript;
        $uploadSucess = move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath);

        $database = new Database;
        $updateInDb = $database
            ->setTable('users')
            ->update(['avatar'], [$targetPath])
            ->where([['id', '=', $_SESSION['user']['id']]])
            ->exec();

        if ($uploadSucess && $updateInDb) {
            $imageActual = $_SESSION["user"]["avatar"];

            if (basename($imageActual) !== "avatar_default.png")
                unlink($imageActual);

            $_SESSION['user']['avatar'] = $targetPath;
            return json_encode([
                'message' => 'Avatar atualizado com sucesso.',
                'status' => 200,
                'avatar' => $targetPath
            ]);
        } else {
            unlink($targetPath);
            return json_encode([
                'message' => 'Ocorreu um erro. Tente novamente mais tarde!',
                'status' => 400
            ]);
        }
    }
}
