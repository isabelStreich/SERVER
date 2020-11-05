<?php

/**
 * projet serveur - ceci est le point d'entree.
 */
if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Acces direct a ce fichier est interdit');
}

// require_once 'db_mysqli.php';
require_once 'db_pdo.php';
require_once 'webpage.php';

class products
{
    public function __construct()
    {
    }

    public function DisplayTable()
    {
        $html = '<style>';
        $html .= 'table {margin:0 auto;text-align:center; width:90%} th, td {padding:5px;border:1px solid black;}';
        $html .= '</style>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<th>productCode</th>';
        $html .= '<th>productName</th>';
        $html .= '<th>productLine</th>';
        $html .= '<th>productScale</th>';
        $html .= '<th>productVendor</th>';
        $html .= '<th>productDescription</th>';
        $html .= '<th>quantityInStock</th>';
        $html .= '<th>buyPrice</th>';
        $html .= '<th>MSRP</th>';

        return $html;
    }

    public function DisplayTableInfo($all_products)
    {
        $html = '<td>'.$all_products['productCode'].'</td>';
        $html .= '<td>'.$all_products['productName'].'</td>';
        $html .= '<td>'.$all_products['productLine'].'</td>';
        $html .= '<td>'.$all_products['productScale'].'</td>';
        $html .= '<td>'.$all_products['productVendor'].'</td>';
        $html .= '<td>'.$all_products['productDescription'].'</td>';
        $html .= '<td>'.$all_products['quantityInStock'].'</td>';
        $html .= '<td>'.$all_products['buyPrice'].'</td>';
        $html .= '<td>'.$all_products['MSRP'].'</td>';

        return $html;
    }

    public function List()
    {
        $DB = new DB();
        $products = $DB->table('products');
        $DB->disconnect();

        $Page = new WebPage();
        $Page->title = 'List of products';
        $Page->description = 'Page - List of products';
        $Page->content = '<button style="position:relative;cursor:pointer;left:42vw" class="btn btn-primary"><a style="color:white;text-decoration:none" href="index.php?op=504">Add a product</a></button><br>';
        $Page->content .= $this->DisplayTable();
        $Page->content .= '<th>Action</th>';
        $Page->content .= '</tr>';
        foreach ($products as $all_products) {
            $Page->content .= '<tr>';
            $Page->content .= $this->DisplayTableInfo($all_products);
            $Page->content .= '<td><nav style="background-color:blue"><a class="fas fa-user-friends" href="index.php?op=503&id='.$all_products['productCode'].'">Display</a>
            <a class="fas fa-edit" href="index.php?op=501&id='.$all_products['productCode'].'">Edit</a>
            <a class="fas fa-backspace" href="index.php?op=502&ids='.$all_products['productCode'].'">Delete</a></nav></td>';
            $Page->content .= '</tr>';
        }
        $Page->content .= '<tr>';
        $Page->content .= '</table>';
        $Page->Display();
    }

    public function Display($filtre)
    {
        $DB = new DB();
        $ProductDetails = $DB->query('SELECT * from products WHERE productCode = "'.$filtre.'"');
        $DB->disconnect();

        $Page = new WebPage();
        $Page->title = 'Products details | id = '.$filtre;
        $Page->description = 'Page - Details of product';
        $Page->content = $this->DisplayTable();
        $Page->content .= '</tr>';
        foreach ($ProductDetails as $un_product) {
            $Page->content .= '<tr>';
            $Page->content .= $this->DisplayTableInfo($un_product);
            $Page->content .= '</tr>';
        }
        $Page->content .= '<tr>';
        $Page->content .= '</table>';
        $Page->content .= '<div style="text-align:center"><button class="btn btn-primary"><a class="fas fa-edit" style="color:white;text-decoration:none" href="index.php?op=501&id='.$filtre.'"> Editer</a></button>';
        $Page->content .= '<button style="margin-left:5px" class="btn btn-danger"><a class="fas fa-backspace" style="color:white;text-decoration:none" href="index.php?op=502&id='.$filtre.'"> Effacer l\'usager</a></button>';
        $Page->content .= '<button style="margin-left:5px" class="btn btn-primary"><a class="fas fa-backward" style="color:white;text-decoration:none" href="index.php?op=500"> Retour</a></button></div>';
        $Page->Display();
    }

    public function DeleteProduct($id)
    {
        $DB = new DB();
        // Effacer un produit de la base de donnees
        $sql = 'DELETE FROM products WHERE productCode = "'.$id.'"';
        $DB->query($sql);
        $DB->disconnect();
    }

    public function ProductFormAffiche($message = ' ', $id = '', $ops = '')
    {
        $ProductPage = new WebPage();
        $ProductPage->title = 'Modifier ou ajouter un produit';
        $ProductPage->description = 'Formulaire pour ajouter ou modifier un produit';
        $DB = new DB();
        $ProductLines = $DB->query('SELECT DISTINCT productLine from products');
        $DataProductLines = TableauSelectProductLineHTML('productLine', $ProductLines);
        $DB->disconnect();

        $ProductPage->content = <<<HTML
        <h4 style="text-align:center" class="alert alert-danger" role="alert">{$message}</h4>
        <form class="jumbotron" action="index.php?op={$ops}" method="POST" style="width:600px;margin:0 auto;text-align:center">
            <fieldset>
                productCode<br>
                <input type="text" class="form-control" name="productCode" maxlength="15" placeholder="Product code (ex. S10_1245 - max 15 characteres)" value="{$id}" required><br>
                productName<br>
                <input type="text" class="form-control" name="productName" maxlength="70" placeholder="Product name (ex. 1:12 - max 70 characteres)" required><br>
                productLine
                {$DataProductLines}<br>
                productScale
                <input type="text" class="form-control" name="productScale" maxlength="10" placeholder="Product scale (ex. 1:12 - max 10 characteres)" required><br>
                productVendor
                <input type="text" class="form-control" name="productVendor" maxlength="50" placeholder="Product vendor (max 50 characteres)" required><br>
                productDescription
                <input type="text" class="form-control" name="productDescription" placeholder="Product description" required><br>
                quantityInStock
                <input type="number" class="form-control" min=0 max=999999 name="quantityInStock" placeholder="Quantity in stock (ex. 500 - max 6 characteres)" required><br>
                buyPrice
                <input type="number" step="any" min=0 max="9999999.99" class="form-control" name="buyPrice" placeholder="Price (ex. 78.45 - max 10 characteres)" required><br>
                MSRP
                <input type="number" step="any" min=0 max="9999999.99" class="form-control" name="MSRP" placeholder="valeur MSRP (ex. 116.95 - max 10 characteres)" required><br>
                <input type="submit" class="btn btn-primary" value="Sauvegarder">
                <input type="reset" class="btn btn-primary" value="Effacer le produit">
                <button class="btn btn-danger"><a style="color:white;text-decoration:none" href="index.php?op=500">Cancel</a></button><br>
            </fieldset>
        </form>
        HTML;
        $ProductPage->Display();
    }

    public function ProductFormVerifier($id = '', $op)
    {
        $message = '';
        $DB = new DB();
        foreach ($DB->table('products') as $all_products) {
            if (isset($_POST['productCode']) && $all_products['productCode'] === $_POST['productCode']) {
                $message = 'Le produit '.$_POST['productCode'].' existes deja dans la base de donnees. Impossible de l\'ajouter.';
            }
        }
        if ($message === '' && isset($_POST['productCode'])) {
            // AJouter un produit dans la base de donnees
            $sql = 'INSERT INTO products (productCode, productName, productLine, productScale, productVendor,
            productDescription, quantityInStock, buyPrice, MSRP) VALUES ("'.$_POST['productCode'].'", "'.$_POST['productName'].'",
            "'.$_POST['productLine'].'", "'.$_POST['productScale'].'", "'.$_POST['productVendor'].'", "'.$_POST['productDescription'].'",
            "'.$_POST['quantityInStock'].'", "'.$_POST['buyPrice'].'", "'.$_POST['MSRP'].'")';
            $DB->query($sql);
            $DB->disconnect();
            $message = 'Le produit a ete ajoutee avec success.';
        }
        $this->ProductFormAffiche($message, $id, $op);

        return $message;
    }

    public function EditerFormVerifier($id = '')
    {
        $message = '';
        if (isset($_POST['productCode'])) {
            // Mettre a jour un produit de la base de donnees
            $sql = 'UPDATE products SET productName = "'.$_POST['productName'].'",
            productScale = "'.$_POST['productScale'].'", productVendor = "'.$_POST['productVendor'].'",
            productDescription = "'.$_POST['productDescription'].'", quantityInStock = "'.$_POST['quantityInStock'].'",
            buyPrice = "'.$_POST['buyPrice'].'", MSRP = "'.$_POST['MSRP'].'" WHERE productCode = "'.$id.'"';
            $DB = new DB();
            $DB->query($sql);
            $message = 'Le produit a ete mise a jour dans la base de donnees.';
        }
        $this->ProductFormAffiche($message, $id, 501);
    }

    public function AfficherProductCritere($line)
    {
        $ProductPage = new WebPage();
        $ProductPage->title = 'Recherche de tous les produits avec productLine = '.$line;
        $ProductPage->description = 'Bienvenue dans la page de recherche de produits !';
        $DB = new DB();
        $ProductLines = $DB->query('SELECT DISTINCT productLine from products');
        $DataProductLines = TableauSelectProductLineHTML('product', $ProductLines);
        $DB->disconnect();
        $ProductPage->content = <<<HTML
        <form action="index.php?op=505" method="POST" style="width:400px;margin:0 auto;text-align:center">
            Rechercher tous les produits par categorie productLine
            {$DataProductLines}
            <input type="submit" class="btn btn-primary" value="Afficher le resultat">
            <button class="btn btn-danger"><a style="color:white;text-decoration:none" href="index.php?op=500">Cancel</a></button><br>
        </form>
        HTML;
        if (isset($line)) {
            $ProductPage->content .= $this->AfficherProduitParProductLine($line);
        }
        $ProductPage->Display();
    }

    public function AfficherProduitParProductLine($line)
    {
        $DB = new DB();
        $Products = $DB->query('SELECT * from products WHERE productLine = "'.$line.'"');
        $DB->disconnect();

        $html = '<style>';
        $html .= 'th, td {padding: 8px; border: 1px solid black} table {width:90%; margin:0 auto;text-align:center}';
        $html .= '</style>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<th>productCode</th>';
        $html .= '<th>productName</th>';
        $html .= '<th>productLine</th>';
        $html .= '<th>productScale</th>';
        $html .= '<th>productVendor</th>';
        $html .= '<th>productDescription</th>';
        $html .= '<th>quantityInStock</th>';
        $html .= '<th>buyPrice</th>';
        $html .= '<th>MSRP</th>';
        $html .= '</tr>';
        foreach ($Products as $un_produit) {
            $html .= '<tr>';
            $html .= '<td>'.$un_produit['productCode'].'</td>';
            $html .= '<td>'.$un_produit['productName'].'</td>';
            $html .= '<td>'.$un_produit['productLine'].'</td>';
            $html .= '<td>'.$un_produit['productScale'].'</td>';
            $html .= '<td>'.$un_produit['productVendor'].'</td>';
            $html .= '<td>'.$un_produit['productDescription'].'</td>';
            $html .= '<td>'.$un_produit['quantityInStock'].'</td>';
            $html .= '<td>'.$un_produit['buyPrice'].'</td>';
            $html .= '<td>'.$un_produit['MSRP'].'</td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '</table>';

        return $html;
    }

    public function ListJson()
    {
        $DB = new DB();
        $products = $DB->table('products');
        $productsJson = json_encode($products, JSON_PRETTY_PRINT);
        $content_type = 'Content-Type: application/json; charset=UTF-8';
        header($content_type);
        http_response_code(200);
        echo $productsJson; // la reponse est des donnees seulement
    }
}
