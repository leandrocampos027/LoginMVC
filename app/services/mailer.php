<?php
namespace App\services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/mailconfig.php';  // Agora sim, as constantes

class Mailer {
    public static function sendRecoveryEmail($email, $token, $encodedEmail) {
        $mail = new PHPMailer(true);

        try {
            // Configuração do servidor SMTP
            $mail->CharSet = 'UTF-8'; // <-- Linha corrige acentos
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER;
            $mail->Password   = MAIL_PASS;
            $mail->Port       = MAIL_PORT;

            // Verifica o tipo de criptografia
            $mail->SMTPSecure = strtolower(MAIL_ENCRYPTION) === 'tls' 
                ? PHPMailer::ENCRYPTION_STARTTLS 
                : PHPMailer::ENCRYPTION_SMTPS;


            // Remetente e destinatário
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($email);

            // ENVIAR ANEXO
            //$mail->addAttachment(__DIR__ .'/../../public/assets/img/login.png', 'NomePersonalizado.png');

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperação de Senha';

            // Dados do template
            $templatePath = __DIR__ . '/../views/email/recovery_email.html';
            $template = file_get_contents($templatePath);

            // Verifica se existe uma logo definida
            $logoOrName = defined('MAIL_LOGO') && !empty(MAIL_LOGO)
            ? "<img src='" . MAIL_LOGO . "' alt='" . MAIL_FROM_NAME . "' style='max-width: 150px;'>"
            : "<h1 style='color: #fff; font-size: 24px; margin: 0;'>" . MAIL_FROM_NAME . "</h1>";

            // Substituição de variáveis no template
            $body = str_replace(
                ['{{TOKEN}}', '{{ENCODED_LINK}}', '{{YEAR}}', '{{MAIL_FROM_NAME}}', '{{LOGO_OR_NAME}}'],
                [$token, $encodedEmail, date('Y'), MAIL_FROM_NAME, $logoOrName],
                $template
            );

            $mail->Body = $body;


            $mail->send();
            return true;

        } catch (Exception $e) {
            // Você pode ativar logs aqui se quiser debug
            return false;
        }
    }
}
