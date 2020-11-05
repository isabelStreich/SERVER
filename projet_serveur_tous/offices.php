<?php

if (!defined('INDEX_LOADED')) {
    http_response_code(403);
    die('Acces direct a ce fichier est interdit');
}
/**
 * inclure des fichiers.
 */
//require_once 'db_mysqli.php';
require_once 'db_pdo.php';
require_once 'webpage.php';

class Offices
{
    public function __construct()
    {
    }

    public function List($filtre = '') // ?op=100.
    {
        $DB = new DB();
        if ($filtre == '') {
            $offices = $DB->table('offices');
        } else {
            $sql = 'SELECT * FROM offices WHERE officeCode='.$filter;
            $params = ['officeCode' => $filtre];
            $offices = $DB->querySelectParam($sql_str, $params);
        }
        /**
         * Show all OFFICES.
         */
        $Page = new WebPage();
        $Page->title = 'All offices';
        $Page->description = 'Page All office';

        $Page->content .= '<a href=index.php?op=101 class="btn btn-success"> Create new office </a>';
        $Page->content .= '<a href=index.php?op=0 class="btn btn-primary"> Back to main page </a>';
        $Page->content .= '<table class="table table-light">';
        $Page->content .= '<thead>';
        $Page->content .= '<tr>';
        $Page->content .= '<th scope="col"> Office Code </th>';
        $Page->content .= '<th scope="col"> City </th>';
        $Page->content .= '<th scope="col"> Phone </th>';
        $Page->content .= '<th scope="col"> AddressLine1 </th>';
        $Page->content .= '<th scope="col"> AddressLine2 </th>';
        $Page->content .= '<th scope="col"> State </th>';
        $Page->content .= '<th scope="col"> Country </th>';
        $Page->content .= '<th scope="col"> PostalCode </th>';
        $Page->content .= '<th scope="col"> Territory </th>';
        $Page->content .= '<th scope="col"> Date de création </th>';
        $Page->content .= '<th scope="col"> Date de modification </th>';
        $Page->content .= '<th scope="col"> Lire </th>';
        $Page->content .= '<th scope="col"> Modifier </th>';
        $Page->content .= '<th scope="col"> Supprimer </th>';
        $Page->content .= '</tr>';
        $Page->content .= '</thead>';
        //echo '<th scope="col"> <form action="index.php?op=101 method="POST"> </th>';
        //echo "<th scope='col'> <input type='text' name='officeCode' class='alert alert-info' placeholder='Find by office number'</th>";
        //echo '<th scope="col"> <button type="submit" name="submit" class="btn btn-info">find a office</th></button>';
        $Page->content .= '<th scope="col"></th>';
        $Page->content .= '</tr>';
        $Page->content .= '</thead>';

        foreach ($offices as $office) {
            $Page->content .= '<tr>';
            $Page->content .= '<td>'.$office['officeCode'].'</td>';
            $Page->content .= '<td>'.$office['city'].'</td>';
            $Page->content .= '<td>'.$office['phone'].'</td>';
            $Page->content .= '<td>'.$office['addressLine1'].'</td>';
            $Page->content .= '<td>'.$office['addressLine2'].'</td>';
            $Page->content .= '<td>'.$office['state'].'</td>';
            $Page->content .= '<td>'.$office['country'].'</td>';
            $Page->content .= '<td>'.$office['postalCode'].'</td>';
            $Page->content .= '<td>'.$office['territory'].'</td>';
            $Page->content .= '<td>'.date('Y-m-d H:i:s').'</td>';
            $Page->content .= '<td>'.date('Y-m-d H:i:s').'</td>';
            $Page->content .= '<td> <a href=index.php?op=102&officeCode='.$office['officeCode'].' class="glyphicon glyphicon-search">voir</a></td>';
            $Page->content .= '<td> <a href=index.php?op=103&officeCode='.$office['officeCode'].' class="glyphicon glyphicon-pencil">edit</a></td>';
            $Page->content .= '<td> <a href=index.php?op=104&officeCode='.$office['officeCode'].' class="glyphicon glyphicon-remove">effacer</a></td>';
            $Page->content .= '</tr>';
        }
        $Page->content .= '</table>';
        $Page->Display();
        die();
    }

    /**
     * ?op=101. Add new office.
     **/
    public function create() // ?op=101.
    {
        $DB = new DB();
        $Page = new WebPage();
        $Page->title = 'New office page';
        $messageError = 'MessageErreur';

        $Page->content = <<<HTML
<div class="jumbotron jumbotron-fluid">
<div class="container-fluid">
    <div class="card mt-5">
        <div class="card-header">
            <h2>Create new office</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-success">
            <form action='index.php?op=105' method="POST">
                <div class="form-group">
                    <label for="name">Office code</label>
                    <input type="text" name="officeCode" id="officeCode" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">City</label>
                    <input type="text" name="city" id="city" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">AddressLine1</label>
                    <input type="text" name="addressLine1" id="addressLine1" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">AddressLine2</label>
                    <input type="text" name="addressLine2" id="addressLine2" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">State</label>
                    <input type="text" name="state" id="state" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Country</label>
                    <input type="text" name="country" id="country" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Postal Code</label>
                    <input type="text" name="postalCode" id="postalCode" class="form-control">
                </div>
                <div class="form-group">
                    <label for="name">Territory</label>
                    <input type="text" name="territory" id="territory" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-info">Create new office</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

HTML;

        $Page->Display();
        die();
    }

    /*
        * ?op=102. Read one office selected
        */
    public function read_one($officeCode = '', $message = '') // ?op=102.
    {
        $DB = new DB();
        $Page = new WebPage();
        $message = '';
        $sql_str = 'SELECT * FROM offices WHERE officeCode="'.$officeCode.'"';
        $offices = $DB->querySelect($sql_str);
        $Page->content = <<<HTML
         <div class="alert alert-success">
             <!-- PAGE CONTENT HERE -->
             <h2> {$offices[0]['city']}'s office details :</h2>
         <a href=index.php?op=100 class="btn btn-primary"> Back to list </a>
                </div>
<table class="table table-success">
        <thead>
        <tr class="bg-success">
      <th scope="col"> Office Code </th>
        <th scope="col"> City </th>
        <th scope="col"> Phone </th>
        <th scope="col"> AddressLine1 </th>
        <th scope="col"> AddressLine2 </th>
        <th scope="col"> State </th>
        <th scope="col"> Country </th>
        <th scope="col"> PostalCode </th>
        <th scope="col"> Territory </th>
        </tr>
    </thead>
    <tbody>
    <tr>
    <td>{$offices[0]['officeCode']}</td>
    <td>{$offices[0]['city']}</td>
    <td>{$offices[0]['phone']}</td>
    <td>{$offices[0]['addressLine1']}</td>
    <td>{$offices[0]['addressLine2']}</td>
    <td>{$offices[0]['state']}</td>
    <td>{$offices[0]['country']}</td>
    <td>{$offices[0]['postalCode']}</td>
    <td>{$offices[0]['territory']}</td>

        </tr>
  </tbody>
</table>

HTML;
        $Page->Display();
        die();
    }

    /*
    * ?op=103. Update the office selected
    */

    public function edit($officeCode = '', $message = '') // ?op=103.
    {
        $DB = new DB();
        $message = '';
        $sql_str = 'SELECT * FROM offices WHERE officeCode="'.$officeCode.'"';
        $offices = $DB->querySelect($sql_str);
        $office = $offices[0];
        $Page = new WebPage();
        $officeCode = $city = $phone = $addressLine1 = $addressLine2 = $state = $country = $postalCode = $territory = '';
        $officeCodeError = $cityError = $phoneError = $addressLine1Error = $addressLine2Error = $stateError = $countryError = $postalCodeError = $territoryError = '';
        $Page->content = <<<HTML
        <div><h2>Warning! Edit office page: </h2> </div>
        <div class="alert alert-success">

        <!-- Show the office selected  -->
               </div>
         <a href=index.php?op=100&edit=1 class="btn btn-primary"> Back to list </a>
<table class="table table-warning">
       <thead class="bg-warning">
       <tr>
     <th scope="col"> Office Code </th>
       <th scope="col"> City </th>
       <th scope="col"> Phone </th>
       <th scope="col"> AddressLine1 </th>
       <th scope="col"> AddressLine2 </th>
       <th scope="col"> State </th>
       <th scope="col"> Country </th>
       <th scope="col"> PostalCode </th>
       <th scope="col"> Territory </th>
       </tr>
   </thead>
   <tbody>
   <tr>
   <td>{$offices[0]['officeCode']}</td>
   <td>{$offices[0]['city']}</td>
   <td>{$offices[0]['phone']}</td>
   <td>{$offices[0]['addressLine1']}</td>
   <td>{$offices[0]['addressLine2']}</td>
   <td>{$offices[0]['state']}</td>
   <td>{$offices[0]['country']}</td>
   <td>{$offices[0]['postalCode']}</td>
   <td>{$offices[0]['territory']}</td>

       </tr>
 </tbody>
</table>

       <!-- Show & UPDATE the office selected -->
       <div class="jumbotron jumbotron-fluid">
       <div class="container-fluid">

       <form action="index.php?op=105" method="POST">
        <div><h2>Edit this office: </h2> </div>

        <div class="form-group">
        <input class="form-control" type="text" placeholder="Office code" maxlength="100" value="{$office['officeCode']}" disabled>
        </div>

        <div class="form-group">
        <input type='hidden' name='officeCode' id='officeCode' value="{$office['officeCode']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='city' type="text" placeholder="city" maxlength="100" value="{$office['city']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='phone' type="text" placeholder="phone" maxlength="100" value="{$office['phone']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='addressLine1' type="text" placeholder="addressLine1" maxlength="100" value="{$office['addressLine1']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='addressLine2' type="text" placeholder="addressLine2" maxlength="100" value="{$office['addressLine2']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='state' type="text" placeholder="state" maxlength="100" value="{$office['state']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='country' type="text" placeholder="country" maxlength="100" value="{$office['country']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='postalCode' type="text" placeholder="postalCode" maxlength="100" value="{$office['postalCode']}">
        </div>

        <div class="form-group">
        <input class="form-control" name='territory' type="text" placeholder="territory" maxlength="100" value="{$office['territory']}">
        </div>

        <div class="form-group">
        <button type="submit" name="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
    </div>
       </div>

HTML;
        $Page->Display();
        die();
    }

    /*
    * ?op=104. Delete the office
    */
    public function delete($officeCode = '', $message = '') // ?op=104.
    {
        $DB = new DB();
        $Page = new WebPage();
        $message = '';
        if (isset($_SESSION['message'])):
?>
<div
    class="alert alert-<?=$_SESSION['msg_type']; ?>">
    <?php
    echo $_SESSION['message'];
        unset($_SESSION['message']); ?>
</div>
<?php endif;
        $DB->query('DELETE FROM offices WHERE officeCode="'.$officeCode.'"');
        if ($offices = true) {
            $_SESSION['message'] = '<div class="alert alert-danger">Record has been deleted</div>';
            $_SESSION['msg-type'] = 'Danger';
        } else {
            return false;
        }
        $this->List();
    }

    /**
     * ?op=105.
     **/
    public function save() // ?op=105.  Save the office UPDATED or CREATED
    {
        $DB = new DB();

        var_dump($_POST);
        if (((!empty($_POST))
        && (isset($_POST['submit']))
           && (isset($_POST['officeCode'])) && (isset($_POST['city']))
           && (isset($_POST['phone'])) && (isset($_POST['addressLine1']))
           && (isset($_POST['addressLine2'])) && (isset($_POST['state']))
           && (isset($_POST['country'])) && (isset($_POST['postalCode']))
           && (isset($_POST['territory'])))
           ) {
            $officeCode = ($_POST['officeCode']);
            $city = ($_POST['city']);
            $phone = ($_POST['phone']);
            $addressLine1 = ($_POST['addressLine1']);
            $addressLine2 = ($_POST['addressLine2']);
            $state = ($_POST['state']);
            $country = ($_POST['country']);
            $postalCode = ($_POST['postalCode']);
            $territory = ($_POST['territory']);
            $createdDate = date('Y-m-d H:i:s');

            if (!isset($_GET['edit'])) {
                $sql_str = "INSERT INTO offices (officeCode, city, phone,
                    addressLine1, addressLine2, state, country, postalCode, territory)
                       VALUES('$officeCode', '$city', '$phone', '$addressLine1',
                       '$addressLine2', '$state', '$country', '$postalCode', '$territory')";
            } else {
                $sql_str = "UPDATE offices SET city='".$city."', phone='".$phone."',
                     addressLine1='".$addressLine1."', addressLine2='".$addressLine2."',
                     state='".$state."', country='".$country."', postalCode='".$postalCode."',
                     territory='".$territory."'
                     WHERE officeCode='".$officeCode."'";
            }
            $DB->query($sql_str);
            $this->List();
        } else {
            echo 'Erreur UPDATE <br>';
            echo '<a href=index.php?op=103 class="btn btn-primary"> Back to list UPDATE </a>';
        }
    }

    /**
     *  ?op=110. ListJson().
     * */
    public function ListJson() //?op=110.
    {
        $DB = new DB();
        $offices = $DB->table('offices');
        $officesJson = json_encode($offices, JSON_PRETTY_PRINT);
        $content_type = 'Content-Type: application/json; charset=UTF-8';
        header($content_type);
        http_response_code(200);
        echo $officesJson; // la reponse sois des donnees
    }
}
