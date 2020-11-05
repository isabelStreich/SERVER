<?php

    require_once 'db_pdo.php';
    require_once 'webpage.php';

    if (!defined('INDEX_LOADED')) {
        http_response_code(403);
        die('acces direct a ce fichier est interdit');
    }

    class customers
    {
        public function __contruct()
        {
        }

        public function List($customerNumber = '')
        {
            $DB = new DB();

            if ($customerNumber == '') {
                $customers = $DB->table('customers');
            } else {
                // $sql_str = 'SELECT * FROM customers WHERE customerNumber='.$filtre;
                //$customers = $DB->querySelect($sql_str);
                $sql_str = 'SELECT * FROM customers WHERE customerNumber=:customerNumber';

                $params = ['customerNumber' => $customerNumber];
                $customers = $DB->querySelectParam($sql_str, $params);
            }

            //$customers = $DB->table('customers');
            //var_dump($customers);

            $Page = new webPage();
            $Page->title = 'Table Customers';
            $Page->description = 'affichage table connection';

            $Page->content = '';

            $Page->content .= '<form action="index.php?op=600" method="POST">';
            $Page->content .= 'Rechercher par no. de client <input  class="form-control" style="width:300px;" type="text" name="customerNumber">';
            $Page->content .= '<input class="btn btn-primary"  type="submit" value="Rechercher">';
            $Page->content .= '</form>';
            $Page->content .= '<button class="btn btn-success" type="button"><a href="index.php?op=603">Ajouter un client</a></button>';

            $Page->content .= '<table class="table table-striped">';
            $Page->content .= '<tr>';
            $Page->content .= '<th>customerNumber</th>';
            $Page->content .= '<th>customerName</th>';
            $Page->content .= '<th>contactLastName</th>';
            $Page->content .= '<th>contactFirstName</th>';
            $Page->content .= '<th>phone</th>';
            $Page->content .= '<th>addressLine1</th>';
            $Page->content .= '<th>addressLine2</th>';
            $Page->content .= '<th>city</th>';
            $Page->content .= '<th>state</th>';
            $Page->content .= '<th>postalCode</th>';
            $Page->content .= '<th>country</th>';
            $Page->content .= '<th>salesRepEmployeeNumber</th>';
            $Page->content .= '<th>creditLimit</th>';
            $Page->content .= '<th>Operation</th>';
            $Page->content .= '</tr>';
            foreach ($customers as $un_customer) {
                $Page->content .= '<tr>';
                $Page->content .= '<td> <a href=index.php?op=602&customerNumber='.$un_customer['customerNumber'].'>'.$un_customer['customerNumber'].'</a></td>';
                $Page->content .= '<td>'.$un_customer['customerName'].'</td>';
                $Page->content .= '<td>'.$un_customer['contactLastName'].'</td>';
                $Page->content .= '<td>'.$un_customer['contactFirstName'].'</td>';
                $Page->content .= '<td>'.$un_customer['phone'].'</td>';
                $Page->content .= '<td>'.$un_customer['addressLine1'].'</td>';
                $Page->content .= '<td>'.$un_customer['addressLine2'].'</td>';
                $Page->content .= '<td>'.$un_customer['city'].'</td>';
                $Page->content .= '<td>'.$un_customer['state'].'</td>';
                $Page->content .= '<td>'.$un_customer['postalCode'].'</td>';
                $Page->content .= '<td>'.$un_customer['country'].'</td>';
                $Page->content .= '<td>'.$un_customer['salesRepEmployeeNumber'].'</td>';
                $Page->content .= '<td>'.$un_customer['creditLimit'].'</td>';
                $Page->content .= '<td> <a href="index.php?op=606&customerNumber='.$un_customer['customerNumber'].'"  style="color:blue !important;text-decoration:none"><i class="fas fa-edit"></i> Edit</a><a href="index.php?op=601&customerNumber='.$un_customer['customerNumber'].'"> <i class="fas fa-trash"></i> Delete </a></td>';
                $Page->content .= '</tr>';
            }

            $Page->content .= '</table>';

            $Page->display();
            die();
        }

        /**
         * ListJson() Service Web (API) retourne la lsite des customers.
         */
        public function ListJson()
        {
            $DB = new DB();
            $customers = $DB->table('customers');

            $customersJson = json_encode($customers, JSON_PRETTY_PRINT);
            $content_type = 'Content-Type: application/json; charset=UTF-8';
            header($content_type);

            http_response_code(200);
            echo $customersJson;
        }

        public function displayRecord($customerNumber)
        {
            $DB = new DB();

            $sql_str = 'SELECT * FROM customers WHERE customerNumber='.$customerNumber;
            $record = $DB->querySelect($sql_str);
            $record = $record[0];

            $Page = new webPage();

            $Page->title = 'Description du record';
            $Page->description = 'description do record ';

            //*******STOCKER LE RECORD DANS UN OBJET ET EXTRAIRE CHACUNE DE SES INFORMATIONS, ON RERENCE AU BON RECORD AVEC LE CUSOTMER NUMBER RECU EN PARAMETRE

            //REGLER LA RECHERCHE, RETOURNE RIEN

            $Page->content = '';

            $Page->content .= '<form method="POST" style="width:390px;margin:30px">';
            $Page->content .= '<fieldset>';
            $Page->content .= '<label> Numero de client </label> <input class="form-control" type="text" name="customerNumber" maxlength="50" required value="'.$record['customerNumber'].'"> <br>';
            $Page->content .= '<label> Nom du client </label> <input class="form-control" type="text" name="customerName" maxlength="255" value="'.$record['customerName'].'" <br>';
            $Page->content .= '<label> Prénom du contact </label> <input class="form-control" type="text" name="contactLastName" maxlength="50" value="'.$record['contactLastName'].'"><br>';
            $Page->content .= '<label> Prénom du contact </label> <input class="form-control" type="text" name="contactFirstName" maxlength="50" value="'.$record['contactFirstName'].'"><br>';
            $Page->content .= '<label> Téléphone </label> <input class="form-control" type="text" name="phone" maxlength="50" value="'.$record['phone'].'"> <br>';
            $Page->content .= '<label> Adresse (Ligne 1) </label> <input class="form-control" type="text" name="addressLine1" maxlength="50" value="'.$record['addressLine1'].'"> <br>';
            $Page->content .= '<label> Adresse (Ligne 2) </label> <input class="form-control" type="text" name="addressLine2" maxlength="50" value="'.$record['addressLine2'].'"> <br>';
            $Page->content .= '<label> Ville </label> <input class="form-control" type="text" name="city" maxlength="50" value="'.$record['city'].'"> <br>';
            $Page->content .= '<label> État / Province </label> <input class="form-control" type="text" name="state" maxlength="50" value="'.$record['state'].'"> <br>';
            $Page->content .= '<label> Code postal </label> <input class="form-control" type="text" name="postalCode" maxlength="15" value="'.$record['postalCode'].'"> <br>';
            $Page->content .= '<label> Pays </label> <input class="form-control" type="text" name="country" maxlength="50" value="'.$record['country'].'"> <br>';
            $Page->content .= '<label> Numéro de lemployee représentant </label> <input class="form-control" type="text" name="salesRepEmployeeNumber" maxlength="11" value="'.$record['salesRepEmployeeNumber'].'"> <br>';
            $Page->content .= '<label> Limite de crédit </label> <input class="form-control" type="text" name="creditLimit" maxlength="50" value="'.$record['creditLimit'].'"> <br>';
            $Page->content .= '</fieldset>';
            $Page->content .= '</form>';
            $Page->content .= '<button type="button" class="btn btn-primary"><a href="index.php?op=600">Retour à la table</a></button>';

            $Page->display();
            die();
        }

        public function createRecord($User_info, $message = '')
        {
            $Page = new webPage();

            $Page->title = 'Ajouter un client';
            $Page->description = 'ajout de client';

            $Page->content = '';
            $Page->content = '<div class="alert alert-danger" role="alert">'.$message.'</div>';

            $Page->content .= '<form action="index.php?op=604" method="POST" style="width:390px;margin:30px">';
            $Page->content .= '<fieldset>';
            $Page->content .= '<label> Numero de client </label> <input class="form-control" type="number" name="customerNumber" required value="'.$User_info['customerNumber'].'"> <br>';
            $Page->content .= '<label> Nom du client </label> <input class="form-control" type="text" name="customerName" maxlength="255" value="'.$User_info['customerName'].'" <br>';
            $Page->content .= '<label> Nom de famille contact </label> <input class="form-control" type="text" name="contactLastName" maxlength="50" value="'.$User_info['contactLastName'].'"><br>';
            $Page->content .= '<label> Prénom du contact </label> <input class="form-control" type="text" name="contactFirstName" maxlength="50" value="'.$User_info['contactFirstName'].'"><br>';
            $Page->content .= '<label> Téléphone </label> <input class="form-control" type="text" name="phone" maxlength="50" value="'.$User_info['phone'].'"> <br>';
            $Page->content .= '<label> Adresse (Ligne 1) </label> <input class="form-control" type="text" name="addressLine1" maxlength="50" value="'.$User_info['addressLine1'].'"> <br>';
            $Page->content .= '<label> Adresse (Ligne 2) </label> <input class="form-control" type="text" name="addressLine2" maxlength="50" value="'.$User_info['addressLine2'].'"> <br>';
            $Page->content .= '<label> Ville </label> <input class="form-control" type="text" name="city" maxlength="50" value="'.$User_info['city'].'"> <br>';
            $Page->content .= '<label> État / Province </label> <input class="form-control" type="text" name="state" maxlength="50" value="'.$User_info['state'].'"> <br>';
            $Page->content .= '<label> Code postal </label> <input class="form-control" type="text" name="postalCode" maxlength="15" value="'.$User_info['postalCode'].'"> <br>';
            $Page->content .= '<label> Pays </label> <input class="form-control" type="text" name="country" maxlength="50" value="'.$User_info['country'].'"> <br>';
            $Page->content .= '<label> Numéro de lemployee représentant </label> <input class="form-control" type="text" name="salesRepEmployeeNumber" value="'.$User_info['salesRepEmployeeNumber'].'"> <br>';
            $Page->content .= '<label> Limite de crédit </label> <input class="form-control" type="text" name="creditLimit"  value="'.$User_info['creditLimit'].'"> <br>';
            $Page->content .= '</fieldset>';
            $Page->content .= '<input class="btn btn-success" type="submit" value="Ajouter">';

            $Page->content .= '</form>';

            $Page->display();
            die();
        }

        public function recordVerifier()
        {
            $record = $_POST;

            //Validation
            $err_message = '';

            $DB = new DB();

            if (!isset($record['customerNumber'])) {
                $err_message = ' Le numéro de client est manquant. ';
            }

            $clients = $DB->querySelect('SELECT customerNumber from customers');

            foreach ($clients as $un_client) {
                if ($un_client['customerNumber'] == $record['customerNumber']) {
                    $err_message .= 'Le numéro de client existe déjà. ';
                }
            }

            if (!isset($record['customerName']) or $record['customerName'] === '') {
                $err_message .= 'Le nom du client est manquant. ';
            } elseif (strlen($record['customerName']) > 50) {
                $err_message .= 'Le nom du client est trop long. ';
            }

            if (!isset($record['contactLastName']) or $record['contactLastName'] === '') {
                $err_message .= 'Le nom de famille du contact est manquant. ';
            } elseif (strlen($record['contactLastName']) > 50) {
                $err_message .= 'Le nom de famille du contact est trop long. ';
            }

            if (!isset($record['contactFirstName']) or $record['contactFirstName'] === '') {
                $err_message .= 'Le prénom du contact est manquant. ';
            } elseif (strlen($record['contactFirstName']) > 50) {
                $err_message .= 'Le prénom du client est trop long. ';
            }

            if (!isset($record['phone']) or $record['phone'] === '') {
                $err_message .= 'Le numéro de téléphone est manquant. ';
            } elseif (strlen($record['phone']) > 50) {
                $err_message .= 'Le numéro de téléphone est trop long. ';
            }

            if (!isset($record['addressLine1']) or $record['addressLine1'] === '') {
                $err_message .= 'Laddresse est manquante. ';
            } elseif (strlen($record['addressLine1']) > 50) {
                $err_message .= 'Laddresse est trop longue. ';
            }

            if (isset($record['addressLine2']) and strlen($record['addressLine2']) > 50) {
                $err_message .= 'Laddresse est trop longue. ';
            }

            if (!isset($record['city']) or $record['city'] === '') {
                $err_message .= 'La ville est manquante. ';
            } elseif (strlen($record['city']) > 50) {
                $err_message .= 'La ville est trop longue. ';
            }

            if (isset($record['state']) and strlen($record['state']) > 50) {
                $err_message .= 'Letat est trop long. ';
            }

            if (isset($record['postalCode']) and strlen($record['postalCode']) > 50) {
                $err_message .= 'Le code postal est trop long. ';
            }

            if (!isset($record['country']) or $record['country'] === '') {
                $err_message .= 'Le pays est manquant. ';
            } elseif (strlen($record['country']) > 50) {
                $err_message .= 'Le pays est trop long. ';
            }

            if (!isset($record['salesRepEmployeeNumber']) or $record['salesRepEmployeeNumber'] === '') {
                $err_message .= 'Le numéro de lemployee est manquant. ';
            }

            $employees = $DB->querySelect('SELECT employeeNumber from employees');

            $employeeExiste = false;

            foreach ($employees as $un_employee) {
                if ($un_employee['employeeNumber'] == $record['salesRepEmployeeNumber']) {
                    $employeeExiste = true;
                }
            }

            if (!$employeeExiste) {
                $err_message .= 'Lemployee nexiste pas';
            }

            if ($err_message !== '') {
                $this->createRecord($record, $err_message);
            } else {
                $sql_str = 'INSERT INTO customers (customerNumber, customerName, contactLastName, contactFirstName, phone, addressLine1, addressLine2, city, state, postalCode, country, salesRepEmployeeNumber, creditLimit) ';

                $sql_str .= "VALUES ('".(int) $record['customerNumber']."', '".$record['customerName']."', '".$record['contactLastName']."',' ".$record['contactFirstName']."',' ".$record['phone']."','  ".$record['addressLine1']."','  ".$record['addressLine2']."','  ".$record['city']."','  ".$record['state']."','  ".$record['postalCode']."','  ".$record['country']."','  ".$record['salesRepEmployeeNumber']."','  ".$record['creditLimit']."')";

                $customer = $DB->query($sql_str);
                header('location: index.php?op=600');
            }
        }

        public function deleteRecord($customerNumber)
        {
            $DB = new DB();

            $sql_str = 'DELETE FROM customers WHERE CustomerNumber="'.$customerNumber.'"';
            $record = $DB->query($sql_str);
            header('location: index.php?op=600');
        }

        public function updateRecord($record, $message = '')
        {
            $Page = new webPage();

            $Page->title = 'Modifier le client';
            $Page->description = 'modifier le client ';

            $Page->content = '';

            $Page->content = '<div class="alert alert-danger" role="alert">'.$message.'</div>';

            $Page->content .= '<form action="index.php?op=605" method="POST" style="width:390px;margin:30px">';
            $Page->content .= '<fieldset>';
            $Page->content .= '<label> Numero de client </label> <input class="form-control" type="text" name="customerNumber" maxlength="50" required value="'.$record['customerNumber'].'"> <br>';
            $Page->content .= '<label> Nom du client </label> <input class="form-control" type="text" name="customerName" maxlength="255" value="'.$record['customerName'].'" <br>';
            $Page->content .= '<label> Nom de famille du contact </label> <input class="form-control" type="text" name="contactLastName" maxlength="50" value="'.$record['contactLastName'].'"><br>';
            $Page->content .= '<label> Prénom du contact </label> <input class="form-control" type="text" name="contactFirstName" maxlength="50" value="'.$record['contactFirstName'].'"><br>';
            $Page->content .= '<label> Téléphone </label> <input class="form-control" type="text" name="phone" maxlength="50" value="'.$record['phone'].'"> <br>';
            $Page->content .= '<label> Adresse (Ligne 1) </label> <input class="form-control" type="text" name="addressLine1" maxlength="50" value="'.$record['addressLine1'].'"> <br>';
            $Page->content .= '<label> Adresse (Ligne 2) </label> <input class="form-control" type="text" name="addressLine2" maxlength="50" value="'.$record['addressLine2'].'"> <br>';
            $Page->content .= '<label> Ville </label> <input class="form-control" type="text" name="city" maxlength="50" value="'.$record['city'].'"> <br>';
            $Page->content .= '<label> État / Province </label> <input class="form-control" type="text" name="state" maxlength="50" value="'.$record['state'].'"> <br>';
            $Page->content .= '<label> Code postal </label> <input class="form-control" type="text" name="postalCode" maxlength="15" value="'.$record['postalCode'].'"> <br>';
            $Page->content .= '<label> Pays </label> <input class="form-control" type="text" name="country" maxlength="50" value="'.$record['country'].'"> <br>';
            $Page->content .= '<label> Numéro de lemployee représentant </label> <input class="form-control" type="text" name="salesRepEmployeeNumber" maxlength="11" value="'.$record['salesRepEmployeeNumber'].'"> <br>';
            $Page->content .= '<label> Limite de crédit </label> <input class="form-control" type="text" name="creditLimit" maxlength="50" value="'.$record['creditLimit'].'"> <br>';
            $Page->content .= '</fieldset>';
            $Page->content .= '<input type="submit" class ="btn btn-primary" value="Mettre à jour">';

            $Page->content .= '</form>';

            $Page->display();
            die();
        }

        public function updateRecordVerifier()
        {
            $record = $_POST;

            //Validation
            $err_message = '';

            $DB = new DB();

            // if (!isset($record['customerNumber'])){
            //     $err_message=' Le numéro de client est manquant. ';

            // }

            if (!isset($record['customerName']) or $record['customerName'] === '') {
                $err_message .= 'Le nom du client est manquant. ';
            } elseif (strlen($record['customerName']) > 50) {
                $err_message .= 'Le nom du client est trop long. ';
            }

            if (!isset($record['contactLastName']) or $record['contactLastName'] === '') {
                $err_message .= 'Le nom de famille du contact est manquant. ';
            } elseif (strlen($record['contactLastName']) > 50) {
                $err_message .= 'Le nom de famille du contact est trop long. ';
            }

            if (!isset($record['contactFirstName']) or $record['contactFirstName'] === '') {
                $err_message .= 'Le prénom du contact est manquant. ';
            } elseif (strlen($record['contactFirstName']) > 50) {
                $err_message .= 'Le prénom du client est trop long. ';
            }

            if (!isset($record['phone']) or $record['phone'] === '') {
                $err_message .= 'Le numéro de téléphone est manquant. ';
            } elseif (strlen($record['phone']) > 50) {
                $err_message .= 'Le numéro de téléphone est trop long. ';
            }

            if (!isset($record['addressLine1']) or $record['addressLine1'] === '') {
                $err_message .= 'Laddresse est manquante. ';
            } elseif (strlen($record['addressLine1']) > 50) {
                $err_message .= 'Laddresse est trop longue. ';
            }

            if (isset($record['addressLine2']) and strlen($record['addressLine2']) > 50) {
                $err_message .= 'Laddresse est trop longue. ';
            }

            if (!isset($record['city']) or $record['city'] === '') {
                $err_message .= 'La ville est manquante. ';
            } elseif (strlen($record['city']) > 50) {
                $err_message .= 'La ville est trop longue. ';
            }

            if (isset($record['state']) and strlen($record['state']) > 50) {
                $err_message .= 'Letat est trop long. ';
            }

            if (isset($record['postalCode']) and strlen($record['postalCode']) > 50) {
                $err_message .= 'Le code postal est trop long. ';
            }

            if (!isset($record['country']) or $record['country'] === '') {
                $err_message .= 'Le pays est manquant. ';
            } elseif (strlen($record['country']) > 50) {
                $err_message .= 'Le pays est trop long. ';
            }

            if (!isset($record['salesRepEmployeeNumber']) or $record['salesRepEmployeeNumber'] === '') {
                $err_message .= 'Le numéro de lemployee est manquant. ';
            }

            $employees = $DB->querySelect('SELECT employeeNumber from employees');

            $employeeExiste = false;

            foreach ($employees as $un_employee) {
                if ($un_employee['employeeNumber'] == $record['salesRepEmployeeNumber']) {
                    $employeeExiste = true;
                }
            }

            if (!$employeeExiste) {
                $err_message .= 'Lemployee nexiste pas';
            }

            if ($err_message !== '') {
                $this->updateRecord($record, $err_message);
            } else {
                $sql_str = 'UPDATE customers SET customerName ="'.$record['customerName'].'", contactLastName="'.$record['contactLastName'].'", contactFirstName="'.$record['contactFirstName'].'", phone="'.$record['phone'].'", addressLine1="'.$record['addressLine1'].'", addressLine2="'.$record['addressLine2'].'", city="'.$record['city'].'",state="'.$record['state'].'", postalCode="'.$record['postalCode'].'", country="'.$record['country'].'", salesRepEmployeeNumber='.$record['salesRepEmployeeNumber'].', creditLimit='.$record['creditLimit'];
                $sql_str .= ' WHERE customerNumber='.$record['customerNumber'].';';

                echo'bonjour';

                $customer = $DB->query($sql_str);

                header('location: index.php?op=600');
            }
        }
    }
