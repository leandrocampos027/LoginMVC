🔐 Sistema de Login com Recuperação de Senha em PHP (MVC + POO + PDO + PHPMailer)
Este é um sistema de login moderno e seguro, desenvolvido com arquitetura MVC, utilizando PHP com POO, banco de dados com PDO, e PHPMailer para envio de e-mails com templates em HTML estilizados. O projeto foca em segurança, organização do código, e boas práticas de desenvolvimento.

FUNCIONALIDADES
📦 Estrutura 100% modular com padrão MVC

🧹 Validação e sanitização de inputs com classe helper

🔐 Login e cadastro de usuários com feedback de erro/sucesso

🔑 Recuperação de senha via e-mail com:

- Token único + código de verificação
- Expiração automática do token
- Validação vinculada ao e-mail
- URL segura codificada com base64

📧 Envio de e-mails com template HTML moderno:

- Suporte a logo personalizada no email
- Layout estilizado com CSS inline
- Fallback para nome textual caso não haja logo
  

🔒 Proteção de páginas restritas com verificação de sessão

🧠 Senhas criptografadas com password_hash()

🌐 URLs amigáveis para navegação limpa


🛠️ Tecnologias Utilizadas

- PHP 7.4+
- Padrão MVC com POO
- PDO para conexão segura com MySQL/MariaDB
- PHPMailer para envio de e-mails
- HTML5 / CSS3 / Bootstrap (layout e template do e-mail)
- JavaScript (validações pontuais)
- Composer (autoloading de dependências)


🎯 Objetivo

Este projeto foi desenvolvido com o propósito de praticar e demonstrar:
- Boas práticas em PHP com POO
- Uso de padrões reais como MVC
- Implementação de fluxo seguro de autenticação
- Envio de e-mails reais com templates modernos
- Validação e proteção contra ataques comuns (XSS, etc.)
- Ideal para estudos, portfólios ou como base para sistemas maiores.
  

🚀 Como instalar

1) Clone o repositório:
   https://github.com/leandrocampos027/LoginMVC/

2) Instale as dependências com o Composer:
   
  >composer install

Instalar o Composer (caso ainda não tenha)

  Se ainda não tem o Composer instalado:
  
  Acesse: https://getcomposer.org/download
  
  Siga as instruções de instalação para Windows, Linux ou Mac
  

3) Instalar o PHPMailer
   
No terminal, ainda na pasta do projeto, execute:

  >composer require phpmailer/phpmailer

Isso vai:

- Baixar o PHPMailer na pasta vendor/
- Criar (ou atualizar) o composer.json
- Gerar o autoload automático em vendor/autoload.php

4) Configure o banco de dados:
   
- Crie um banco de dados MySQL/MariaDB
- Use o SQL do arquivo config/db.txt para criar o banco de dados
- Edite os dados em /config/config.php:

5)Configure o envio de e-mails: 

Edite seus dados no arquivo config/mailconfig.php


