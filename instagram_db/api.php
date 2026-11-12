<?php

header("Content-Type: application/json;charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require "config.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

$corpo = json_decode(file_get_contents("php://input"), true) ?? [];

function teste(){
    echo json_encode(["mensagem" => "Back-end respondendo com sucesso!"]);
    exit;
}

function createUsuario($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO Usuarios (NOME, USERNAME, EMAIL, SENHA) VALUES (?,?,?,?)"
    );
    $stmt->execute([$dados["nome"],
                    $dados["username"],
                    $dados["email"],
                    $dados["senha"]]);
    http_response_code(201);
    echo json_encode(
        ["mensagem" => "Usuário criado com sucesso!",
        "id" => $con-> lastInsertID()]
    );
}

function readUsuario($con){
    $stmt = $con->query("SELECT * FROM Usuarios");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}

function updateUsuario($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE Usuarios SET nome = ?, username = ?, email = ?, senha = ? WHERE id_usuario = ?"
    );
    $stmt -> execute([  $dados["nome"],
                        $dados["username"],
                        $dados["email"],
                        $dados["senha"],
                        $id]);
    echo json_encode(["mensagem" => "Usuário alterado com sucesso!"]);
}

function deleteUsuario($con, $id){
    $stmt = $con->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Usuário removido com sucesso!"]);
}

function createCurtida($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO Curtidas (id_usuario, id_publicacao) VALUES (?,?)"
    );
    $stmt->execute([$dados["id_usuario"],
                    $dados["id_publicacao"]
                    ]);
    http_response_code(201);
    echo json_encode(
        ["mensagem" => "Curtida criada com sucesso!",
        "id" => $con-> lastInsertID()]
    );
}

function readCurtida($con){
    $stmt = $con->query("SELECT * FROM Curtidas");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}

function updateCurtida($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE Curtidas SET id_usuario = ? WHERE id_curtida = ?"
    );
    $stmt -> execute([  $dados["id_usuario"],
                        $id]);
    echo json_encode(["mensagem" => "Curtida alterada com sucesso!"]);
}

function deleteCurtida($con, $id){
    $stmt = $con->prepare("DELETE FROM Curtidas WHERE id_curtida = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Curtida removida com sucesso!"]);
}

function createPublicacao($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO Publicacoes (id_usuario, legenda, imagem_url) VALUES (?,?,?)"
    );
    $stmt->execute([$dados["id_usuario"],
                    $dados["legenda"],
                    $dados["imagem_url"]]);
    http_response_code(201);
    echo json_encode(
        ["mensagem" => "Publicação criada com sucesso!",
        "id" => $con-> lastInsertID()]
    );
}

function readPublicacao($con){
    $stmt = $con->query("SELECT * FROM Publicacoes");
    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
}

function updatePublicacao($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE Publicacoes SET legenda= ? WHERE id_publicacao = ?"
    );
    $stmt -> execute([  $dados["legenda"],
                        $id]);
    echo json_encode(["mensagem" => "Legenda alterada com sucesso!"]);
}

function deletePublicacao($con, $id){
    $stmt = $con->prepare("DELETE FROM Publicacoes WHERE id_publicacao = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Publicação removida com sucesso!"]);
}

if ($rota === "usuarios") {
    switch($metodo){
        case "POST":
            createUsuario($con, $corpo);
            break;
        case "GET":
            readUsuario($con);
            break;
        case "PUT":
            updateUsuario($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteUsuario($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}else if ($rota === "curtidas") {
    switch($metodo){
        case "POST":
            createCurtida($con, $corpo);
            break;
        case "GET":
            readCurtida($con);
            break;
        case "PUT":
            updateCurtida($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteCurtida($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}else if ($rota === "publicacoes") {
    switch($metodo){
        case "POST":
            createPublicacao($con, $corpo);
            break;
        case "GET":
            readPublicacao($con);
            break;
        case "PUT":
            updatePublicacao($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deletePublicacao($con, $_GET["id"]);
            break;
        default:
            teste();
    }
}
?>