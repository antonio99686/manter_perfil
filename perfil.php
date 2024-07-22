<?php
session_start();
require_once "function/function.php";
$conexao = conn();

// Verifica se a sessão está iniciada e se o usuário está logado
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: index.html");
    exit();
}

// Obtém o ID do usuário da sessão
$id_user = $_SESSION['id_user'];

// Consulta SQL para obter os dados do usuário utilizando prepared statements para evitar injeção de SQL
$sql = "SELECT * FROM usuario WHERE id_user = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Verifica se a consulta foi bem-sucedida
if (!$resultado) {
    $_SESSION['mensagem'] = "Erro ao consultar o banco de dados: " . mysqli_error($conexao);
    $_SESSION['tipo_mensagem'] = "error";
    $_SESSION['titulo_mensagem'] = "Erro!";
    header("Location: perfil.php");
    exit();
}

// Obtém os dados do usuário
$dados = mysqli_fetch_assoc($resultado);

// Verifica se um arquivo foi enviado via formulário
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $newFileName = $id_user . '.' . $fileExtension;

    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'jfif');
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadFileDir = 'img/';

        // Verifica se o diretório de upload existe, senão, cria-o
        if (!file_exists($uploadFileDir) && !is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true); // Cria o diretório recursivamente com permissões 0777
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $updateSql = "UPDATE usuario SET perfil_img = '$newFileName' WHERE id_user = $id_user";
            executarSQL($conexao, $updateSql);

            $_SESSION['mensagem'] = "Foto de perfil atualizada com sucesso!";
            $_SESSION['tipo_mensagem'] = "success";
            $_SESSION['titulo_mensagem'] = "Sucesso!";
            header("Location: perfil.php");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao mover o arquivo enviado para o diretório de destino.";
            $_SESSION['tipo_mensagem'] = "error";
            $_SESSION['titulo_mensagem'] = "Erro!";
            header("Location: perfil.php");
            exit();
        }
    } else {
        $_SESSION['mensagem'] = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        $_SESSION['tipo_mensagem'] = "error";
        $_SESSION['titulo_mensagem'] = "Erro!";
        header("Location: perfil.php");
        exit();
    }
}

// Verifica se a solicitação é para excluir a foto do perfil
if (isset($_POST['delete_photo'])) {
    $defaultImage = 'default.jpg'; // Define a imagem padrão
    $deleteSql = "UPDATE usuario SET perfil_img = ? WHERE id_user = ?";
    $stmt = mysqli_prepare($conexao, $deleteSql);
    mysqli_stmt_bind_param($stmt, "si", $defaultImage, $id_user);
    mysqli_stmt_execute($stmt);

    // Remove o arquivo de imagem atual, se não for a imagem padrão
    if ($dados['perfil_img'] !== $defaultImage && file_exists('img/' . $dados['perfil_img'])) {
        unlink('img/' . $dados['perfil_img']);
    }

    $_SESSION['mensagem'] = "Foto de perfil excluída com sucesso!";
    $_SESSION['tipo_mensagem'] = "success";
    $_SESSION['titulo_mensagem'] = "Sucesso!";
    header("Location: perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="shortcut icon" href="icon/icon.png">
    <link rel="stylesheet" href="css/perfil.css">
    <title>Manter Perfil</title>
</head>

<body>
    <div class="container">
        <aside>
            <div class="toggle">
                <div class="logo">
                    <h2> Projeto de <span class="danger">Manter Perfil</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="perfil.php" class="active">
                    <span class="material-icons-sharp">
                        person_outline
                    </span>
                    <h3>Perfil</h3>
                </a>
                <a href="logout.php">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

        <main>
            <h1>Perfil</h1>
            <div class="box">
                <h2>Dados Usuário</h2>
                <br>
                <div class="user"><em><b>Nome:</b></em> <?php echo $dados['nome'] ?></div>
                <br>
                <div class="user2"><em><b>E-mail:</b></em> <?php echo $dados['email'] ?></div>
                <br>
                <div class="user3"><em><b>Senha:</b></em> <?php echo $dados['senha'] ?></div>
                <br>
                <img class="ims" src="img/<?php echo $dados['perfil_img'] ?>">
            </div>
        </main>

        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp ">
                        light_mode
                    </span>
                    <span class="material-icons-sharp">
                        dark_mode
                    </span>
                </div>
                <div class="profile">
                    <div class="info">
                        <p>Olá, <b>Bem-Vindo(a)</b></p>
                        <small class="text-muted"><?php echo $dados['nome'] ?></small>
                    </div>
                    <div class="profile-photo">
                        <img src="img/<?php echo $dados['perfil_img'] ?>" alt="user" id="profile-picture">
                    </div>
                </div>
            </div>

            <div class="box-perfil" style="margin-top: 20px;">
                <h2>Alterar Foto de Perfil</h2>
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="file" id="avatar-image">
                    <img class="box-perfil-img" id="preview-image" src="#" alt="Preview">
                    <button type="submit" class="form-control" style="margin-top: 10px;">Salvar Foto</button>
                </form>
            </div>

            <div class="box-perfil" style="margin-top: 20px;">
                <h2>Excluir Foto de Perfil</h2>
                <form method="post">
                    <input type="hidden" name="delete_photo" value="1">
                    <button type="submit" class="form-control" style="margin-top: 10px;">Excluir Foto</button>
                </form>
            </div>
            <div class="box-perfil" style="margin-top: 20px;">
                <h2>fotos</h2>
                <?php echo $dados['perfil_img'] ?>
            </div>
        </div>
    </div>
    <script src="JavaScript/perfil.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const avatarImage = document.querySelector('#avatar-image');
        const previewImage = document.querySelector('#preview-image');
        const profilePicture = document.querySelector('#profile-picture');

        avatarImage.addEventListener('change', event => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                    previewImage.style.display = 'block';
                    profilePicture.src = event.target.result;
                }

                reader.readAsDataURL(file);
            } else {
                previewImage.src = '#';
                previewImage.style.display = 'none';
            }
        });

        <?php if (isset($_SESSION['mensagem'])): ?>
            Swal.fire({
                title: "<?php echo $_SESSION['titulo_mensagem']; ?>",
                text: "<?php echo $_SESSION['mensagem']; ?>",
                icon: "<?php echo $_SESSION['tipo_mensagem']; ?>"
            });
            <?php
            unset($_SESSION['mensagem']);
            unset($_SESSION['tipo_mensagem']);
            unset($_SESSION['titulo_mensagem']);
            ?>
        <?php endif; ?>
    </script>
</body>

</html>
