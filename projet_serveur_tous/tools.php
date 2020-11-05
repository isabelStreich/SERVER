<?php

// bloquer l'appel direct à ce fichier
// index.php doit être le point d'entrée
if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Direct access to this php file is forbidden');
}

/**
 * TableauAffiche permet d'afficher un tableau en HTML.
 */
function TableauAffiche($Tableau)
{
    echo '<table>';
    echo '<tr><th style="border:1px solid black">indice/clef</th><th style="border:1px solid black">valeur</th></tr>';
    foreach ($Tableau as $cle => $valeur) {
        echo '<tr><td style="border:1px solid black">'.$cle.'</td><td style="border:1px solid black">'.$valeur.'</td></tr>';
    }
    echo '</table>';
}

function TableauSelectHTML($nom_du_select, $Tableau, $Selected = '')
{
    $html = '<select class="form-control" name="'.$nom_du_select.'">';

    foreach ($Tableau as $cle => $valeur) {
        if ($cle == $Selected) {
            $html .= '<option value="'.$cle.'" selected>'.$valeur.'</option>';
        } else {
            $html .= '<option value="'.$cle.'">'.$valeur.'</option>';
        }
    }
    $html .= '</select>';

    return $html;
}

/**
 * Crash() affiche une erreur et enregistre l'erreur
 * dans un fichier .log.
 */
function Crash($code_erreur, $message)
{
    $currentDirectory = getcwd();
    // echo $currentDirectory.'<br>';

    // ecrire dans le fichier log
    $temps = date(DATE_RFC2822);
    // var_dump($temps);

    // variable superglobale $_SERVER
    //TableauAffiche($_SERVER);

    $myfile = fopen($currentDirectory."\log\serveur.log", 'a+');
    fwrite($myfile, $_SERVER['REMOTE_ADDR'].'-'.$temps.'-'.$code_erreur.'-'.$message.PHP_EOL);
    fclose($myfile);

    // send email
    //mail('velo@stephanelapointe.com', 'Erreur sur le serveur '.COMPANY_NAME, $message);

    //envoit reponse HTTP
    http_response_code($code_erreur);
    die($message);
}

function LogAffiche()
{
    $currentDirectory = getcwd();
    $file = file_get_contents($currentDirectory."\log\serveur.log");
    if ($file === false) {
        return 'fichier vide';
    } else {
        return $file;
    }
}
