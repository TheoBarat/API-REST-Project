<?php
class RequeteSQL {

    private $linkpdo;

    public function __construct()
    {
        ///Connexion au serveur MySQL avec PDO
        $server = 'localhost';
        $login  = 'root';
        $mdp    = '';
        $db     = 'blog';

        try {
            $this->linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);
            $this->linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        ///Capture des erreurs éventuelles
        catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
 
    /*
    FONCTIONS D'INTERROGATION DE LA BDD
    */

    public function getConnection($login,$password,$role){
        $req = $this->linkpdo->prepare("SELECT * FROM `Users` WHERE login = :login AND password = :password AND role = :role");
        $req->execute(array(
            'login' => $login,
            'password' => $password,
            'role' => $role
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllArticle()
    {
        $req = $this->linkpdo->prepare("SELECT * FROM article");
        $req->execute();

        return $req->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function getArticle($id)
    {
        $req = $this->linkpdo->prepare("SELECT * FROM article where Id_Article = :id");
        $req->execute(array(
            'id' => $id
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    FONCTIONS D'AJOUT DANS LA BDD
    */

    /*
    FONCTIONS DE MODIFICATION DANS LA BDD
    */

    /*
    FONCTION SUPPRIMER DE LA BDD
    */

}
?>