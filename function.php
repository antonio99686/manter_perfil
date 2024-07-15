<?php
function conectar()
{
    require_once "config.php";
    $conexao = mysqli_connect(
        $config['host'],
        $config['user'],
        $config['pass'],
        $config['db']
    );

    if ($conexao === false) {
        echo "Erro ao conectar á base dados.N° do erro" .
            mysqli_connect_errno() . "." .
            mysqli_connect_error();
        exit;
    }
    return $conexao;
}


function executarSQL($conexao, $sql)
{
    $result = mysqli_query($conexao, $sql);
    if ($result === false) {
        echo "Erro ao executar o comando SQL. " . mysqli_errno($conexao) . ": " . mysqli_error($conexao);
        die(); // Stop further execution if there's an error
    }
    return $result;
}
