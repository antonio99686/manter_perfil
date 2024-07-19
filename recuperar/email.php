<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/gmail.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

    <title>Recuperar Senha</title>
</head>

<body>

</body>

</html>
<?php
// Carregando o PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "../function/function.php";
$conexao = conn();
$email = $_POST['email'];

$sql = "SELECT * FROM usuario WHERE email = '$email'";
$result = executarSQL($conexao, $sql);

$usuario = mysqli_fetch_assoc($result);
if ($usuario == null) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Email não cadastrado! Faça o cadastro e em seguida realize o login.',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../crud/formcad.html'; // redireciona para a página de cadastro
            }
        });
    </script>";
    die();
}

// Gerar um token único
$token = bin2hex(random_bytes(50));

// Incluindo os arquivos necessários do PHPMailer
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
include '../function/conn.php';

$mail = new PHPMailer(true); // Habilita exceções

try {
    // configuração do servidor SMTP
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->setLanguage('br');
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Desative o debug
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Endereço do servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = $conn['email'];
    $mail->Password = $conn['senha_email'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'verifi_self_signed' => true
        )
    );

    $mail->setFrom($conn['email'], "Recuperação de Senha");
    $mail->addAddress($usuario['email'], $usuario['nome']);
    $mail->addReplyTo($conn['email'], "Recuperação de Senha");

    $mail->isHTML(true);
    $mail->Subject = "Recuperação de senha do sistema";
    $mail->Body = 'Olá,<br><br>
    Recebemos uma solicitação para redefinir a senha da sua conta. Para prosseguir com a redefinição, clique no link abaixo:
     <a href="http://localhost:8080/manter_perfil/recuperar/senha_nova.php?email='
        . $usuario['email'] . '&token=' . $token . '">Clique aqui para redefinir sua senha</a><br><br>
    Se você não solicitou a alteração de senha, por favor, ignore este e-mail.<br><br>
    Atenciosamente,<br>
    Equipe de Suporte';

    $mail->send();
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Email enviado!',
        text: 'Confira o seu email para recuperar o acesso à sua conta.',
        confirmButtonText: 'Ok'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../index.html'; // redireciona para a página de login
        }
    });
</script>";

    // gravar recuperar senha 
    date_default_timezone_set('America/Sao_paulo');
    $data = new DateTime('now');
    $agora = $data->format('Y-m-d H:i:s');
    $sql2 = "INSERT INTO recuperar_senha (email,token,data_criacao,usado) 
    VALUES ('" . $usuario['email'] . "','$token','$agora', 0 )";
    $result = executarSQL($conexao, $sql2);
   

} catch (Exception $e) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Não foi possível enviar o email. Mailer Error: {$mail->ErrorInfo}',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'form_recuperar.html'; // redireciona para a página de recuperação de senha
            }
        });
    </script>";
}
?>