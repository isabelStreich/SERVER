<?php

// bloquer l'appel direct à ce fichier
// index.php doit être le point d'entrée
if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Direct access to this php file is forbidden');
}

// fichiers requis (dépendances)
require_once 'global_defines.php';

/**
 * WebPage class affiche une page web standardisé (template).
 */
class WebPage
{
    // head metadatas
    public $lang = 'fr-CA';
    public $title = DEFAULT_PAGE_TITLE; // must be set for each page
    public $description = DEFAULT_PAGE_DESCRIPTION; // must be set for each page
    public $author = DEFAULT_PAGE_AUTHOR;
    public $icon = WEB_SITE_ICON_FILE;

    //page body content
    public $content; // page content itself

    // FUNCTIONS -------------------------------------------------------------
    public function __construct()
    {
    }

    public function Display()
    {
        if (!isset($this->content)) {
            Crash(500, 'cette page est vide, contenu non-spécifié - class WebPage - function Display');
        } ?>
<!DOCTYPE html>
<html lang="<?=$this->lang; ?>">

<head>
    <!-- <base href="http://localhost/projet_serveur_tous/"> -->
    <meta charset="UTF-8">
    <title><?=$this->title; ?>
    </title>
    <meta name="DESCRIPTION" content="<?=$this->description; ?>">
    <meta name="author" content="<?=$this->author; ?>">
    <!-- web site icon -->
    <LINK rel="icon" href="<?=$this->icon; ?>">

    <!--IMPORTANT pour responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- font awesome 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/d96d500ebb.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
            position: sticky;
            top: 0;
            width: 100%;
        }

        nav ul li {
            float: left;
        }

        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav ul li a:hover:not(.active) {
            background-color: #111;
        }

        nav .active {
            background-color: #4CAF50;
        }
    </style>
</head>

<body>

    <!-- PAGE HEADER -->
    <header>
        <img src="web_site_icon.jpg" alt="web site icon"><?=COMPANY_NAME; ?>
    </header>

    <!-- BARRE DE NAVIGATION -->
    <nav>
        <ul>
            <li><a href='index.php'>Accueil</a></li>
            <li><a href='index.php?op=100'>Offices</a></li>
            <li><a href='index.php?op=200'>Catégorie des produits</a></li>
            <li><a href='index.php?op=300'>Employés</a></li>
            <li><a href='index.php?op=400'>Commandes</a></li>
            <li><a href='index.php?op=500'>Produits</a></li>
            <li><a href='index.php?op=600'>Clients</a></li>
            <li><a href='index.php?op=700'>Paiments</a></li>
            <li><a href='index.php?op=98'>Log du serveur</a></li>
            <li><a href='index.php?op=99'>$_SERVER</a></li>
            <li><a href='index.php?op=10'>Usagers</a></li>
            <li><a href='index.php?op=3'>Inscription</a></li>
        </ul>
        <div style="clear:left"></div>
    </nav>
    <?php
        if (isset($_SESSION['email'])) {
            echo $_SESSION['email'];
            echo ' <a href="index.php?op=5">Déconnection</a>';
        } else {
            echo "<a href='index.php?op=1'>Connection</a>";
        } ?>

    </nav>
    <h1>
        <?=$this->title; ?>
    </h1>

    <!-- CONTENT -->
    <?=$this->content; ?>


    <!-- FOOTER -->
    <p style="text-align:center"><i class="fa fa-arrow-circle-left" style="font-size:40px;"
            onclick="window.history.back()"></i></p>
    <footer>
        Exercice par <?=$this->author; ?> &copy;<br>
        <?=COMPANY_STREET_ADDRESS; ?> <?=COMPANY_CITY; ?> <?=COMPANY_PROVINCE; ?> <?=COMPANY_COUNTRY; ?> <?=COMPANY_POSTAL_CODE; ?><br>
        <?=COMPANY_TEL; ?> <?=COMPANY_EMAIL; ?>
    </footer>
    </div>

    <!-- Bootstrap Javascript -->
    <script src=" https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>
<?php
    // fin de la fonction display
    die();
    }
} // fin de la classe
