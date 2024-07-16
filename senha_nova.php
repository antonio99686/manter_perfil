<?php
$email = $_GET['email'];
$token = $_GET['token'];
require_once "../function/function.php";
$conexao = conn();

$sql = "SELECT * FROM recuperar_senha WHERE email ='$email' AND token ='$token'";
$result = executarSQL($conexao, $sql);

$recuperar = mysqli_fetch_assoc($result);

if ($recuperar == null) {
    echo "Email ou token incorreto. Tente fazer um novo pedido de recuperação de senha";
    die();
} else {
    date_default_timezone_set('America/Sao_Paulo');
    $agora = new DateTime('now');
    $data_criacao = DateTime::createFromFormat('Y-m-d H:i:s', $recuperar['data_criacao']);

    $Umdia = DateInterval::createFromDateString('1 day');
    $dataExpiracao = date_add($data_criacao, $Umdia);

    if ($agora > $dataExpiracao) {
        echo "Essa solicitação de recuperação de senha expirou! Faça um novo pedido de recuperação de senha";
        die();
    }
    if ($recuperar['usado'] == 1) {
        echo "Esse pedido de recuperação de senha já foi utilizado 
        anteriormente! Para recuperar a senha faça um novo pedido
        de recuperação de senha.";
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="icon/icon.png">

    <link rel="stylesheet" href="../css/style.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7-beta.16/jquery.inputmask.min.js"></script>
    <title>Recuperar Senha</title>
</head>

<body>

    <div class="container" id="container">

        <div class="form-container sign-in" style="position: relative; top: 30%;">
            <h2>Recuperão de Senha</h2>
            <form action="salvar_senha.php" method="POST" id="resetPasswordForm">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                Email: <?php echo $email; ?><br>

                <label for="senha">
                    <input type="password" name="senha" placeholder="Nova Senha">
                </label>
                <br>
                <label for="senha_repetir">
                    <input type="password" name="senha_repetir" placeholder="Repita a Nova Senha">
                </label>
                <br>
                <button type="submit">Salvar nova senha</button>
            </form>
        </div>
        
    </div>

    <script>
        $(document).ready(function() {
            $('#resetPasswordForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: ,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '../index.php'; // Redirect to index.php after success
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: xhr.responseText
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
