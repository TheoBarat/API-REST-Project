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
        $req = $this->linkpdo->prepare("SELECT * FROM `users` WHERE login = :login AND password = :password AND role = :role");
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
                        'ArticleID' => $value['id_article'],
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
                        'ArticleID' => $value['id_article'],
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

    public function getArticle($idArticle, $role = null)
    {
        switch ($role) {
            case "moderator":
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user AND article.id_article = :idArticle");
                $reqArticle->execute(array(
                    'idArticle' => $idArticle
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
                $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user AND article.id_article = :idArticle");
                $reqArticle -> execute(array(
                    'idArticle' => $idArticle
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
            $reqArticle = $this->linkpdo->prepare("SELECT article.id_article, article.date_publication, article.contenu, users.login FROM article, users WHERE article.id_user = users.id_user AND article.id_article = :idArticle");
            $reqArticle -> execute(array(
                'idArticle' => $idArticle
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
        $req = $this->linkpdo->prepare("SELECT Login FROM `users` WHERE Id_User = :idUser");
        $req->execute(array(
            'idUser' => $idUser
        ));

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdUserByLogin($login){
        $req = $this->linkpdo->prepare("SELECT Id_User FROM users WHERE Login = :login");
        $req->execute(array(
            'login' => $login
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserLike($id_Article){
        $req = $this -> linkpdo -> prepare("SELECT users.login FROM interagir, users WHERE interagir.id_user = users.id_user AND interagir.id_article = :idArticle AND interagir.a_like = 1");
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
        $req = $this->linkpdo->prepare("SELECT COUNT(*) FROM interagir WHERE interagir.iD_Article = :idArticle AND a_like = 1");
        $req->execute(array(
            'idArticle' => $id_Article
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
    }

    public function getNbDislike($id_Article){
        $req = $this->linkpdo->prepare("SELECT COUNT(*) FROM interagir WHERE interagir.iD_Article = :idArticle AND a_like = -1");
        $req->execute(array(
            'idArticle' => $id_Article
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
    }

    public function isLike($id_Article, $id_User){
        $req = $this->linkpdo->prepare("SELECT count(*) FROM interagir WHERE iD_Article = :idArticle AND id_user = :idUser AND a_like = 1");
        $req->execute(array(
            'idArticle' => $id_Article,
            'idUser' => $id_User
        ));
        $req = $req->fetchAll(PDO::FETCH_ASSOC)[0]['count(*)'];
        if ($req > 0){
            return true;
        }
        return false;
    }

    public function isDislike($id_Article, $id_User){
        $req = $this->linkpdo->prepare("SELECT count(*) FROM interagir WHERE iD_Article = :idArticle AND id_user = :idUser AND a_like = -1");
        $req->execute(array(
            'idArticle' => $id_Article,
            'idUser' => $id_User
        ));
        $req = $req->fetchAll(PDO::FETCH_ASSOC)[0]['count(*)'];
        if ($req > 0){
            return true;
        } else {
            return false;
        }
    }

    public function articleExist($idArticle){
        $req = $this->linkpdo->prepare("SELECT count(*) FROM article WHERE iD_Article = :idArticle");
        $req->execute(array(
            'idArticle' => $idArticle
        ));
        $req = $req->fetchAll(PDO::FETCH_ASSOC)[0]['count(*)'];
        return $req > 0;
    }

    /*
    FONCTIONS D'AJOUT DANS LA BDD
    */

    public function insertArticle($auteur, $contenu) {
        $idAuteur = $this->getIdUserByLogin($auteur);
        $req = $this->linkpdo->prepare('INSERT INTO article VALUES (NULL, :contenu, now(), :idAuteur)');
        $testreq = $req->execute(array(
            'contenu' => $contenu,
            'idAuteur' => $idAuteur[0]['Id_User']
        ));
        if (!$testreq) {
            die("Erreur insertArticle");
        }
    }

    /*
    FONCTIONS DE MODIFICATION DANS LA BDD
    */

    public function updateArticle($idArticle, $contenu){
        $req = $this->linkpdo->prepare('UPDATE article SET contenu = :contenu WHERE Id_Article = :idArticle');
        $testreq = $req->execute(array(
            'contenu' => $contenu,
            'idArticle' => $idArticle
        ));
        if (!$testreq) {
            die("Erreur updateArticle");
        }
    }

    public function likeArticle($idArticle, $idUser){
        // Vérifie si l'article existe
        $req = $this -> linkpdo -> prepare ("SELECT count(*) FROM article WHERE id_article = :idArticle");
        $req -> execute(array(
            'idArticle' => $idArticle
        ));
        if ($req -> fetchAll(PDO::FETCH_ASSOC)[0]['count(*)'] == 0){
            return 0;
        }

        $req = $this -> linkpdo -> prepare('SELECT a_like FROM interagir WHERE id_article = :idArticle AND id_user = :idUser');
        $req -> execute(array(
            'idArticle' => $idArticle,
            'idUser' => $idUser
        ));
        $req = $req -> fetchAll(PDO::FETCH_ASSOC);
        if (count($req) > 0) {
            $req = $this->linkpdo->prepare('UPDATE interagir SET a_like = 1 WHERE id_article = :idArticle AND id_user = :idUser');
            $testreq = $req->execute(array(
                'idArticle' => $idArticle,
                'idUser' => $idUser
            ));
            if (!$testreq) {
                die("Erreur likeArticle");
            }
        } else {
            $req = $this->linkpdo->prepare('INSERT INTO interagir VALUES (:idUser, :idArticle,  1)');
            $testreq = $req->execute(array(
                'idArticle' => $idArticle,
                'idUser' => $idUser
            ));
            if (!$testreq) {
                die("Erreur likeArticle");
            }
        }
        return 1;
    }

    public function dislikeArticle($idArticle, $idUser) {
        // Vérifie si l'article existe
        $req = $this -> linkpdo -> prepare ("SELECT count(*) FROM article WHERE id_article = :idArticle");
        $req -> execute(array(
            'idArticle' => $idArticle
        ));
        if ($req -> fetchAll(PDO::FETCH_ASSOC)[0]['count(*)'] == 0){
            return 0;
        }

        $req = $this -> linkpdo -> prepare('SELECT a_like FROM interagir WHERE id_article = :idArticle AND id_user = :idUser');
        $req -> execute(array(
            'idArticle' => $idArticle,
            'idUser' => $idUser
        ));
        $req = $req -> fetchAll(PDO::FETCH_ASSOC);
        if (count($req) > 0) {
            $req = $this->linkpdo->prepare('UPDATE interagir SET a_like = -1 WHERE id_article = :idArticle AND id_user = :idUser');
            $testreq = $req->execute(array(
                'idArticle' => $idArticle,
                'idUser' => $idUser
            ));
            if (!$testreq) {
                die("Erreur likeArticle");
            }
        } else {
            $req = $this->linkpdo->prepare('INSERT INTO interagir VALUES (:idUser, :idArticle, -1)');
            $testreq = $req->execute(array(
                'idArticle' => $idArticle,
                'idUser' => $idUser
            ));
            if (!$testreq) {
                die("Erreur dislikeArticle");
            }
        }
        return 1;
    }

    public function removeLikeArticle($idArticle, $idUser){
        $req = $this->linkpdo->prepare('DELETE FROM interagir WHERE id_article = :idArticle AND id_user = :idUser AND a_like = 1');
        $testreq = $req->execute(array(
            'idArticle' => $idArticle,
            'idUser' => $idUser
        ));
        if (!$testreq) {
            die("Erreur enleverLike");
        }
    }

    public function removeDislikeArticle($idArticle, $idUser){
        $req = $this->linkpdo->prepare('DELETE FROM interagir WHERE id_article = :idArticle AND id_user = :idUser AND a_like = -1');
        $testreq = $req->execute(array(
            'idArticle' => $idArticle,
            'idUser' => $idUser
        ));
        if (!$testreq) {
            die("Erreur enleverDislike");
        }
    }
    /*
    FONCTION SUPPRIMER DE LA BDD
    */

    //fonction pour supprimer un article
    public function deleteArticle($idArticle){
        $this->deleteInteragir($idArticle);
        
        //Test si l'article existe
        $req = $this -> linkpdo -> prepare ('SELECT count(*) FROM article WHERE Id_Article = :idArticle');
        $req -> execute(array(
            'idArticle' => $idArticle
        ));
        if ($req -> fetchAll(PDO::FETCH_ASSOC)[0]['count(*)'] == 0){
            return 0;
        }

        $req = $this->linkpdo->prepare('DELETE FROM article WHERE Id_Article = :idArticle');
        $testreq = $req->execute(array(
            'idArticle' => $idArticle
        ));
        if ($testreq == false) {
            die("Erreur deleteArticle");
        }
        return 1;
        // $this->deleteInteragir($idArticle);
    }

    //on regarde si un article a des likes ou dislike dans la table interagir et si c'est le cas, on vide toute les lignes de la table interagir avec l'id de l'article
    public function deleteInteragir($idArticle){
        $req = $this->linkpdo->prepare("SELECT count(*) as nb FROM interagir WHERE id_article = :idArticle");
        $req->execute(array(
            'idArticle' => $idArticle
        ));
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if ($result[0] > 0) {
            $req = $this->linkpdo->prepare("DELETE FROM interagir WHERE id_article = :idArticle");
            $req->execute(array(
                'idArticle' => $idArticle
            ));
        }
    }

    public function deleteArticlesUser($username){
        $idUser = $this->getIdUserByLogin($username);
        //requete pour récuperer tous les articles d'un utilisateur
        $req = $this->linkpdo->prepare("SELECT id_article FROM article WHERE id_user = :idUser");
        $req->execute(array(
            'idUser' => $idUser[0]['Id_User']
        ));
        //on crée une boucle et tant qu'il reste un article, on supprime les likes et dislikes de l'article
        while ($result = $req->fetchAll(PDO::FETCH_ASSOC)) {
            $this->deleteInteragir($result[0]['id_article']);
        }

        $req = $this->linkpdo->prepare('DELETE FROM article WHERE Id_User = :idUser');
        $testreq = $req->execute(array(
            'idUser' => $idUser[0]['Id_User']
        ));
        if ($testreq == false) {
            die("Erreur deleteArticle");
        }

    }

}
?>
