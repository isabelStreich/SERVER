<?php

if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Acces direct a ce fichier est interdit');
}

//require_once 'db_mysqli.php';
require_once 'db_pdo.php';
require_once 'webpage.php';

class employees
{
    public function __construct()
    {
    }

    public function list($employeeNumber = '')
    {
        $DB = new DB();
        if ($employeeNumber == '') {
            $employees = $DB->table('employees');
        } else {
            $sql_str = 'SELECT * FROM employees WHERE employeeNumber = :employeeNumber';
            $params = ['employeeNumber' => $employeeNumber];
            $employees = $DB->querySelectParam($sql_str, $params);
        }

        $DB->disconnect();

        $page = new WebPage();
        $page->title = 'employees dans la bd';
        $page->description = 'afficher une liste employees';
        $page->content = '';

        $page->content .= '<style>';
        $page->content .= 'th,td{border:1px solid black;}';
        $page->content .= '</style>';
        $page->content .= '<br>';
        $page->content .= '<form action="index.php?op=300" method="POST">';
        $page->content .= 'rechercher par no.employee <input type ="text" name="employeeNumber" >';

        $page->content .= '<input type="submit" value=Rechercher">';
        $page->content .= '</form>';
        $page->content .= '<br>';
        $page->content .= '<table>';
        $page->content .= '<tr>';
        $page->content .= '<th>employeeNumber</th>';
        $page->content .= '<th>lastName</th>';
        $page->content .= '<th>firstName</th>';
        $page->content .= '<th>extension</th>';
        $page->content .= '<th>email</th>';
        $page->content .= '<th>officeCode</th>';
        $page->content .= '<th>reportsTo</th>';
        $page->content .= '<th>jobTitle</th>';
        $page->content .= '<th>operation</th>';
        $page->content .= '</tr>';

        foreach ($employees as $un_employee) {
            $page->content .= '<tr>';
            $page->content .= '<td><a href="index.php?op=303&employeeNumber='.$un_employee['employeeNumber'].'">'.$un_employee['employeeNumber'].'</a></td>';
            $page->content .= '<td>'.$un_employee['lastName'].'</td>';
            $page->content .= '<td>'.$un_employee['firstName'].'</td>';
            $page->content .= '<td>'.$un_employee['extension'].'</td>';
            $page->content .= '<td>'.$un_employee['email'].'</td>';
            $page->content .= '<td>'.$un_employee['officeCode'].'</td>';
            $page->content .= '<td>'.$un_employee['reportsTo'].'</td>';
            $page->content .= '<td>'.$un_employee['jobTitle'].'</td>';
            $page->content .= '<td><a class="far fa-edit" style="font-size:18px" href="index.php?op=301&employeeNumber='.$un_employee['employeeNumber'].'">edit</a> | <a href="index.php?op=302">delete</a></td>';

            $page->content .= '</tr>';
        }
        $page->content .= '</table>';
        $page->content .= '<br>';
        $page->content .= '<form action="index.php?op=305" method="POST">';
        $page->content .= '<input type = "submit" value="Ajouter">';
        $page->content .= '</form>';
        $page->content .= '<br>';
        $page->display();
    }

    public function add()
    {
        $DB = new DB();

        $employees = $DB->table('employees');

        $page = new WebPage();
        $page->title = 'ajouter un employee dans la bd';
        $page->description = 'ajout employees';
        $page->content = '';
        $page->content .= $page->content .= '<form action="index.php?op=304&employeeNumber=-1" method="POST">';
        $page->content .= ' <label for="numberEMploye">ajoutez numero employee</label>';
        $page->content .= '<input class="form-control" type="number" name="employeeNumber" value="-1" disabled maxlength="11" required>';

        $page->content .= ' <label for="lastName">ajoutez lastName</label>';
        $page->content .= '<input class="form-control" type="text" name="lastName"  maxlength="50" required>';

        $page->content .= ' <label for="firstName">ajoutez firstName</label>';
        $page->content .= '<input class="form-control" type="text" name="firstName"  maxlength="50" required>';

        $page->content .= ' <label for="extension">ajoutez extension</label>';
        $page->content .= '<input class="form-control" type="text" name="extension" required maxlength="10" >';

        $page->content .= ' <label for="email">ajoutez email Employee</label>';
        $page->content .= '<input class="form-control" type="email" name="email"   maxlength="100" required >';

        $page->content .= ' <label for="officeCode">ajoutez officeCode</label>';
        $page->content .= '<input class="form-control" type="number" name="officeCode"  maxlength="50" required >';

        $page->content .= ' <label for="reportsTo">ajoutez reportsTo</label>';
        $page->content .= '<input class="form-control" type="text" name="reportsTo"   maxlength="11" >';

        $page->content .= ' <label for="jobTitle">ajoutez jobTitle</label>';
        $page->content .= '<input class="form-control" type="text" name="jobTitle"s maxlength="11" >';
        $page->content .= '<input type ="submit" value="save">';
        $page->content .= '</form>';

        // $page->content .= '<form action="index.php?op=304&employeeNumber=0 method="POST">';

        //$page->content .= '</form>';
        $page->display();
    }

    public function edit($employeeNumber = '')
    {
        $DB = new DB();

        $sql_str = 'SELECT * FROM employees WHERE employeeNumber = :employeeNumber';
        $params = ['employeeNumber' => $employeeNumber];
        $employees = $DB->querySelectParam($sql_str, $params);
        $user_info = $employees[0];
        // var_dump($user_info);

        $page = new WebPage();
        $page->title = 'employees dans la bd';
        $page->description = 'editer un employee';
        $page->content = '';
        $page->content .= '<form action="index.php?op=304&employeeNumber='.$employeeNumber.'" method="post" >';

        $page->content .= '<input class="form-control" type="hidden" name="employeeNumber" value="'.$user_info['employeeNumber'].'"  maxlength="50" required>';
        $page->content .= ' <label for="numberEMploye">modifie lastName</label>';
        $page->content .= '<input class="form-control" type="text" name="lastName" value="'.$user_info['lastName'].'"  maxlength="50" required>';
        $page->content .= ' <label for="email">modifier email Employee</label>';
        $page->content .= '<input class="form-control" type="email" name="email" value="'.$user_info['email'].'"  maxlength="100" required >';
        $page->content .= '<input type ="submit" value="save employee">';
        $page->content .= '</form>';

        $page->content .= '<form action="index.php?op=302" method="post" >';
        $page->content .= '<input type ="submit" value="delete employee">';
        $page->content .= '</form>';

        $page->content .= '<form action="index.php?op=300" method="post" >';
        $page->content .= '<input type ="submit" value="cancel">';
        $page->content .= '</form>';
        $page->display();
    }

    public function delete($employeeNumber = '')
    {
        $DB = new DB();

        $sql_str = 'SELECT * FROM employees WHERE employeeNumber = :employeeNumber';
        $params = ['employeeNumber' => $employeeNumber];
        $employees = $DB->querySelectParam($sql_str, $params);
        //$user_info = $employees[0];

        if (isset($_GET['employeeNumber'])) {
            $sql_delete = 'DELETE FROM employees WHERE employeeNumber = :employeeNumber';
            $employees = $DB->querySelectParam($sql_delete, $params);
            $this->list($_GET['employeeNumber']);
        }
    }

    public function Display($employeeNumber = '')
    {
        $DB = new DB();

        $sql_str = 'SELECT * FROM employees WHERE employeeNumber = :employeeNumber';
        $params = ['employeeNumber' => $employeeNumber];
        $employees = $DB->querySelectParam($sql_str, $params);

        $DB->disconnect();

        $page = new WebPage();
        $page->title = 'information d\'un employee dans la liste employees';
        $page->description = 'afficher une liste employees';
        $page->content = '';

        $page->content .= '<style>';
        $page->content .= 'th,td{border:1px solid black;}';
        $page->content .= '</style>';
        $page->content .= '<br>';
        $page->content .= '<table>';
        $page->content .= '<tr>';
        $page->content .= '<th>employeeNumber</th>';
        $page->content .= '<th>lastName</th>';
        $page->content .= '<th>firstName</th>';
        $page->content .= '<th>extension</th>';
        $page->content .= '<th>email</th>';
        $page->content .= '<th>officeCode</th>';
        $page->content .= '<th>reportsTo</th>';
        $page->content .= '<th>jobTitle</th>';
        $page->content .= '<th>operation</th>';
        $page->content .= '</tr>';

        foreach ($employees as $un_employee) {
            $page->content .= '<tr>';
            $page->content .= '<td><a href="index.php?op=303&employeeNumber='.$un_employee['employeeNumber'].'">'.$un_employee['employeeNumber'].'</a></td>';
            $page->content .= '<td>'.$un_employee['lastName'].'</td>';
            $page->content .= '<td>'.$un_employee['firstName'].'</td>';
            $page->content .= '<td>'.$un_employee['extension'].'</td>';
            $page->content .= '<td>'.$un_employee['email'].'</td>';
            $page->content .= '<td>'.$un_employee['officeCode'].'</td>';
            $page->content .= '<td>'.$un_employee['reportsTo'].'</td>';
            $page->content .= '<td>'.$un_employee['jobTitle'].'</td>';
            $page->content .= '<td><a href="index.php?op=301&employeeNumber='.$un_employee['employeeNumber'].'">edit</a> | <a href="index.php?op=302&employeeNumber='.$un_employee['employeeNumber'].'">delete</a></td>';

            $page->content .= '</tr>';
        }
        $page->content .= '</table>';
        $page->content .= '<br>';
        $page->content .= '<form action="index.php?op=305" method="POST">';
        $page->content .= '<input type = "submit" value="Ajouter">';
        $page->content .= '</form>';
        $page->content .= '<br>';
        $page->display();
    }

    public function save($employeeNumber = '')
    {
        $DB = new DB();
        $erreur_message = '';
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        // A FAIRE VERIFIER LES DONNEES
        // verification lastName
        //var_dump($_POST);
        if (!isset($_POST['lastName'])) {
            $erreur_message .= 'le lastName est requis';
        } elseif (strlen($_POST['lastName']) > 50) {
            $erreur_message .= 'le lastName est trop long,max 50 caracteres';
        }

        // verification email
        if (!isset($_POST['email']) or $_POST['email'] == '') {
            $erreur_message .= 'le email est requis';
        } elseif (strlen($_POST['email']) > 100) {
            $erreur_message .= 'le email est trop long,max 100 caracteres';
        }

        if ($erreur_message == '') {
            if ($_GET['employeeNumber'] == -1) {
                $sql_insert = 'INSERT INTO employees (employeeNumber,lastName, firstName,extension,email,officeCode,reportsTo,jobTitle) VALUES ($employeeNumber,"'.$_POST['lastName'].'","'.$_POST['firstName'].'","'.$_POST['extension'].'","'.$_POST['email'].'","'.$_POST['officeCode'].'",'.$_POST['reportsTo'].',"'.$_POST['jobTitle'].'")';
                var_dump($sql_insert);
                $employees = $DB->querySelect($sql_insert);
                $this->list($_POST['employeeNumber']);
            } else {
                $sql_str = "UPDATE employees SET lastName ='$lastName',email='$email' WHERE employeeNumber='$employeeNumber'";
                $employees = $DB->querySelect($sql_str);
                $this->list($_POST['employeeNumber']);
            }
        } else {
            echo $erreur_message;
            $this->add();
        }
    }

    public function listJson()
    {
        //svce web(API) retourne la list des employees en format Json
        $DB = new DB();
        $employees = $DB->table('employees');

        $employeesJson = json_encode($employees, JSON_PRETTY_PRINT);
        $content_type = 'Content-Type: application/json; charset=UTF-8';
        header($content_type);

        http_response_code(200);
        echo $employeesJson; //la rponse est donnees seulement
    }
}
