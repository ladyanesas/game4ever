<?php
require '../Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader ();
$app = new \Slim\Slim ();
$app->response ()->header ( 'Content-Type', 'application/json;charset=utf-8' );

$app->get ( '/', function () {
	echo "Teste Jogos Nintendo";
} );

$app->get ( '/jogos', 'getJogos' );
$app->get ( '/jogos/:nome', 'getNome' );
$app->get ( '/jogos/:genero', 'getGenero' );
$app->post ( '/jogos', 'addJogo' );

$app->run ();
function getConn() {
	return new PDO ( 'mysql:host=localhost;dbname=bign', 'root', '', array (
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" 
	) );
}

// #######Listar Jogos - POST#######
function getJogos() {
	$stmt = getConn ()->query ( "SELECT * FROM jogos" );
	$jogos = $stmt->fetchAll ( PDO::FETCH_OBJ );
	echo "Lista Geral de Jogos<br><br>";
	echo "{jogos:" . json_encode ( $jogos ) . "}";
}

// #########Filtrar por Nome- POST###########
function getNome($nome) {
	$conn = getConn ();
	$sql = "SELECT * FROM jogos WHERE nome like'%$nome%'";
	$stmt = $conn->prepare ( $sql );
	$stmt->bindParam ( "nome", $jogo->Nome );
	$stmt->execute ();
	$jogo->jogo = $stmt->fetchObject ();
		
	echo "Lista por nome<br><br>";
	echo json_encode ( $jogo );
}

// #########Filtrar por Genero- POST###########
function getGenero($genero) {
	$conn = getConn ();
	$sql = "SELECT * FROM jogos WHERE genero = '$genero'";
	$stmt = $conn->prepare ( $sql );
	$stmt->bindParam ( "genero", $jogo->Genero );
	$stmt->execute ();
	$jogo->jogo = $stmt->fetchObject ();
	
	echo "Lista por Genero:<br><br>";
	echo json_encode ( $jogo );
}

// ##########Cadastrar Jogos - GET##########
function addJogo() {
	$request = \Slim\Slim::getInstance ()->request ();
	$jogo = json_decode ( $request->getBody () );
	$sql = "INSERT INTO jogos (nome,genero,plataforma) values (:nome,:genero,:plataforma) ";
	$conn = getConn ();
	$stmt = $conn->prepare ( $sql );
	$stmt->bindParam ( "nome", $jogo->nome );
	$stmt->bindParam ( "genero", $jogo->genero );
	$stmt->bindParam ( "plataforma", $jogo->plataforma );
	$stmt->execute ();
	$jogo->id_jogo = $conn->lastInsertId ();
	echo "Cadastro Realizado com sucesso!<br><br>";
	echo json_encode ( $jogo );
}

?>