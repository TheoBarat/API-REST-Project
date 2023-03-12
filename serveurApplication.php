<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
require_once('jwt_utils.php');
require_once('SQL.php');
$sql = new requeteSQL();

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
$bearer_token = '';
///Recherche du token dans la requête
$bearer_token = get_bearer_token();

/// Vérification de la présence d'un token
if(is_jwt_valid($bearer_token)) {
	/// Récupération des données du token
	$payload = get_payload($bearer_token);
	$role = $payload->role;

	if ($role == "publisher"){
		switch($http_method){
			case "GET":
				/// Récupération des critères de recherche envoyés par le Client
				if (!empty($_GET['id'])){
					$matchingData = $sql -> getArticle($_GET['id']);
				} else {
					$matchingData = $sql -> getAllArticle();
				}
				deliver_response(200, "Bien affiché", $matchingData);
				break;
			
			case "POST":
				/// Récupération des données envoyées par le Client
				$postedData = file_get_contents('php://input');
				$data = json_decode($postedData, true);
				$auteur = $data['Auteur'];
				$contenu = $data['Contenu'];
				$sql -> insertArticle($auteur,$conteu);
				deliver_response(201, "Ajout réussi", NULL);
				break;

			case "PUT":
				/// Récupération des données envoyées par le Client
				$postedData = file_get_contents('php://input');
				break;

			case "DELETE":
				break;

			default:
				/// Envoi de la réponse au Client
				deliver_response(405, "Méthode non supportée", NULL);
				break;
		}
	} else if ($role == "moderator"){
		switch($http_method){

		}
	}
//pour les personnes non authentifiées
} else {
	if($http_method == "GET") {
		$matchingData = $sql->GETALL();
		/// Envoi de la réponse au Client
		deliver_response(200, "Bien affiché", $matchingData);
	} else {
		/// Envoi de la réponse au Client
		deliver_response(401, "Vous n'avez pas les droits", NULL);
	}
}

/// Envoi de la réponse au Client
function deliver_response($status, $status_message, $data)
{
	/// Paramétrage de l'entête HTTP, suite
	header("HTTP/1.1 $status $status_message");
	/// Paramétrage de la réponse retournée
	$response['status'] = $status;
	$response['status_message'] = $status_message;
	$response['data'] = $data;
	/// Mapping de la réponse au format JSON
	$json_response = json_encode($response);
	echo $json_response;
}

?>
