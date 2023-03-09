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
	switch ($http_method) {
			/// Cas de la méthode GET
		case "GET":
			/// Récupération des critères de recherche envoyés par le Client
			if (!empty($_GET['id'])) {
				$matchingData = $sql->GET($_GET['id']);
			} else {
				$matchingData = $sql->GETALL();
			}
			/// Envoi de la réponse au Client
			deliver_response(200, "Bien affiché", $matchingData);
			break;
		/// Cas de la méthode POST
		case "POST":
			/// Récupération des données envoyées par le Client
			$postedData = file_get_contents('php://input');
			break;
		/// Cas de la méthode PUT
		case "PUT":
			/// Récupération des données envoyées par le Client
			$postedData = file_get_contents('php://input');
			break;
		/// Cas de la méthode DELETE
		case "DELETE":
			break;
		/// Cas par défaut
		default:
			/// Envoi de la réponse au Client
			deliver_response(405, "Méthode non supportée", NULL);
			break;
	}
} else {
	/// Envoi de la réponse au Client
	deliver_response(401, "Token invalide", NULL);
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
