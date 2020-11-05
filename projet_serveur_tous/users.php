<?php

// bloquer l'appel direct à ce fichier
// index.php doit être le point d'entrée
if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Direct access to this php file is forbidden');
}

// fichiers requis (dépendances)
require_once 'db_pdo.php';

/**
 * WebPage class affiche une page web standardisé (template).
 */
class Users
{
    // FUNCTIONS -------------------------------------------------------------
    public function __construct()
    {
    }

    public function List()
    {
        $DB = new DB();
        $Users = $DB->table('users');

        $Page = new WebPage();
        $Page->title = 'Liste des usagers';
        $Page->description = 'Liste de tous les usagers';
        $Page->content = '';
        $Page->content .= '<table class="table">';
        $Page->content .= '<thead class="thead-dark">';
        $Page->content .= '<tr>';
        $Page->content .= '<th scope="col">id</th><th scope="col">fullname</th><th scope="col">email</th><th scope="col">langue</th><th scope="col">pays</th>';
        $Page->content .= '</tr>';
        $Page->content .= '</thead>';
        $Page->content .= '<tbody>';
        foreach ($Users as $User) {
            $Page->content .= '<tr>';
            $Page->content .= '<td>'.$User['id'].'</td><td>'.$User['fullname'].'</td><td>'.$User['email'].'</td><td>'.$User['langue'].'</td><td>'.$User['pays'].'</td>';
            $Page->content .= '</tr>';
        }
        $Page->content .= '</tbody>';
        $Page->content .= '</table>';
        $Page->Display();
    }

    public function LoginFormAffiche($User_Info, $message = '')
    {
        if (isset($_SESSION['email'])) {
            $Page = new WebPage();
            $Page->title = 'Déjà connecté';
            $Page->description = 'page de connection';
            $Page->content = "Vous êtes déjà connecté ! <a href='index.php?op=5'>Déconnectez-vous</a>";
            $Page->Display();
        } else {
            $Page = new WebPage();
            $Page->title = 'Connectez-vous ';
            $Page->description = 'page de connection';

            // mettre un long contenu html dans une variable
            $Page->content = '';
            if (isset($_COOKIE['email'])) {
                // usager a déjà visité le site web
                // il y a moins de 2 ans
                $Page->content .= 'Re-bienvenue '.$_COOKIE['email'];
                $Page->content .= '<br>Votre derniere connection '.date('d-M-Y', $_COOKIE['derniere_connection']);
            }
            $Page->content .= '<div class="alert alert-danger" role="alert">'.$message.'</div>';
            $Page->content .= '<form action="index.php?op=2" method="POST">';
            $Page->content .= 'Email <input type="email" name="email" maxlength="126" value="'.$User_Info['email'].'"><br>';
            $Page->content .= 'Mot de passe <input type="password" name="pw" maxlength="8" value="'.$User_Info['pw'].'"><br>';
            $Page->content .= '<input type="submit" value="Continuer">';
            $Page->content .= '</form>';
            $Page->content .= '<br>';

            $Page->Display();
        }
    }

    public function LoginFormVerifier()
    {
        // $Users = [
        //     ['id' => 0, 'email' => 'Yannick@gmail.com', 'pw' => '12345678'],
        //     ['id' => 1, 'email' => 'Victor@test.com', 'pw' => '11111111'],
        //     ['id' => 2, 'email' => 'Christian@victoire.ca', 'pw' => '22222222'],
        // ];

        $DB = new DB();
        $Users = $DB->table('users');

        // sauvergarder les valeurs dans le formulaire au cas ou il y
        // a erreur et on doit ré-afficher le formulaire
        $User_Info = $_POST;

        // récupérer les données du formulaire -------------
        //var_dump($_POST);
        if (isset($_POST['email']) and $_POST['email'] !== '') {
            $email_form = $_POST['email'];
        } else {
            // afficher message email manquant
            // reaficher le formulaire
            $this->LoginFormAffiche($User_Info, 'Un email est requis !');
        }

        if (isset($_POST['pw']) and $_POST['pw'] !== '') {
            $pw_form = $_POST['pw'];
        } else {
            // afficher message pw manquant
            // reaficher le formulaire
            $this->LoginFormAffiche($User_Info, 'Mot de passe est requis !');
        }

        // vérifier si usager connu ---------------------
        $user_bon = false;
        foreach ($Users as $User) {
            if ($User['email'] == $email_form and $User['pw'] == $pw_form) {
                $user_bon = true;
                break;
            }
        }

        if ($user_bon) {
            // OK CONNECTÉ
            $_SESSION['email'] = $_POST['email'];
            // cookie valide pour 2 ans
            setcookie('email', $_POST['email'], time() + 2 * (365 * 24 * 60 * 60));
            setcookie('derniere_connection', time(), time() + 2 * (365 * 24 * 60 * 60));

            // si bon afficher une page ok vous êtes connectez
            $Page = new WebPage();
            $Page->title = 'Vous êtes connecté';
            $Page->description = 'page de connection';

            $Page->content = <<<HTML
    <div class="alert alert-success" role="alert">Vous êtes connecté !</div>
HTML;

            $Page->Display();
        } else {
            // si usager inconnu, ré-afficher le formulaire
            $this->LoginFormAffiche($User_Info, 'Email ou mot de passe sont invalides, réessayez');
        }
    }

    public function InscriptionFormAffiche($User_Info, $message = '')
    {
        $Page = new WebPage();
        $Page->title = 'Inscrivez-vous comme usager';
        $Page->description = 'Inscrivez-vous comme usager, créez votre profile';

        // $Provinces = ['QC' => 'Québec', 'ON' => 'Ontario', 'NB' => 'Nouveau-Brunswick', 'NS' => 'Nouvelle-Écosse', 'AB' => 'Alberta', 'MN' => 'Manitoba', 'SK' => 'Saskatchewan'];
        // $ProvincesSelect = TableauSelectHTML('province', $Provinces, $User_Info['province']);
        $DB = new DB();
        $Provinces = $DB->table('provinces');
        $ProvincesSelect = '<select name="province">';
        foreach ($Provinces as $Province) {
            $ProvincesSelect .= '<option value="'.$Province['code'].'">'.$Province['nom'].'</option>';
        }
        $ProvincesSelect .= '</select>';

        // $Pays = ['CA' => 'Canada', 'US' => 'USA', 'MX' => 'Mexique', 'FR' => 'France', 'AU' => 'Autre'];
        // $PaysSelect = TableauSelectHTML('pays', $Pays, $User_Info['pays']);
        $Pays = $DB->table('pays');
        $PaysSelect = '<select name="pays">';
        foreach ($Pays as $Pay) {
            $PaysSelect .= '<option value="'.$Pay['code'].'">'.$Pay['nom'].'</option>';
        }
        $PaysSelect .= '</select>';

        $Page->content = <<<HTML
    <div class="alert alert-danger" role="alert">{$message}</div>
    <form action="index.php?op=4" method="POST" style="width:350px;margin:30px;">

    <fieldset>
            <legend>Information générale</legend>

            <input class="form-control" type="text" name="fullname" value="{$User_Info['fullname']}" placeholder="Prénom et nom" maxlength="50" required>

            No., Rue, #Apt (optionel)<br>
            <textarea class="form-control" name="adresse" placeholder="Entrez votre adresse" cols="30" rows="3" maxlength="255">{$User_Info['adresse']}</textarea>

            Ville (optionel)<br>
            <input class="form-control" type="text" name="ville" value="{$User_Info['ville']}" maxlength="50">
            <br>
            Province (optionel)<br>
            {$ProvincesSelect}
            <br>
            Pays (optionel)<br>
            {$PaysSelect}
            <br>
            Code postal (optionel)<br>
            <input class="form-control" type="text" name="code_postal" value="{$User_Info['code_postal']}" placeholder="ex. A1B-2C3" maxlength="7">
        </fieldset>
        <br>
        <!-- Vos intérêts (optionel, vous pouvez sélectionner plusieurs)<br>
        <select class="form-control" name="interets[]" multiple size="3">
            <option value="se">scooter électrique</option>
            <option value="sg">scooter à essence</option>
            <option value="velo_el">vélo électrique</option>
            <option value="velo">velo régulier</option>
            <option value="moto">moto</option>
        </select> -->

        <!--Langue ------------------------------------------->
        <fieldset>
            <legend>Langue</legend>
            <input type="radio" name="langue" value="fr" id="French" checked>
            <label for="French">Français</label>
            <br>
            <input type="radio" name="langue" value="an" id="English">
            <label for="English">Anglais</label>
            <br>
            <input type="radio" name="langue" value="autre" id="autre_lang_sel">
            <label for="autre_lang_sel">Autre</label>
            <input type="text" name="autre_langue" value="{$User_Info['autre_langue']}" maxlength="25">
            <br>
        </fieldset>

         <!-- info connexion ---------------------------------->
         <fieldset>
            <legend>Info connexion (requis)</legend>
            <input class="form-control" type="email" name="email" value="{$User_Info['email']}" placeholder="Email" maxlength="126" required><br>
            <input class="form-control" type="password" name="pw" value="{$User_Info['pw']}" placeholder="mot de passe - max 8 char." maxlength="8" required><br>
            <input class="form-control" type="password" name="pw2" value="{$User_Info['pw2']}" placeholder="repétez le mot de passe" maxlength="8" required><br>
        </fieldset>

        <!-- acceptations ------------------------------------->
        <input type="checkbox" name="spam_ok" value="1" checked>Je désire recevoir périodiquement de l'information sur
        les
        nouveaux produits<br>
        <br>

        <!-- bouton  ------------------------------------------>
        <input class="btn btn-primary" type="submit" value="Continuez"><br>
        <br>
    </form>
HTML;

        $Page->Display();
    }

    public function InscriptionFormVerifier()
    {
        $err_message = '';

        //tableau pour enregistrer les infos et repasser au formulaire en cas d'erreur
        $User_Info = $_POST;

        // $Users = [
        //     ['id' => 0, 'email' => 'Yannick@gmail.com', 'pw' => '12345678'],
        //     ['id' => 1, 'email' => 'Victor@test.com', 'pw' => '11111111'],
        //     ['id' => 2, 'email' => 'Christian@victoire.ca', 'pw' => '22222222'],
        // ];

        $DB = new DB();
        $Users = $DB->table('users');

        //email
        if (!isset($_POST['email']) or empty($_POST['email'])) {
            $err_message .= 'Un email est requis !';
        } else {
            // vérifier si usager existe déjà ---------------------
            $user_existe = false;
            foreach ($Users as $User) {
                if ($User['email'] == $_POST['email']) {
                    $user_existe = true;
                    break;
                }
            }

            if ($user_existe) {
                // si usager existe déjà, ré-afficher le formulaire
                $err_message .= 'Email existe déjà, choisir un autre email.';
            }
        }

        // fullname
        if (!isset($_POST['fullname']) or $_POST['fullname'] == '') {
            $err_message .= 'Le nom est requis !';
        } elseif (strlen($_POST['fullname']) > 50) {
            $err_message .= 'Le nom est trop long, max 50 caractères !';
        }

        // adresse
        if (isset($_POST['adresse']) && strlen($_POST['adresse']) > 255) {
            $err_message .= 'Addresse trop longue, max 255 caractères.';
        }

        // ville
        if (isset($_POST['ville']) && strlen($_POST['ville']) > 50) {
            $err_message .= 'Ville trop longue, max 50 caractères.';
        }

        // province
        if (isset($_POST['province'])) {
            $prov_valid = false;

            // $Provinces = ['QC' => 'Québec', 'ON' => 'Ontario', 'NB' => 'Nouveau-Brunswick', 'NS' => 'Nouvelle-Écosse', 'AB' => 'Alberta', 'MN' => 'Manitoba', 'SK' => 'Saskatchewan'];
            //verfier une valeur dans la liste
            // foreach ($Provinces as $cle => $nom) {
            //     if ($cle == $_POST['province']) {
            //         $prov_valid = true;
            //         break;
            //     }
            // }

            $Provinces = $DB->table('provinces');
            foreach ($Provinces as $Province) {
                if ($Province['code'] == $_POST['province']) {
                    $prov_valid = true;
                    break;
                }
            }

            if (!$prov_valid) {
                $err_message = $err_message.'province invalide';
            }
        }

        // pays
        if (isset($_POST['pays'])) {
            $pays_valid = false;
            // $Pays = ['CA' => 'Canada', 'US' => 'USA', 'MX' => 'Mexique', 'FR' => 'France', 'AU' => 'Autre'];
            $Pays = $DB->table('pays');
            foreach ($Pays as $Pay) {
                if ($Pay['code'] == $_POST['pays']) {
                    $pays_valid = true;
                    break;
                }
            }

            if (!$pays_valid) {
                $err_message = $err_message.'pays invalide';
            }
        }

        // code_postal
        if (isset($_POST['code_postal']) && strlen($_POST['code_postal']) > 7) {
            $err_message .= 'Code postal trop long, max 6 caractères.';
        }

        // interet
        // echo 'interet 0='.$_POST['interets'][0].'<br>';
        // echo 'interet 1='.$_POST['interets'][1].'<br>';

        // langue
        if (isset($_POST['langue']) && strlen($_POST['langue']) > 2) {
            $err_message .= 'langue trop longue, code de 2 caractères seulement.';
        }

        // autre langue
        if (isset($_POST['autre_langue']) && strlen($_POST['autre_langue']) > 25) {
            $err_message .= 'autre langue trop longue, max 25 caractères.';
        }

        //pw
        if (!isset($_POST['pw'])) {
            $err_message .= 'Mot de passe est requis !';
        } elseif (strlen($_POST['pw']) > 8) {
            $err_message .= 'mot de passe trop long, max 8 caractères.';
        }

        //pw2
        if (!isset($_POST['pw2'])) {
            $err_message .= 'Mot de passe 2 est requis !';
        }
        // verifer les 2 mots de passes sont identiques
        if ($_POST['pw'] !== $_POST['pw2']) {
            $err_message .= 'Les 2 mots de passe ne sont pas identiques';
        }

        // spam_ok
        if (!isset($_POST['spam_ok'])) {
            // cas special checkbox, recoit rien si pas coché
            // donc on doit forcer la valeur 0
            $User_Info['spam_ok'] = 0;
        } else {
            $User_Info['spam_ok'] = 1;
        }

        // A FAIRE verifer tous champs longueur max,
        // valeur est dans la liste (pour les selects) etc...

        //A FAIRE select avec mutliple
        // $interets = $_POST['interets'];
        // var_dump($interets);

        if ($err_message !== '') {
            $this->InscriptionFormAffiche($User_Info, $err_message);
        } else {
            // tout ok

            // l'usager est connecté
            $_SESSION['email'] = $_POST['email'];
            // cookie valide pour 2 ans
            setcookie('email', $_POST['email'], time() + 2 * (365 * 24 * 60 * 60));
            setcookie('derniere_connection', time(), time() + 2 * (365 * 24 * 60 * 60));

            $DB->query("INSERT INTO users
        (fullname,adresse,ville,province,pays,code_postal,langue,autre_langue,email,pw,spam_ok)
        VALUES ('".$_POST['fullname']."','".$_POST['adresse']."','".$_POST['ville']."','".$_POST['province']."','".$_POST['pays']."','".$_POST['code_postal']."','".$_POST['langue']."','".$_POST['autre_langue']."','".$_POST['email']."','".$_POST['pw']."','".$User_Info['spam_ok']."')"
        );

            $Page = new WebPage();
            $Page->title = 'Vous êtes inscrit !';
            $Page->description = 'Bienvenue vous êtes inscrit.<br>';
            $Page->content = 'Bienvenue '.$User_Info['fullname'].' vous êtes inscrit.<br>Vous pouvez maintenant suivre vos commandes et plus';
            $Page->Display();
        }
    }
}
