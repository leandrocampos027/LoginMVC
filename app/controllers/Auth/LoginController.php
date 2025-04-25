<?php
namespace App\Controllers\Auth;
use App\Models\Auth\Usuario;
use App\Models\Auth\PasswordReset;

use App\Services\Mailer;
use App\Helpers\InputHelper;
use App\Helpers\Sanitizer;


class LoginController {

    //ETAPA DE LOGIN / LOGOUT
    public function index() {
        require_once '../app/views/Auth/login.php';
    }

    public function autenticar() {       

        $email = InputHelper::sanitizeEmail($_POST['email'] ?? '');
        $senha = InputHelper::cleanString($_POST['senha'] ?? '');      
    
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorEmail($email);
    
        if (!$usuario) {
            $_SESSION['error'] = "Usuário não existe";
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }
    
        if (password_verify($senha, $usuario['password'])) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: ' . BASE_URL . '/Auth/login/dashboard');
            exit;

        } else {
            $_SESSION['error'] = "Usuário e/ou senha inválidos";
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        require_once '../app/views/painel/dashboard.php';
    }

    public function sair() {        
        session_destroy();
        header('Location: ' . BASE_URL . '/Auth/login');
    }

    
    //ETAPA DE CADASTRO
    public function register() {
        require_once '../app/views/Auth/register.php';
    }
    
    public function cadastrar() {         

        $email = InputHelper::sanitizeEmail($_POST['email'] ?? '');
        $password = InputHelper::cleanString($_POST['password'] ?? '');
        $confirm_password = InputHelper::cleanString($_POST['confirm_password'] ?? '');

        $user = new Usuario();
        
        // Verifica se as senhas coincidem
        if ($password !== $confirm_assword) {
            $_SESSION['error'] = "As senhas não são iguais.";
            header('Location: ' . BASE_URL . '/Auth/login/register');
            exit;
        }

        if(empty($email) || empty($password) || empty($password)){
            $_SESSION['error'] = "Preencha todos os campos obrigatório.";
            header('Location: ' . BASE_URL . '/Auth/login/register');
            exit;
        }
        if ($user->exists($email)) {
            $_SESSION['error'] = "Já existe uma conta com esse e-mail.";
            header('Location: ' . BASE_URL . '/Auth/login/register');
            exit;
        }
        if ($user->register($email, $password)) {
            $_SESSION['success'] = "Conta criada com sucesso! Faça o login.";
            header('Location: ' . BASE_URL . '/Auth/login/register');
            exit;
        } else {
            $_SESSION['error'] = "Erro ao criar a conta. Tente novamente.";
            header('Location: ' . BASE_URL . '/Auth/login/register');
            exit;
        }

    }   

    // ETAPA RECUPERAÇÃO DE SENHA
    public function forgot_password() {
        require_once '../app/views/Auth/forgot_password.php';
    }

    public function recuperar() {       
        $email = InputHelper::sanitizeEmail($_POST['email'] ?? '');

        if (empty($email)) {
            $_SESSION['error'] = "Por favor, informe seu e-mail.";
            header('Location: ' . BASE_URL . '/Auth/login/forgot_password');
            exit;
        }
        
        $user = new Usuario();
        $reset = new PasswordReset();
        $mailer = new Mailer();

        if (!$user->exists($email)) {
            $_SESSION['error'] = "E-mail não encontrado.";
            header('Location: ' . BASE_URL . '/Auth/login/forgot_password');
            exit;
        }

        // Gerar token e código
        $token = bin2hex(random_bytes(16));
        $code = rand(100000, 999999); // código de 6 dígitos

        // Salvar no banco
        $reset->createToken($email, $token, $code);

        // Codificar o token na URL
        $encodedToken = urlencode(base64_encode($token));

        $link = BASE_URL . '/Auth/login/reset_password?token='.$encodedToken;

        if ($mailer->sendRecoveryEmail($email, $code, $link)) {
            $_SESSION['success'] = "Enviamos o link de recuperação para seu e-mail!";
        } else {
            $_SESSION['error'] = "Erro ao enviar o e-mail. Tente novamente.";
        }

        header('Location: ' . BASE_URL . '/Auth/login/forgot_password');
        exit;
    }

    // ETAPA RESET DE SENHA
    public function reset_password() {
        require_once '../app/views/Auth/reset_password.php';
    }

    public function alterar() {
        $user = new Usuario();
        $reset = new PasswordReset();
    
        $token = base64_decode(urldecode($_POST['token'] ?? ''));
        $code = InputHelper::sanitizeInt($_POST['code'] ?? '');        
        $newpassword = InputHelper::cleanString($_POST['new_password'] ?? '');
        $confirmPassword = InputHelper::cleanString($_POST['confirm_password'] ?? '');
    
        // Verifica se todos os campos estão preenchidos
        if (empty($token) || empty($code) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = "Todos os campos são obrigatórios.";
            header('Location:' . BASE_URL . '/Auth/login/reset_password?token=' . urlencode(base64_encode($token)));
            exit;
        }
    
        // Verifica se as senhas coincidem
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "As senhas não são iguais.";
            header('Location:' . BASE_URL . '/Auth/login/reset_password?token=' . urlencode(base64_encode($token)));
            exit;
        }
    
        // Valida o token e o código
        if ($reset->validateToken($token, $code)) {
            $email = $reset->getEmailByToken($token);
            if ($reset->updatePassword($email, $newPassword)) {
                $reset->deleteToken($token);
                $_SESSION['success'] = "Senha alterada com sucesso! Faça o login.";
                header('Location:' . BASE_URL . '/Auth/login/');
                exit;
            } else {
                $_SESSION['error'] = "Erro ao atualizar a senha.";
            }
        } else {
            $_SESSION['error'] = "Token ou código inválido ou expirado.";
        }
    
        header('Location:' . BASE_URL . '/Auth/login/reset_password?token=' . urlencode(base64_encode($token)));
        exit;
    }
    
    

    
}
