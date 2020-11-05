<?php

if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('acces direct a ce fichier est interdit');
}

$index_loaded = true;
// require_once 'db_mysqli.php';
require_once 'db_pdo.php';
require_once 'global_defines.php';
require_once 'tools.php';
require_once 'webpage.php';
require_once 'productlines.php';
// require_once 'css/global.css';

class productlines
{
    public function __construct()
    {
    }

    public function List($produitFiltre = '')
    {
        $DB = new DB();
        if ($produitFiltre == '') {
            $productlines = $DB->table('productlines');
        } else {
            $sql_str = 'SELECT * FROM productlines WHERE productLine=:produitFiltre';
            $params = ['produitFiltre' => $produitFiltre];
            $productlines = $DB->querySelectParam($sql_str, $params);
        }
        //$productlines = $DB->table('productlines');

        $Page = new webPage();

        //A FAIRE AFFICHER LA TABLE HTML

        $Page->content .= '<h1 style="text-align: center"> PRODUCTSLINE </h1>';

        //SEARCH
        $Page->content .= '<form action="index.php?op=200" method="POST" class="float-right">';
        $Page->content .= '<div class="form-group mx-sm-3 mb-2">';
        $Page->content .= '<input type="text" name="produitFiltre" class="form-control mr-sm-2" placeholder="Rechercher par nom">';
        // $Page->content .= '<input type="submit" value="Rechercher" class="btn btn-outline-success my-2 my-sm-0">';
        $Page->content .= '</div>';
        $Page->content .= '</form>';
        //BUTTON ADD
        $Page->content .= '<div id="btn_ajouter" class="form-group mx-sm-3 mb-2">';
        $Page->content .= '<a href="index.php?op=205" class="btn btn-success">AJOUTER</a>';
        $Page->content .= '</div>';
        //TABLE PRODUCTLINES
        $Page->content .= '<div align="justify">';
        $Page->content .= '<table class="table" >';
        $Page->content .= '<thead class="thead-light">';
        $Page->content .= '<tr>';
        $Page->content .= '<tr>';
        $Page->content .= '<th scope="col"> ProductLine </th>';
        $Page->content .= '<th scope="col"> Text Description </th>';
        $Page->content .= '<th scope="col">htmlDescription </th>';
        $Page->content .= '<th scope="col">image </th>';
        $Page->content .= '<th scope="col">Operation </th>';
        $Page->content .= '</tr>';
        foreach ($productlines as $un_productlines) {
            // code...
            $Page->content .= '<tr scope="row">';
            $Page->content .= '<td scope="row">'.$un_productlines['productLine'].'</td>';
            $Page->content .= '<td scope="row">'.$un_productlines['textDescription'].'</td>';
            $Page->content .= '<td scope="row">'.$un_productlines['htmlDescription'].'</td>';
            $Page->content .= '<td scope="row"><img src="data:image;base64,'.$un_productlines['image'].'" alt="une image" /></td>';
            $Page->content .= '<td scope="row">
            <div class="btn-group" role="group" aria-label="Basic example">
            <a href="index.php?op=203&id='.$un_productlines['productLine'].'"class="btn btn-outline-info"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
            <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
          </svg></a>
            <a href="index.php?op=201&id='.$un_productlines['productLine'].'"class="btn btn-outline-warning"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
          </svg></a>
            <a href="index.php?op=202&id='.$un_productlines['productLine'].'"class="btn btn-outline-danger"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
          </svg></a>
            </div>
            </td>';
            $Page->content .= '</tr>';
        }
        $Page->content .= '</table>';
        $Page->content .= '<div>';

        // $Page->content .= '<fieldset>';
        // echo '<button type="button" class="btn btn-success">Ajouter</button>';

        $Page->display();
        die();
    }

    /**
     * service API que retourne  la liste des productline en format JSON.
     */
    public function ListJson()
    {
        $DB = new DB();
        $productlines = $DB->table('productlines');
        $productlinesJson = json_encode($productlines, JSON_PRETTY_PRINT);
        $content_type = 'content-Type:application/json; charset=UTF-8';
        header($content_type);
        http_response_code(200);
        echo $productlinesJson;
    }

    /**
     * AJOUTER.
     */
    public function Add($errMSG = '')
    {
        $Page = new webPage();

        $Page->content .= '<fieldset class="form-group">';
        $Page->content .= '<div  class="container">';
        $Page->content .= '<form action="index.php?op=204" method="POST" enctype="multipart/form-data"> ';
        // $Page->content .= "<input type='hidden' name='productLine' value='".$$productlines_info['productLine']."'>";
        $Page->content .= '<div>';
        $Page->content .= '<div><h1>Ajouter une nouvelle catégorie: </h1> </div>';
        $Page->content .= '<div> <h4>'.$errMSG.'</h4></div>';
        $Page->content .= '<div class="jumbotron">';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Product Line name :</label>';
        $Page->content .= '<input type="text" class="form-control form-control-lg" name="productLine" id="productLine" maxlength="50">';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Description :</label>';
        $Page->content .= '<textarea name="textDescription" class="form-control form-control-lg" id="textDescription" maxlength="4000"> </textarea>';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> HtmlDescription :</label>';
        $Page->content .= '<input type="text" name="htmlDescription" class="form-control form-control-lg" id="htmlDescription">';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Image :</label>';
        $Page->content .= '<input type="file" name="image"  class="form-control form-control-lg" id="image" accept="image/*">';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Submit</button>';
        $Page->content .= '</div>';
        $Page->content .= '<div>';
        $Page->content .= '<a href="index.php?op=200" class="btn btn-secondary btn-lg btn-block">Retour</a>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</form>';
        $Page->content .= '</fieldset >';

        $Page->display();
        die();
    }

    /**
     * DISPLAY.
     */
    public function Display($productLine)
    {
        // $sql = "SELECT * FROM productlines WHERE id = ?";
        $sql = 'SELECT * FROM productlines WHERE productLine = "'.$productLine.'"';
        $DB = new DB();
        $productlines = $DB->querySelect($sql);
        $productline = $productlines[0];

        $Page = new webPage();

        $Page->content .= '<fieldset class="form-group">';
        $Page->content .= '<form action="index.php?op=203" method="POST" enctype="multipart/form-data">';

        // $Page->content .= "<input type='hidden' name='op' value='".$productline['productLine']."'>";
        $Page->content .= '<div  class="container">';
        $Page->content .= '<div><h3>Display catégorie: </h3> </div>';
        $Page->content .= '<div class="jumbotron">';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Product Line name :</label>';
        $Page->content .= '<input type="text" maxlength="50" class="form-control form-control-lg" value="'.$productline['productLine'].'" disabled>';
        $Page->content .= "<input type='hidden' name='productLine' id='productLine' value='".$productline['productLine']."'>";
        // $Page->content .= '<input type="text" name="productLine" maxlength="50" value="'.!empty($productLine) ? $productLine : $records['productLine'].'>';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row"> ';
        $Page->content .= '<label class="lead"> Description :</label>';
        $Page->content .= '<textarea name="textDescription" class="form-control form-control-lg" id="textDescription" maxlength="4000"  rows="10" cols="100"  disabled>'.$productline['textDescription'].' </textarea>';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> HtmlDescription :</label>';
        $Page->content .= '<input type="text" name="htmlDescription" id="htmlDescription" class="form-control form-control-lg" value="'.$productline['htmlDescription'].'"  disabled>';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Image :</label>';
        $Page->content .= '<input type="file" name="image" id="image" class="form-control form-control-lg" value="'.$productline['image'].'"  disabled>';
        $Page->content .= '</div>';
        $Page->content .= '<div>';
        $Page->content .= '<a  href="index.php?op=200" class="btn btn-secondary btn-lg btn-block">Retour</a>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</form>';
        $Page->content .= '</fieldset >';

        $Page->display();
        die();
    }

    /**
     * EDIT.
     */
    public function Edit($productLine, $err_message = '')
    {
        $Page = new webPage();

        if (!isset($_FILES['image']['name'])) {
            $_FILES['image']['name'] = '';
        }

        $Page->content .= '<fieldset class="form-group">';
        $Page->content .= '<form action="index.php?op=206" method="POST" enctype="multipart/form-data"> ';
        $Page->content .= '<div class="container">';
        $Page->content .= '<div>';
        $Page->content .= '<div><h1>Editer une catégorie: </h1> </div>';
        $Page->content .= '<div> <h4>'.$err_message.'</h4></div>';
        $Page->content .= '<div class="jumbotron">';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Product Line name :</label>';
        $Page->content .= '<input type="text" class="form-control form-control-lg" maxlength="50" value="'.$productLine['productLine'].'" disabled>';
        $Page->content .= "<input type='hidden' name='productLine' id='productLine' value='".$productLine['productLine']."'>";
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Description :</label>';
        $Page->content .= '<textarea name="textDescription" class="form-control form-control-lg" id="textDescription" maxlength="4000"  rows="6" cols="100">'.$productLine['textDescription'].' </textarea>';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> HtmlDescription :</label>';
        $Page->content .= '<input type="text" class="form-control form-control-lg" name="htmlDescription" id="htmlDescription" value="'.$productLine['htmlDescription'].'">';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<label class="lead"> Image :</label>';
        $Page->content .= '<input type="file" class="form-control form-control-lg" name="image" id="image" value="'.$_FILES['image']['name'].'">';
        $Page->content .= '</div>';
        $Page->content .= '<div class="form-group row">';
        $Page->content .= '<button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Submit</button>';
        $Page->content .= '</div>';
        $Page->content .= '<div>';
        $Page->content .= '<a href="index.php?op=200" class="btn btn-secondary btn-lg btn-block">Retour</a>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->content .= '</form>';
        $Page->content .= '</fieldset >';

        $Page->display();
        die();
    }

    /**
     * DELETE.
     */
    public function Delete($id_delete)
    {
        $DB = new DB();
        $id = $_GET['id'];
        $sql_str = 'DELETE FROM productlines WHERE productLine="'.$id.'"';
        $DB->querySelect($sql_str);

        $Page = new webPage();

        $Page->content .= '<div class="alert alert-light" align="center">';
        $Page->content .= '<div class="alert alert-danger" role="alert">';
        $Page->content .= '<h4 class="alert-heading">Successfully deleted!</h4>';
        $Page->content .= '<a href="index.php?op=200" class="btn btn-primary btn-lg active">Retour</a>';
        $Page->content .= '</div>';
        $Page->content .= '</div>';
        $Page->display();

        die();
    }

    /**
     * SAVE.
     */
    public function Save($id)
    {
        $err_message = '';
        // ici faire req de ajouter/
        $DB = new DB();

        //if (!empty($_POST) && isset($_POST['productLine']) && isset($_POST['textDescription']) && isset($_POST['htmlDescription'])) {
        // On assigne nos valeurs
        $productLine = $_POST['productLine'];
        $textDescription = $_POST['textDescription'];
        $htmlDescription = $_POST['htmlDescription'];

        $image = $_FILES['image']['name'];
        $tmp_dir = $_FILES['image']['tmp_name'];
        $imgSize = $_FILES['image']['size'];

        //VÉRIFIER QUE LE NOM N'EST PAS RÉPÉTÉ
        $productLineExistant = $DB->querySelect('SELECT productLine from productlines');

        foreach ($productLineExistant as $un_product) {
            if ($un_product['productLine'] == $_POST['productLine']) {
                $err_message .= 'Le nom de la ligne de produits existe deja. ';
            }
        }

        //  VERIFER LES DONNES
        // productLine

        if (!isset($_POST['productLine']) or $_POST['productLine'] == '') {
            $err_message .= 'Le productLine est requis !';
        } elseif (strlen($_POST['productLine']) > 50) {
            $err_message .= 'Le nom est trop long, max 50 caractères !';
        }
        if (!isset($_POST['textDescription']) or $_POST['textDescription'] == '') {
            $err_message .= 'Le  description est requis !';
        } elseif (strlen($_POST['textDescription']) > 4000) {
            $err_message .= 'Le textDescription est trop long, max 4000 caractères !';
        }

        // TOUT LES DONNEES SONT OK

        if (!$err_message == '') {
            $this->Add($err_message);
        } else {
            $sql_str = "INSERT INTO productlines (productLine, textDescription, htmlDescription,image)
            VALUES('$productLine', '$textDescription', '$htmlDescription','$image')";
            $DB->query($sql_str);
            $this->List();
        }
    }

    public function SaveEdit($id)
    {
        $err_message = '';
        $product = $_POST;
        // ici faire req de ajouter/

        $DB = new DB();

        //if (!empty($_POST) && isset($_POST['productLine']) && isset($_POST['textDescription']) && isset($_POST['htmlDescription'])) {
        // On assigne nos valeurs
        $productLine = $_POST['productLine'];
        $textDescription = $_POST['textDescription'];
        $htmlDescription = $_POST['htmlDescription'];

        $image = $_FILES['image']['name'];
        $tmp_dir = $_FILES['image']['tmp_name'];
        $imgSize = $_FILES['image']['size'];

        //VERIFICATION

        if (!isset($_POST['textDescription']) or $_POST['textDescription'] == '') {
            $err_message .= 'Le  description est requis !';
        } elseif (strlen($_POST['textDescription']) > 4000) {
            $err_message .= 'Le textDescription est trop long, max 4000 caractères !';
        }

        // TOUT LES DONNEES SONT OK
        if (!$err_message == '') {
            $this->Edit($product, $err_message);
        } else {
            $sql_str = "UPDATE productlines SET textDescription='".$textDescription."',htmlDescription='".$htmlDescription."',image='".$image."' where productLine='".$productLine."'";
            $DB->query($sql_str);
            $this->List();
        }
    }
}
