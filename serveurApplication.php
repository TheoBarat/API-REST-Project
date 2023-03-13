<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
require_once('jwt_utils.php');
require_once('SQL.php');
$sql = new RequeteSQL();

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

	switch ($http_method) {
		/// Cas de la méthode GET
		case "GET":
			/// Récupération des critères de recherche envoyés par le Client
			if($payload->role == "publisher") {
				if (!empty($_GET['id'])) {
					$matchingData = $sql->getArticle($_GET['id']);
				} else {
					$matchingData = $sql->getAllArticle();
				}
				/// Envoi de la réponse au Client
				deliver_response(200, "Bien affiché", $matchingData);
			} else {
				if (!empty($_GET['id'])) {
					$matchingData = $sql->getArticle($_GET['id']);
				} else {
					$matchingData = $sql->getAllArticle();
				}
				/// Envoi de la réponse au Client
				deliver_response(401, "Vous n'avez pas les droits", NULL);
			}
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
//pour les personnes non authentifiées
} else {
	if($http_method == "GET") {
		//Consulter les messages existants. Seules les informations suivantes doivent être disponibles : auteur, date de publication, contenu.
		$matchingData = $sql->getAllArticle();
		$result = array();
		foreach($matchingData as $value) {
			$login = $sql->getUsername($value['Id_User']);
			$data = array(
				'auteur' => $login[0]['Login'],
				'date' => date('d/m/Y', strtotime($value['Date_Publication'])),
				'heure' => $value['Heure_Publication'],
				'contenu' => $value['Contenu']
			);
			array_push($result, $data); // ajoute le tableau $data au tableau $result
		}
		/// Envoi de la réponse au Client
		deliver_response(200, "Bien affiché", $result);
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
