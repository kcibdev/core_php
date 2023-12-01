<?php

namespace Hp\LearningRoute\Controllers;

use Hp\LearningRoute\Common\Functions;
use Hp\LearningRoute\Common\Response;
use Hp\LearningRoute\Common\Auth;
use Hp\LearningRoute\Common\Validator;
use Hp\LearningRoute\Core\Database;

class AuthController
{

    private $db;
    private $jwt;

    public function __construct()
    {
        $this->db = new Database();
        $this->jwt = new Auth();
    }

    public function login()
    {
        $inputJSON = file_get_contents('php://input');
        $_POST = json_decode($inputJSON, true);
        $data = $_POST;

        if (!isset($data['email']) || !isset($data['password'])) {
            Functions::abort(ERROR_LOGIN, SC400);
        }

        if (!Validator::email($data['email'])) {
            Functions::abort(ERROR_EMAIL, SC400);
        }

        $user = $this->db->query("SELECT * FROM _ql_users WHERE MATCH (email_address) AGAINST (?)", ['"' . $data['email'] . '"'])->find();

        if (!$user) {
            Functions::abort("Invalid login details", SC400);
        }

        if ($user['acct_status'] != 'active') {
            Functions::abort("Can't access account. If you have any questions, Contact our support team.", SC401);
        }

        $verify_password = password_verify($data['password'], $user['user_password']);

        if (!$verify_password) {
            Functions::abort("The password is incorrect", SC400);
        }

        $sterized_user_data = Functions::remove_data($user, 'user_password');

        $token = $this->jwt->generate($sterized_user_data['userId']);
        $sterized_user_data['token'] = $token;

        Response::reply(SUCCESS_LOGIN, SC200, $sterized_user_data);
    }

    public function register($data)
    {
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            Functions::abort(ERROR_REGISTER, SC400);
        }
        $data = $_POST;
        Functions::dump($data);
    }

    public function forgot($data)
    {
        Functions::dump($data);
    }
}
