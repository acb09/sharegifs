<?php

namespace App;

use Exception;
use PDOException;
use App\Database;
use App\Login;

class Register
{
    private $redirectSuccess = '/feed';
    private $redirectFailed = '/login';


    

    public function __construct()
    {
        extract(json_decode(file_get_contents('php://input'), true));

        $this->name = addslashes($name);
        $this->email = addslashes($email);
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        $this->register();
    }





    public function register()
    {
        try {
            $this->validate();
            $created = $this->create();

            $dataSession = ['id' => $this->lastInsertId, 'email' => $this->email];
            $createdSession = Login::createSession($dataSession);

            if ($created && $createdSession)
                echo json_encode([
                    'message' => 'Usuário cadastrado com sucesso!',
                    'status' => 201,
                    'redirect' => $this->redirectSuccess
                ]);
            else if ($created) {
                echo json_encode([
                    'message' => 'Usuário cadastrado com sucesso!',
                    'status' => 201,
                    'redirect' => $this->redirectFailed
                ]);
            } else
                echo json_encode([
                    'message' => 'Ocorreu um erro! Tente novamente mais tarde!',
                    'status' => 400
                ]);
        } catch (PDOException $e) {
            echo json_encode([
                'message' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }





    private function validate()
    {
        if ($this->name == '' or $this->email == '' or $this->password == '')
            throw new Exception("Campos estão vazios!");

        return true;
    }





    private function create()
    {
        $database = new Database;
        $created = $database->setTable('users')->create(['name', 'email', 'password'], [$this->name, $this->email, $this->password]);
        $this->lastInsertId = $database->lastInsertId;

        return $created;
    }
}
