<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/gmail.png" />
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
    <title>Nova Senha</title>
</head>

<body>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>
</body>

</html>

<?php
$email = $_POST['email'];
$token = $_POST['token'];
$senha = $_POST['senha'];
$senha_nova = $_POST['senha_repetir'];

require_once "../function/function.php";
$conexao = conn();

$sql = "SELECT * FROM recuperar_senha WHERE email ='$email' AND token ='$token'";
$result = executarSQL($conexao, $sql);

$recuperar = mysqli_fetch_assoc($result);

if ($recuperar == null) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Email ou token incorreto. Tente fazer um novo pedido de recuperação de senha'
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>";
    die();
} else {
    date_default_timezone_set('America/Sao_Paulo');
    $agora = new DateTime('now');
    $data_criacao = DateTime::createFromFormat('Y-m-d H:i:s', $recuperar['data_criacao']);

    $Umdia = DateInterval::createFromDateString('1 day');
    $dataExpiracao = date_add($data_criacao, $Umdia);

    if ($agora > $dataExpiracao) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Essa solicitação de recuperação de senha expirou! Faça um novo pedido de recuperação de senha'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        die();
    }
    if ($recuperar['usado'] == 1) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Esse pedido de recuperação de senha já foi utilizado anteriormente! Faça um novo pedido de recuperação de senha.'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        die();
    }
    if ($senha != $senha_nova) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'A senha que você digitou é diferente da senha que você digitou no repetir senha. Por favor, tente novamente.'
            }).then(() => {
                window.history.back();
            });
        </script>";
        die();
    }

    $sql2 = "UPDATE usuario SET senha = '$senha' WHERE email = '$email'";
    executarSQL($conexao, $sql2);
    $sql3 = "UPDATE recuperar_senha SET usado = 1 WHERE email = '$email' AND token = '$token'";
    executarSQL($conexao, $sql3);

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: 'Nova senha cadastrada com sucesso! Faça o login para acessar o sistema.'
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>";
}
?>

