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
if ($bearer_token != null){
	if (is_jwt_valid($bearer_token)) {
		/// Récupération des données du token
		$payload = get_payload($bearer_token);
		$role = $payload->role;

		if ($role == "publisher") {
			switch ($http_method) {
				case "GET":
					/// Récupération des critères de recherche envoyés par le Client
					if (!empty($_GET['traitement'])) { // Article en fonction de l'auteur (login de l'auteur)
						$traitement = $_GET['traitement'];
						switch ($traitement) {
							case 'author':
								$matchingData = $sql -> getArticlesByUser($_GET['user'], $role);
								break;
							case 'article':
								$matchingData = $sql -> getArticle($_GET['idArticle'], $role);
								break;
							case 'mesArticles':
								$matchingData = $sql -> getArticlesByUser($payload -> login, $role);
								break;
							default:
								deliver_response(400, "Mauvaise requête", null);
								exit;
						}
					} else {
						$matchingData = $sql -> getAllArticle($role);
					}
					deliver_response(200, "Bien affiché", $matchingData);
					break;
				
				case "POST":
					if (!empty($_GET['traitement'])) {
						$traitement = $_GET['traitement'];
						switch ($traitement) {
							case 'ajouterArticle':
								$postedData = file_get_contents('php://input');
								$data = json_decode($postedData, true);
								if ($data['Contenu'] == null) {
									deliver_response(400, "Mauvaise requête", null);
									exit;
								}
								
								$auteur = $payload -> login;
								$contenu = $data['Contenu'];
								$sql -> insertArticle($auteur, $contenu);
								break;
							default :
								deliver_response(400, "Mauvaise requête", null);
								break;
						}
					}
					deliver_response(201, "Ajout réussi ", null);
					break;

				case "PUT":
					/// Récupération des données envoyées par le Client
					$postedData = file_get_contents('php://input');
					break;

				case "DELETE":
					if ($_GET['traitement'] == "supprimerMesArticles") {
						$sql ->deleteArticlesUser($payload -> login);
						deliver_response(200, "Bien supprimé", NULL);
					} else {
						deliver_response(400, "Mauvaise requête", NULL);
					}
					break;

				default:
					/// Envoi de la réponse au Client
					deliver_response(405, "Méthode non supportée", null);
					break;
			}
		} elseif ($role == "moderator") {
			switch ($http_method) {

				case "GET":
					if (!empty($_GET['traitement'])) { // Article en fonction de l'auteur (login de l'auteur)
						$traitement = $_GET['traitement'];
						switch ($traitement) {
							case 'author':
								$matchingData = $sql -> getArticlesByUser($_GET['user'], $role);
								break;
							case 'article':
								$matchingData = $sql -> getArticle($_GET['idArticle'], $role);
								break;
							default:
								deliver_response(400, "Mauvaise requête", NULL);
								exit;
						}
					} else {
						$matchingData = $sql -> getAllArticle($role);
					}
					deliver_response(200, "Bien affiché Moderator", $matchingData);
					break;

				case "PUT":
					/// Récupération des données envoyées par le Client
					$postedData = file_get_contents('php://input');
					break;

				case "DELETE":
					/// Récupération de l'identifiant de la ressource envoyé par le Client
					if ($_GET['traitement'] == "supprimerArticle") {
						$sql ->deleteArticle($GET_['id']);
						deliver_response(200, "Bien supprimé", NULL);
					} else {
						deliver_response(400, "Mauvaise requête", NULL);
					}
					break;

				default:
					/// Envoi de la réponse au Client
					deliver_response(405, "Méthode non supportée", NULL);
					break;
			}
		}
	} else { // Si le token n'est pas valide
		deliver_response(401, "Token invalide", NULL);
	}

} else { // Si le client n'est pas identifié
	if ($http_method == "GET") {
		if (!empty($_GET['traitement'])) { // Article en fonction de l'auteur (login de l'auteur)
			$traitement = $_GET['traitement'];
			switch ($traitement) {
				case 'author':
					$matchingData = $sql -> getArticlesByUser($_GET['user']);
					break;
				case 'article':
					$matchingData = $sql -> getArticle($_GET['idArticle']);
					break;
				default:
					deliver_response(400, "Mauvaise requête", NULL);
					exit;
			}
		} else {
        	$matchingData = $sql->getAllArticle(); //Consulter les messages existants (auteur, date de publication, contenu)
		}
        deliver_response(200, "Bien affiché user", $matchingData); /// Envoi de la réponse au Client
    } else {
        /// Envoi de la réponse au Client
        deliver_response(405, "Méthode non supportée", NULL);
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
