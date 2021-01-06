<?php

namespace App;

use App\Database;
use PDOException;

class Publications
{

    public function __construct()
    {
        self::getPublications();
    }





    static public function create()
    {
        try {

            extract($_POST);

            if (!$description && !$images && !isset($_FILES['images'])) {
                echo json_encode([
                    'message' => 'Você precisa preencher alguma informação.',
                    'status' => 400
                ]);
                return false;
            }

            $urlsImagesInComma = [];
            $uploadSucess = false;

            if (isset($_FILES['images']))

                foreach ($_FILES["images"]["tmp_name"] as $key => $value) {
                    $filename = basename($_FILES["images"]["name"][$key]);
                    $extension = array_pop(explode('.', $filename));
                    /*
                    * Criptografa o nome do arquivo.
                    * O prefixo é o id do usuário quem publicou.
                    * O sufixo é a extensão do arquivo.
                    */
                    $filenameCript = $_SESSION['user']['id'] . '.' . md5($filename . date('Y-m-d H-i-s')) . '.' . $extension;

                    $targetPath = "./img/publications/" . $filenameCript;
                    $uploadSucess = move_uploaded_file($value, $targetPath);

                    if ($uploadSucess)
                        $urlsImagesInComma[] = $targetPath;
                }

            else
                $urlsImagesInComma[] = $images[0];

            $legend = count($urlsImagesInComma) ? $urlsImagesInComma[0] : null;

            $database = new Database;
            $created = $database
                ->setTable('publications')
                ->create(
                    ['id_user', 'description', 'legend', 'src_image', 'id_owned'],
                    [$_SESSION['user']['id'], $description, $legend, implode(',', $urlsImagesInComma), $_SESSION['user']['id']]
                );

            if ($created)
                echo json_encode([
                    'message' => 'Publicação criada!',
                    'status' => 201,
                    'publish' => self::findPublish($database->lastInsertId)
                ]);
            else {
                foreach (explode(',', $urlsImagesInComma) as $image)
                    @unlink($image);

                echo json_encode([
                    'message' => 'Ocorreu um erro. Tente novamente mais tarde!',
                    'status' => 400
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'message' => 'Informações não foram enviadas.',
                'status' => 500
            ]);
        }
    }





    static public function findPublish($id)
    {
        $database = new Database;

        $result = $database->custom('SELECT * FROM publications as p INNER JOIN users as u ON p.id_user = u.id WHERE p.id = ' . $id)
            ->fetch();

        $profile = [
            'id' => $result['id_user'],
            'name' => $result['name'],
            'avatar' => $result['avatar'],
            'email' => $result['email'],
            'amount_followers' => $result['amount_followers'],
            // 'link' => '/feed?user=' + $result['id_user']
        ];

        $publications = [
            'profile' => $profile,

            'id' => $result['id'],
            'description' => $result['description'],
            'legend' => $result['legend'],
            'image' => $result['src_image'] ?? $result['legend'],
            'hearts' => [
                'isHeart' => false,
                'amount' => $result['amount_hearts']
            ],
            'comments' => [
                'users' => [],
                'amount' => $result['amount_comments'],
            ],
            'shares' => [
                'isShares' => false,
                'amount' => $result['amount_shares'],
            ],
            'id_owned' => $result['id_owned'],
            'id_original' => $result['id_original'],
            'create_at' => $result['create_at'],
        ];

        // echo json_encode($publications);

        return json_encode($publications);
    }





    static public function getPublications()
    {
        $database = new Database;

        $sql = 'SELECT * FROM publications as p INNER JOIN users as u on p.id_user = u.id WHERE p.id_user IN ';
        $sql .= '( SELECT f.id_followed FROM followers as f WHERE f.id_follower = ' . $_SESSION['user']['id'] . ' ) ';
        $sql .= 'OR p.id_user = ' . $_SESSION['user']['id'] . ' ORDER BY create_at ASC';

        $results = $database->custom($sql);

        $publications = [];

        foreach ($results->fetchAll() as $result) {

            $profile = [
                'id' => $result['id_user'],
                'name' => $result['name'],
                'avatar' => $result['avatar'],
                'email' => $result['email'],
                'amount_followers' => $result['amount_followers'],
                'follow' => 'true',
                // 'link' => '/feed?user=' + $result['id_user']
            ];
            $publications[] = [
                'profile' => $profile,

                'id' => $result['id'],
                'description' => $result['description'],
                'legend' => $result['legend'],
                'image' => $result['src_image'] ?? $result['legend'],
                'hearts' => [
                    'isHeart' => false,
                    'amount' => $result['amount_hearts']
                ],
                'comments' => [
                    'users' => [],
                    'amount' => $result['amount_comments'],
                ],
                'shares' => [
                    'isShares' => false,
                    'amount' => $result['amount_shares'],
                ],
                'id_owned' => $result['id_owned'],
                'id_original' => $result['id_original'],
                'create_at' => $result['create_at'],
            ];
        }

        echo json_encode($publications);

        return self::class;
    }
}
