<?php

namespace App;

use App\Database;

class Login
{
    private $redirectSuccess = '/feed';
    private $redirectFailed = '';





    public function __construct()
    {
        extract(json_decode(file_get_contents('php://input'), true));

        $this->email = addslashes($email);
        $this->password = $password;

        $this->login();
    }





    public function login()
    {
        $database = new Database;

        $user = $database
            ->setTable('users')
            ->select(['id', 'name', 'email', 'avatar', 'password'])
            ->where([
                ['email', '=', $this->email]
            ])
            ->first();

        $noResult = !count($user);

        if ($noResult)
            echo json_encode([
                'message' => 'Verifique suas credenciais e tente novamente!',
                'status' => 404,
                'redirect' => $this->redirectFailed
            ]);
        else {
            $combine = password_verify($this->password, $user['password']);
            if ($combine) {
                self::createSession($user);

                echo json_encode([
                    'message' => 'Efetuando o login...',
                    'status' => 200,
                    'redirect' => $this->redirectSuccess
                ]);
            } else {
                echo json_encode([
                    'message' => 'Verifique suas credenciais e tente novamente!',
                    'status' => 404,
                    'redirect' => $this->redirectFailed
                ]);
            }
        }
    }





    public static function createSession(array $data)
    {
        return $_SESSION['user'] = $data;
    }





    public static function logout()
    {
        $_SESSION['user'] = null;
        session_destroy();
    }
}
