<?php

// bloquer l'appel direct à ce fichier
// index.php doit être le point d'entrée
if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Direct access to this php file is forbidden');
}

//require_once 'db_mysqli.php';
require_once 'db_pdo.php';
require_once 'webpage.php';

class payments
{
    public function __construct()
    {
    }

    /**
     * List($customerNumber = '') affiche tous les paiements
     * si aucun customerNumber est spécifié.
     * Sinon affiche seulement les paiements du client.
     */
    public function List($customerNumber = '')
    {
        $DB = new DB();
        if ($customerNumber == '') {
            $payments = $DB->table('payments');
        } else {
            //$sql_str = 'SELECT * FROM payments WHERE customerNumber='.$customerNumber;
            //$payments = $DB->querySelect($sql_str);
            $sql_str = 'SELECT * FROM payments WHERE customerNumber= :customerNumber';
            $params = ['customerNumber' => $customerNumber];
            $payments = $DB->querySelectParam($sql_str, $params);
            //echo 'TOTO';
        }
        //var_dump($payments);
        $DB->disconnect();

        $Page = new WebPage();
        $Page->title = '<i class="fa fa-list"></i> Paiements';
        $Page->description = 'Liste des paiement';
        $Page->content = '<div style="max-width:700px;margin:auto">';
        $Page->content .= '<button class="btn btn-primary" onclick="location.replace(\'index.php?op=705\')">+ Ajouter un paiement</button>';

        // formulaire recherche -----------------
        $Page->content .= '<form action="index.php?op=700" method="POST">';
        $Page->content .= 'Rechercher par no. de client <input type="number" min="0" step="1" name="customerNumber">';
        $Page->content .= '<button><i class="fa fa-search"></i>Rechercher</button>';
        $Page->content .= ' <a href="index.php?op=700">Tous les paiements</a>';
        $Page->content .= '</form>';

        // table ---------------------------------
        $Page->content .= '<table class="table table-striped">';
        $Page->content .= '<thead class="thead-dark">';
        $Page->content .= '<th scope="col">Customer</th>';
        $Page->content .= '<th scope="col">Check No</th>';
        $Page->content .= '<th scope="col">Date</th>';
        $Page->content .= '<th scope="col">$ Amount</th>';
        $Page->content .= '<th scope="col">Actions</th>';
        $Page->content .= '</thead>';
        $Page->content .= '<tbody>';
        foreach ($payments as $un_payment) {
            $Page->content .= '<tr>';
            $Page->content .= '<td>'.$un_payment['customerNumber'].'</td>';
            $Page->content .= '<td>'.$un_payment['checkNumber'].'</td>';
            $Page->content .= '<td>'.$un_payment['paymentDate'].'</td>';
            $Page->content .= '<td>'.$un_payment['amount'].'</td>';
            $Page->content .= '<td><a href="index.php?op=703&customerNumber='.$un_payment['customerNumber'].'&checkNumber='.$un_payment['checkNumber'].'"><i class="fa fa-paste"></i> Voir</a>
            | <a href="index.php?op=701&customerNumber='.$un_payment['customerNumber'].'&checkNumber='.$un_payment['checkNumber'].'"><i class="fa fa-edit"></i> Edit</a>
            | <a href="index.php?op=702&customerNumber='.$un_payment['customerNumber'].'&checkNumber='.$un_payment['checkNumber'].'"><i class="fa fa-remove"></i> Delete</a></td>';
        }
        $Page->content .= '<tbody>';
        $Page->content .= '</table>';
        $Page->content .= '</div>';
        $Page->Display();
    }

    /**
     * ListJson() Service Web (API) retourne la liste des payments
     * en format JSON.
     */
    public function ListJson()
    {
        $DB = new DB();
        $payments = $DB->table('payments');
        $paymentsJson = json_encode($payments, JSON_PRETTY_PRINT);
        $content_type = 'Content-Type: application/json; charset=UTF-8';
        header($content_type);
        http_response_code(200);
        echo $paymentsJson; // la reponse est des données seulement
    }

    /**
     * Delete() effacer un paiement.
     *
     * La clé primaire de la table payments est l'union
     * des 2 colonnes $customerNumber et $checkNumber
     */
    public function Delete($customerNumber, $checkNumber)
    {
        $sql_str = 'DELETE FROM payments WHERE customerNumber='.$customerNumber.' AND checkNumber="'.$checkNumber.'"';
        //var_dump($sql_str);
        $DB = new DB();
        $DB->query($sql_str);
        //echo 'deleted';
    }

    /**
     * Edit() modifier un paiement.
     *
     * La clé primaire de la table payments est l'union
     * des 2 colonnes $customerNumber et $checkNumber
     */
    public function Edit($old_customerNumber, $old_checkNumber, $Payment, $message = '')
    {
        $Page = new WebPage();
        $Page->title = '<i class="fa fa-edit"></i> Modifier ou ajouter un paiement';
        $Page->description = 'Modifier ou ajouter un paiement';

        // mettre un long contenu html dans une variable
        $Page->content = '';
        $Page->content .= '<div class="alert alert-danger" role="alert">'.$message.'</div>';
        $Page->content .= '<form action="index.php?op=704" method="POST" style="width:300px;margin:auto">';
        $Page->content .= '<input type="hidden" name="old_customerNumber" value="'.$Payment['customerNumber'].'">';
        $Page->content .= '<input type="hidden" name="old_checkNumber" value="'.$Payment['checkNumber'].'">';
        $Page->content .= '<label>customerNumber</label> <input class="form-control" type="number" min="0" step="1" name="customerNumber" maxlength="11" value="'.$Payment['customerNumber'].'" required><br>';
        $Page->content .= '<label>checkNumber</label> <input class="form-control" type="text" name="checkNumber" maxlength="50" value="'.$Payment['checkNumber'].'" required><br>';
        $Page->content .= '<label>Date</label> <input class="form-control" type="date" name="paymentDate" maxlength="25" value="'.$Payment['paymentDate'].'" required> mm/dd/yyyy<br>';
        $Page->content .= '<label>Amount $</label> <input class="form-control" type="number" step="0.01" name="amount" value="'.$Payment['amount'].'" required><br>';
        $Page->content .= '<input type="submit" class="btn btn-success" value="Sauvegarder">';
        $Page->content .= '&nbsp;<button type="button" class="btn btn-danger" onclick="window.location.href=\'index.php?op=700\'">Annuler</button>';
        $Page->content .= '</form>';

        $Page->content .= '<br>';

        $Page->Display();
    }

    /**
     * Save() sauvegarder un paiement.
     *
     * La clé primaire de la table payments est l'union
     * des 2 colonnes $customerNumber et $checkNumber
     */
    public function Save($old_customerNumber, $old_checkNumber)
    {
        //A FAIRE verfier les données
        $error_msg = '';
        var_dump($_POST['customerNumber']);
        if (!isset($_POST['customerNumber']) or $_POST['customerNumber'] == '') {
            $error_msg .= '-no. client est requis';
        } elseif (!is_numeric($_POST['customerNumber'])) {
            $error_msg .= '-no. client doit être un nombre entier';
        } else {
            //verfier si le client existe
            $DB = new DB();
            $sql_str = 'SELECT * FROM customers WHERE customerNumber='.$_POST['customerNumber'];
            $Customers = $DB->querySelect($sql_str);
            if (count($Customers) == 0) {
                $error_msg .= '-client inexsitant';
            }
        }

        if (!isset($_POST['checkNumber']) or $_POST['checkNumber'] == '') {
            $error_msg .= '-no. check est requis';
        } elseif (strlen($_POST['checkNumber']) > 50) {
            $error_msg .= '-no. check trop long';
        }

        if (!isset($_POST['paymentDate']) or $_POST['paymentDate'] == '') {
            $error_msg .= '-date est requise';
        }
        if (!isset($_POST['amount']) or $_POST['amount'] == '') {
            $error_msg .= '-amount est requis';
        } elseif (!is_numeric($_POST['amount'])) {
            $error_msg .= '-amount erroné';
        }

        if ($error_msg) {
            $this->Edit($old_customerNumber, $old_checkNumber, $_POST, $error_msg);
        } elseif ($old_customerNumber == '') {
            // tout OK
            //nouveau paiement
            $sql_str = 'INSERT INTO payments (customerNumber,checkNumber,paymentDate,amount) VALUES ('.$_POST['customerNumber'].',"'.$_POST['checkNumber'].'","'.$_POST['paymentDate'].'",'.$_POST['amount'].')';
            //var_dump($sql_str);
            $Payment = $DB->query($sql_str);
            $this->List(); // retourne à la liste
        } else {
            // tout OK
            // paiement existant
            $sql_str = 'UPDATE payments SET customerNumber='.$_POST['customerNumber'].' ,checkNumber="'.$_POST['checkNumber'].'", paymentDate="'.$_POST['paymentDate'].'",amount='.$_POST['amount'].' WHERE customerNumber='.$old_customerNumber.' AND checkNumber="'.$old_checkNumber.'"';
            //var_dump($sql_str);
            $Payment = $DB->query($sql_str);
            $this->List(); // retourne à la liste
        }
    }

    /**
     * Display() afficher un paiement.
     *
     * La clé primaire de la table payments est l'union
     * des 2 colonnes $customerNumber et $checkNumber
     */
    public function Display($customerNumber, $checkNumber)
    {
        $DB = new DB();
        $sql_str = 'SELECT * FROM payments WHERE customerNumber= :customerNumber AND checkNumber=:checkNumber';
        $params = ['customerNumber' => $customerNumber, 'checkNumber' => $checkNumber];
        $payment = $DB->querySelectParam($sql_str, $params);
        $payment = $payment[0];

        $Page = new WebPage();
        $Page->title = 'Paiement';
        $Page->description = $Page->title;
        $Page->content = <<<HTML
        <div class="jumbotron" style="width:300px;margin:auto">
        Customer :{$payment['customerNumber']} <br>
        Check No : {$payment['checkNumber']}<br>
        Date : {$payment['paymentDate']}<br>
        Amount : {$payment['amount']}<br>

        <a href="index.php?op=701&customerNumber={$payment['customerNumber']}&checkNumber={$payment['checkNumber']}"><i class="fa fa-edit"></i>Edit</a> |
        <a href="index.php?op=702&customerNumber={$payment['customerNumber']}&checkNumber={$payment['checkNumber']}"><i class="fa fa-remove"></i>Delete</a><br>
        <a href='index.php?op=700'><i class="fa fa-list"></i>Tous les paiements</a>
        </div>
HTML;
        $Page->Display();
    }
}
