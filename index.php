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
$app->put('/upJogos/:id','upJogo');

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

//#########update dos jogos
 function upJogo($id)
       {
               $request = \Slim\Slim::getInstance()->request();
               $jogo = json_decode($request->getBody());
               $sql = "UPDATE jogos SET nome_jogo=:nome_jogo,idioma=:idioma,regiao=:regiao,genero=:genero,indicacao=:indicacao,plataforma=:plataforma,sinopse=:sinopse,valor_medio_jogo=:valor_medio_jogo,imagem_jogo=:imagem_jogo,dataInclusao=:dataInclusao,data_lancamento=:data_lancamento";
               $conn = getConn();
               $stmt = $conn->prepare($sql);
               $stmt->bindParam("id_jogo",$jogo->id_jogo);
               $stmt->bindParam("versao",$jogo->versao);
               $stmt->bindParam("nome_jogo",$jogo->nome_jogo);
               $stmt->bindParam("idioma",$jogo->idioma);
               $stmt->bindParam("regiao",$jogo->regiao);
               $stmt->bindParam("genero",$jogo->genero);
               $stmt->bindParam("indicacao",$jogo->indicacao);
               $stmt->bindParam("plataforma",$jogo->plataforma);
               $stmt->bindParam("sinopse",$jogo->sinopse);
               $stmt->bindParam("valor_medio_jogo",$jogo->valor_medio_jogo);
               $stmt->bindParam("imagem_jogo",$jogo->imagem_jogo);
               $stmt->bindParam("data_lancamento",$jogo->data_lancamento);
               $stmt->execute();
               echo json_encode($jogo);
       }


?>