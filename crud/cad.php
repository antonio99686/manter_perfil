<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Cadastro</title>
</head>

<body>

<?php
require_once "../function/function.php";
$conexao = conn();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome'], $_POST['senha'], $_POST['email'])) {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $img_padrao = 'user.jpg'; // Nome da imagem padrão

    // Insert user data into the database with a default image
    $sql = "INSERT INTO usuario (nome, senha, email, perfil_img) VALUES ('$nome', '$senha', '$email', '$img_padrao')";
    if (executarSQL($conexao, $sql)) {
        // Get the last inserted user ID
        $id_usuario = mysqli_insert_id($conexao);

        // Verifica se um arquivo foi enviado via formulário
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = $id_usuario . '.' . $fileExtension;

            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = '../img/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $updateSql = "UPDATE usuario SET perfil_img = '$newFileName' WHERE id_usuario = $id_usuario";
                    executarSQL($conexao, $updateSql);

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Imagem atualizada com sucesso!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../perfil.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Erro ao mover o arquivo enviado',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../perfil.php';
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Formato de arquivo não suportado',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '../perfil.php';
                    });
                </script>";
            }
        } else {
            // Redirect if no file was uploaded
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Usuário cadastrado com sucesso!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '../perfil.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Falha ao cadastrar Usuário.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '../perfil.php';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Atenção!',
            text: 'Dados do formulário incompletos.',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = '../perfil.php';
        });
    </script>";
}
?>

</body>
</html>
