<?php
namespace App\Helpers;

class Sanitizer {
    public static function sanitize($data, $type = '') {
        $data = trim($data);

        switch ($type) {
            case 'cpf':
                $data = preg_replace('/[^0-9]/', '', $data);
                return (self::isValidCPF($data)) ? $data : false;

            case 'cnpj':
                $data = preg_replace('/[^0-9]/', '', $data);
                return (self::isValidCNPJ($data)) ? $data : false;

            case 'celular':
                $data = preg_replace('/[^0-9]/', '', $data);
                return (strlen($data) === 11) ? $data : false;

            case 'cep':
                $data = preg_replace('/[^0-9]/', '', $data);
                return (strlen($data) === 8) ? $data : false;

            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);

            case 'string':
                return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

            default:
                return $data;
        }
    }

    private static function isValidCPF($cpf) {
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }

        return true;
    }

    private static function isValidCNPJ($cnpj) {
        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) return false;

        $soma1 = 0;
        $soma2 = 0;
        $multiplicador1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $multiplicador2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $soma1 += $cnpj[$i] * $multiplicador1[$i];
        }
        $resto = $soma1 % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        for ($i = 0; $i < 13; $i++) {
            $soma2 += $cnpj[$i] * $multiplicador2[$i];
        }
        $resto = $soma2 % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return ($cnpj[12] == $digito1 && $cnpj[13] == $digito2);
    }
}
