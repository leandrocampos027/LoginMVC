<?php
namespace App\Helpers;

class InputHelper {
    // Sanitiza e retorna string limpa (sem tags e com trim)
    public static function cleanString($value) {
        return trim(strip_tags($value));
    }

    // Sanitiza e valida e-mail
    public static function sanitizeEmail($email) {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    // Sanitiza inteiros
    public static function sanitizeInt($value) {
        return filter_var(trim($value), FILTER_SANITIZE_NUMBER_INT);
    }

    // Verifica se campo está vazio
    public static function isEmpty($value) {
        return empty(trim($value));
    }

    // Valida se duas senhas são iguais
    public static function passwordsMatch($pass1, $pass2) {
        return trim($pass1) === trim($pass2);
    }
}
