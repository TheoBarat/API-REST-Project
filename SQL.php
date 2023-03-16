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

    public function getAllArticle($role = null)
    {
        switch ($role){
            case "moderator":
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user");
                $reqArticle -> execute();
                $reqArticle = $reqArticle -> fetchAll(PDO::FETCH_ASSOC);
                $result = array();
                foreach ($reqArticle as $value) {
                    $data = array(
                        'Auteur' => $value['login'],
                        'Date' => date('d/m/Y', strtotime($value['date_publication'])),
                        'Contenu' => $value['contenu'],
                        'NbLike' => $this-> getNbLike($value['id_article']),
                        'UserLike' => $this-> getUserLike($value['id_article']),
                        'NbDislike' => $this-> getNbDislike($value['id_article']),
                        'UserDislike' => $this-> getUserDislike($value['id_article'])
                    );
                    array_push($result, $data); // ajoute le tableau $data au tableau $result
                }
                break;
            case "publisher":
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user");
                $reqArticle -> execute();
                $reqArticle = $reqArticle -> fetchAll(PDO::FETCH_ASSOC);
                $result = array();
                foreach ($reqArticle as $value) {
                    $data = array(
                        'Auteur' => $value['login'],
                        'Date' => date('d/m/Y', strtotime($value['date_publication'])),
                        'Contenu' => $value['contenu'],
                        'NbLike' => $this-> getNbLike($value['id_article']),
                        'NbDislike' => $this-> getNbDislike($value['id_article'])
                    );
                    array_push($result, $data); // ajoute le tableau $data au tableau $result
                }
                break;
            default :
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user");
                $reqArticle -> execute();
                $reqArticle = $reqArticle -> fetchAll(PDO::FETCH_ASSOC);
                $result = array();
                foreach ($reqArticle as $value) {
                    $data = array(
                        'Auteur' => $value['login'],
                        'Date' => date('d/m/Y', strtotime($value['date_publication'])),
                        'Contenu' => $value['contenu']
                    );
                    array_push($result, $data); // ajoute le tableau $data au tableau $result
                }
                break;
        }
        return $result;
    }

    public function getArticle($idArticle)
    {
        $req = $this->linkpdo->prepare("SELECT * FROM article where Id_Article = :idArticle");
        $req->execute(array(
            'idArticle' => $idArticle
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByUser($loginUser, $role = null)
    {
        switch ($role){
            case "moderator":
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user AND users.login = :loginUser");
                $reqArticle -> execute(array(
                    'loginUser' => $loginUser
                ));
                $reqArticle = $reqArticle -> fetchAll(PDO::FETCH_ASSOC);
                $result = array();
                foreach ($reqArticle as $value) {
                    $data = array(
                        'Auteur' => $value['login'],
                        'Date' => date('d/m/Y', strtotime($value['date_publication'])),
                        'Contenu' => $value['contenu'],
                        'NbLike' => $this-> getNbLike($value['id_article']),
                        'UserLike' => $this-> getUserLike($value['id_article']),
                        'NbDislike' => $this-> getNbDislike($value['id_article']),
                        'UserDislike' => $this-> getUserDislike($value['id_article'])
                    );
                    array_push($result, $data); // ajoute le tableau $data au tableau $result
                }
                break;
            case "publisher":
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user AND users.login = :loginUser");
                $reqArticle -> execute(array(
                    'loginUser' => $loginUser
                ));
                $reqArticle = $reqArticle -> fetchAll(PDO::FETCH_ASSOC);
                $result = array();
                foreach ($reqArticle as $value) {
                    $data = array(
                        'Auteur' => $value['login'],
                        'Date' => date('d/m/Y', strtotime($value['date_publication'])),
                        'Contenu' => $value['contenu'],
                        'NbLike' => $this-> getNbLike($value['id_article']),
                        'NbDislike' => $this-> getNbDislike($value['id_article'])
                    );
                    array_push($result, $data); // ajoute le tableau $data au tableau $result
                }
                break;
            default :
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user AND users.login = :loginUser");
                $reqArticle -> execute(array(
                    'loginUser' => $loginUser
                ));
                $reqArticle = $reqArticle -> fetchAll(PDO::FETCH_ASSOC);
                $result = array();
                foreach ($reqArticle as $value) {
                    $data = array(
                        'Auteur' => $value['login'],
                        'Date' => date('d/m/Y', strtotime($value['date_publication'])),
                        'Contenu' => $value['contenu']
                    );
                    array_push($result, $data); // ajoute le tableau $data au tableau $result
                }
                break;
        }
        return $result;
    }

    public function getUsername($idUser){
        $req = $this->linkpdo->prepare("SELECT Login FROM `Users` WHERE Id_User = :idUser");
        $req->execute(array(
            'idUser' => $idUser
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLike($id_Article){
        $req = $this -> linkpdo -> prepare ("SELECT users.login FROM interagir, users WHERE interagir.id_user = users.id_user AND interagir.id_article = :idArticle AND interagir.a_like = 1");
        $req -> execute(array(
            'idArticle' => $id_Article
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserDislike($id_Article){
        $req = $this -> linkpdo -> prepare ("SELECT users.login FROM interagir, users WHERE interagir.id_user = users.id_user AND interagir.id_article = :idArticle AND interagir.a_like = -1");
        $req -> execute(array(
            'idArticle' => $id_Article
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNbLike($id_Article){
        $req = $this->linkpdo->prepare("SELECT COUNT(*) FROM interagir WHERE interagir.id_article = :idArticle AND a_like = 1");
        $req->execute(array(
            'idArticle' => $id_Article
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
    }

    public function getNbDislike($id_Article){
        $req = $this->linkpdo->prepare("SELECT COUNT(*) FROM interagir WHERE interagir.id_article = :idArticle AND a_like = -1");
        $req->execute(array(
            'idArticle' => $id_Article
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
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