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

    public function getArticle($idArticle)
    {
        $req = $this->linkpdo->prepare("SELECT * FROM article where Id_Article = :idArticle");
        $req->execute(array(
            'idArticle' => $idArticle
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByUser($loginUser, $role)
    {
        if ($role == "moderator") {
            $req = $this->linkpdo->prepare("SELECT * FROM article, users where article.id_user = users.id_user AND users.login = :loginUser");
            $req->execute(array(
                'loginUser' => $loginUser
            ));
        } elseif ($role == "publisher") {
            $req = $this->linkpdo->prepare("SELECT users.login, article.date_publication, article.contenu, count(a_like)  FROM article, users, interagir where article.id_user = users.id_user AND users.id_users = interagir.id_users AND article.id_article = interagir.id_article AND users.login = :loginUser");
            $req->execute(array(
                'loginUser' => $loginUser
            ));
        }
        

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsername($idUser){
        $req = $this->linkpdo->prepare("SELECT Login FROM `Users` WHERE Id_User = :idUser");
        $req->execute(array(
            'idUser' => $idUser
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    FONCTIONS D'AJOUT DANS LA BDD
    */

    public function insertArticle($auteur,$contenu){
        $req = $this->linkpdo->prepare('INSERT INTO chuckn_facts VALUES (NULL,:contenu,:,now(),:auteur)');
        $testreq = $req->execute(array(
            'auteur' => $auteur,
            'contenu' => $contenu
        ));
        if ($testreq == false) {
            die("Erreur insertArticle");
        }
    }

    /*
    FONCTIONS DE MODIFICATION DANS LA BDD
    */

    //fonction pour supprimer un article
    public function deleteArticle($idArticle){
        $req = $this->linkpdo->prepare('DELETE FROM article WHERE Id_Article = :idArticle');
        $testreq = $req->execute(array(
            'idArticle' => $idArticle
        ));
        if ($testreq == false) {
            die("Erreur deleteArticle");
        }
    }

    /*
    FONCTION SUPPRIMER DE LA BDD
    */

}
?>