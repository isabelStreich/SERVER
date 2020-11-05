<?php

session_start();

// indique que index.php est chargée
define('INDEX_LOADED', true);

// fichiers requis (dépendances)
require_once 'global_defines.php';
require_once 'tools.php';

// https://www.php.net/manual/en/language.oop5.autoload.php
//load automatiquement les fichiers
// spl_autoload_register(function ($class_name) {
//     include $class_name.'.php';
// });

require_once 'webpage.php';
require_once 'users.php';
require_once 'payments.php';
require_once 'customers.php'; // Christian
require_once 'products.php'; // Christian Djipsu
require_once 'order.php'; //Victor
require_once 'employees.php'; //Yannick
require_once 'offices.php'; //Dmytro
require_once 'productlines.php'; //Isabel

// controller
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = 0; // affiche page d'acceuil
}

// si pas connecté code op entre 0 et 4 seulement
if (!isset($_SESSION['email']) and $op >= 5) {
    Crash(401, 'Vous devez être connecté pour effectuer cette opération');
}

switch ($op) {
    case 0:
        HomePage();
        break;

    case 1:
        $User_Info = [
            'email' => '',
            'pw' => '',
        ];
        $Users = new Users();
        $Users->LoginFormAffiche($User_Info);
        break;

    case 2:
        $Users = new Users();
        $Users->LoginFormVerifier();
        break;

    case 3:
        $User_Info = [
            'fullname' => '',
            'adresse' => '',
            'ville' => '',
            'province' => '',
            'pays' => 'CA',
            'code_postal' => '',
            'langue' => 'fr',
            'autre_langue' => '',
            'email' => '',
            'pw' => '',
            'pw2' => '',
            'spam_ok' => 1,
        ];
        $Users = new Users();
        $Users->InscriptionFormAffiche($User_Info);
        break;

    case 4:
        $Users = new Users();
        $Users->InscriptionFormVerifier();
        break;

    // tous les op suivantes l'usager doit être connecté
    case 5:
        // déconnection
        $_SESSION['email'] = null;
        HomePage();
        break;

    case 10:
        $Users = new Users();
        $Users->List();

        // no break
    case 98:
        //affiche fichier serveur.log
        $Page = new WebPage();
        $Page->title = 'Log du serveur';
        $Page->content = LogAffiche();
        $Page->Display();
        break;

    case 99:
        TableauAffiche($_SERVER);
        break;
    break;

    // OFFICES===============================================================
    case 100:
        //main list
        $offices = new offices();
        $offices->List();
        $id_OfficeCode = $_GET['officeCode'];
        $offices->edit($id_OfficeCode);
        break;

    case 101:
        // add (Create new office)
        $offices = new offices();
        $offices->create();
        break;

    case 102:
        // read One office
        $offices = new offices();
        $offices->read_one($_GET['officeCode']);

        break;

    case 103:
        // edit
        $offices = new offices();
        $offices->edit($_GET['officeCode']);

        break;

    case 104:
        // delete
        $offices = new offices();
        $offices->delete($_GET['officeCode']);
        break;

    case 105:
        //save
        $offices = new offices();
        $offices->save();
        break;

    case 110:
        // Servise web api retourne la liste des offices en format JSON
        $offices = new offices();
        $offices->ListJson();
        break;

    //PRODUCTLINE
    case 200:
        $productlines = new productlines();

        if (isset($_POST['produitFiltre'])) {
            $productlines->List($_POST['produitFiltre']);
        } else {
            $productlines->List();
        }

        break;

    case 201:
        // edit
        $product = new productlines();
        $DB = new DB();
        $id = $_GET['id'];
        $sql = "SELECT * FROM productlines WHERE productLine ='".$id."'";

        $productlines = $DB->querySelect($sql);
        $productline = $productlines[0];
        $product->Edit($productline);

        if (isset($_POST['productline'])) {
            break;
        } else {
            $productlines->List();
        }
        break;

    case 202:
        // Delete
        $productlines = new productlines();
        $id = $_GET['id'];
        $productlines->Delete($id);
        break;

    case 203:
        // Display
       $productlines = new productlines();
        $id = $_GET['id'];
        $productlines->Display($id);
        break;

    case 204:
        // Save
        $productlines = new productlines();
        $id = $_POST['productLine'];
        $productlines->Save($id);
        break;

    case 205:
        // Add
        $productlines_info = [
            'productLine' => '',
            'textDescription' => '',
            'htmlDescription' => '',
            'image' => '',
        ];
        $productlines = new productlines();
        $productlines->Add();
        break;
    case 206:
        //save edit
        $productlines = new productlines();
        $id = $_POST['productLine'];
        $productlines->SaveEdit($id);
        break;

    case 210:
        //service web API returne la liste de productlines
        $productlines = new productlines();
        $productlines->ListJson();
        break;

    //employees......................................................
    case 300:
        //liste employees
       $employees = new employees();
       if (isset($_POST['employeeNumber'])) {
           $employees->list($_POST['employeeNumber']);
       } else {
           $employees->list();
       }

   break;

   case 301:
//editer un employees
        $employees = new employees();
        //$employees->edit($employeeNumber);

        if (isset($_GET['employeeNumber'])) {
            $employees->edit($_GET['employeeNumber']);
        } else {
            Crash(400, 'EmployeeNumber pas defini op=301');
        }
   break;
case 302:
   //suprimer un employee
   $employees = new employees();
   if (isset($_GET['employeeNumber'])) {
       $employees->delete($_GET['employeeNumber']);
   } else {
       Crash(400, 'EmployeeNumber pas defini op=302');
   }

   break;
case 303:
   //afficher les informations d'un employes
         $employees = new employees();

        if (isset($_GET['employeeNumber'])) {
            $employees->Display($_GET['employeeNumber']);
        } else {
            Crash(400, 'EmployeeNumber pas defini op=303');
        }

       break;
   case 304:
       $employees = new employees();
       if (isset($_GET['employeeNumber'])) {
           $employees->save($_GET['employeeNumber']);
       } else {
           Crash(400, 'EmployeeNumber pas defini op=304');
       }

   break;
   case 305:
       $employees = new employees();
           $employees->add();

   break;
   // case 306:

   case 310:
       //service web (API) retourne la liste des employees en format JSON
       $employees = new employees();
       $employees->listJson();
   break;

//ORDERS -----------------------------------------------------------------------------------------------------------
case 400:
    $order = new order();
    if (isset($_POST['orderNumber'])) {
        $order->list($_POST['orderNumber']);
    } else {
        $order->list();
    }

break;
case 401:
    $order = new order();
    $order->updateVerification();
break;
case 405:
    $order = new order();
    $id = $_GET['id'];
    $order->afficherOrder($_GET['id']);
break;
case 406:
    $userInfo = ['customerNumber' => '', 'requiredDate' => '', 'comments' => ''];
    $order = new order();
    $order->addOrder($userInfo);
break;
case 407:
    $order = new order();
    $order->addOrderVerification();
break;
case 408:
    $order = new order();
    $orderNumber = $_GET['id'];
    $order->removeOrder($orderNumber);
break;
case 409:
    $userInfo = ['customerNumber' => '', 'requiredDate' => '', 'comments' => '', 'status' => '', 'shippedDate' => '', 'orderDate' => '', 'customerNumber' => ''];
    $order = new order();
    $order->updateOrder($userInfo, $_GET['id']);
break;
case 410:
    //SERVICE (API) RETOURNE LA LISTE DES ORDER
    //EN FORMAT JSON
    $order = new order();
    $order->ListJson();
break;

case 500:
    // display products table with link
    $products = new products();
    $products->List();
    break;
case 501:
    // edit
    $products = new products();
    if (isset($_POST['productCode'])) {
        $id = $_POST['productCode'];
    } else {
        $id = $_GET['id'];
    }
    $products->EditerFormVerifier($id);
    break;
case 502:
    // delete
    $products = new products();
    if (isset($_GET['ids'])) {
        $ids = $_GET['ids'];
        $products->DeleteProduct($ids);
        $products->List();
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $products->DeleteProduct($id);
        $products->List();
    }
    break;
case 503:
    // display a product detailed information by id = productCode
    $id = $_GET['id'];
    $products = new products();
    $products->Display($id);
    break;
case 504:
    // Ajouter un produit dans la BD
    $products = new products();
    $products->ProductFormVerifier('', 504);
    break;
case 505:
    $line = '';
    $products = new products();
    if (isset($_POST['product'])) {
        $line = $_POST['product'];
    }
    $products->AfficherProductCritere($line);
    break;
case 510:
    $products = new products();
    $products->ListJson();
    break;

//CUSTOMERS
case 600:
    $customers = new customers();

    if (isset($_POST['customerNumber'])) {
        $customers->List($_POST['customerNumber']);
    } else {
        $customers->List();
    }

break;

case 601:
    $customers = new customers();
    $customers->deleteRecord($_GET['customerNumber']);
break;

case 602:
    //display du record
    $customers = new customers();
    $customers->displayRecord($_GET['customerNumber']);
break;

case 603:
$User_info = [
    'customerNumber' => '',
    'customerName' => '',
    'contactLastName' => '',
    'contactFirstName' => '',
    'phone' => '',
    'addressLine1' => '',
    'addressLine2' => '',
    'city' => '',
    'state' => '',
    'postalCode' => '',
    'country' => '',
    'salesRepEmployeeNumber' => '',
    'creditLimit' => '0',
];
    $customers = new customers();
    $customers->createRecord($User_info);

break;

case 604:
    $customers = new customers();
    $customers->recordVerifier();

break;

case 605:
    $customers = new customers();
    $customers->updateRecordVerifier();

break;

case 606:
    $DB = new DB();

    $sql_str = 'SELECT * FROM customers WHERE customerNumber='.$_GET['customerNumber'];

    $record = $DB->querySelect($sql_str);
    $record = $record[0];

    $customers = new customers();
    $customers->updateRecord($record);
break;

case 610:
    //Service wed (API) retourn la liste des customers  en format JSON
    $customers = new customers();
    $customers->ListJson();
break;

    // PAYMENTS ------------------------------------
    case 700:
        // LIST PAYMENTS
        $Payments = new Payments();
        if (isset($_POST['customerNumber'])) {
            $Payments->List($_POST['customerNumber']);
        } else {
            $Payments->List();
        }

        break;

    case 701:
        //EDIT payments
        //var_dump($sql_str);
        $DB = new DB();
        $customerNumber = $_GET['customerNumber'];
        $checkNumber = $_GET['checkNumber'];
        $sql_str = 'SELECT * FROM payments WHERE customerNumber='.$customerNumber.' AND checkNumber="'.$checkNumber.'"';
        $Payment = $DB->querySelect($sql_str);
        $Payment = $Payment[0];
        $Payments = new Payments();
        $Payments->Edit($customerNumber, $checkNumber, $Payment);
        break;

    case 702:
        // DELETE payments
        $Payments = new Payments();
        $customerNumber = $_GET['customerNumber'];
        $checkNumber = $_GET['checkNumber'];
        $Payments->Delete($customerNumber, $checkNumber);
        $Payments->List(); // retourne à la liste
        break;

    case 703:
        // DISPLAY payments
        $Payments = new Payments();
        $customerNumber = $_GET['customerNumber'];
        $checkNumber = $_GET['checkNumber'];
        $Payments->Display($customerNumber, $checkNumber);
        break;

    case 704:
        //SAVE
        $Payments = new Payments();
        $old_customerNumber = $_POST['old_customerNumber'];
        $old_checkNumber = $_POST['old_checkNumber'];
        $Payments->Save($old_customerNumber, $old_checkNumber);
        break;

    case 705:
        // ADD
        $DB = new DB();
        $customerNumber = '';
        $checkNumber = '';
        $Payment = [
            'customerNumber' => '',
            'checkNumber' => '',
            'paymentDate' => date('Y-m-d'), // current date
            'amount' => '',
        ];
        echo date('Y-m-d');
        $Payments = new Payments();
        $Payments->Edit($customerNumber, $checkNumber, $Payment);
        break;

    case 710:
        // Service Web (API) retourne la liste des payments
        // en format JSON
        $Payments = new Payments();
        $Payments->ListJson();
        break;

    default:
        Crash(400, 'operation invalide');
}

function HomePage()
{
    // affiche une page
    $Page = new WebPage();
    $Page->title = 'Bienvenue ! ';
    $Page->description = 'Bienvenue à veloelectrique.com !';

    // mettre un long contenu html dans une variable
    $Page->content = <<<HTML
  Ceci est la page d'acceuil
 HTML;

    // afficher la page
    $Page->Display();
    die(); // stop php code execution, same as exit()
}
