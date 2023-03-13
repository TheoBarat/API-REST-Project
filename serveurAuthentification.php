<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
require_once('jwt_utils.php');
require_once('SQL.php');
$sql = new RequeteSQL();

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method) {
		/// Cas de la méthode POST
	case "POST":
		/// Récupération des données envoyées par le Client
		$postedData = file_get_contents('php://input');
		$users = json_decode($postedData, true);
		$login = $users['login'];
        $password = $users['password'];
		$role = $users['role'];
		if($sql->getConnection($login,$password,$role)){
            $headers = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'login' => $login,
				'role' => $role,
                'exp' => time() + 86400,
            );
            $token = generate_jwt($headers,$payload);
            /// Envoi de la réponse au Client
            deliver_response(200, "Bien connecté", $token);
        }
        else{
            /// Envoi de la réponse au Client
            deliver_response(401, "Mauvais identifiants", NULL);
        }
		break;
		/// Cas par défaut
	default:
		/// Envoi de la réponse au Client
		deliver_response(405, "Méthode non supportée", NULL);
		break;
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
