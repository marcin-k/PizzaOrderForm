<?php
/**********************************************************************
 *  vieworder page displays several different pages based on the
 *  values passed, ($_POST or $_GET array)
 *
 * @author: Marcin Krzeminski
 *          R00117906
 *          marcin.krzeminski@mycit.ie
 *
/**********************************************************************/

//include all files used
include "inc_functionsForVieworder.php";
include "inc_StickyForm.php";
include "inc_database.php";

// list of variables below is used to determine where was the page called from
// $delete - indicate user was on vieworder page and clicked on delete button
// $update - indicate user was on vieworder page and clicked on update button
// $confirmDelete - indicate user was on deleting page and confirm deletion
// $validateUpdate - indicate the user was updating his order and it will process to validate it now
$delete = isItSet("delete");
$update = isItSet("update");
$confirmDelete = isItSet("confirmDelete");
$validateUpdate = isItSet("validateUpdate");

// checks the checkboxes in the form
$anchovies = isItSet("addAnchovies");
$pineapple = isItSet("addPineapple");
$pepperoni = isItSet("addPepperoni");
$olives = isItSet("addOlives");
$onion = isItSet("addOnion");
$peppers = isItSet("addPeppers");
$student = isItSet("student");

//$_GET['order_id'] determines that user was using url to find order
if(isset($_GET['order_id'])){
    $order_id=($_GET['order_id']);
    $result = orderRetrive($order_id);

//if order was not found "Incorrect order number!" message is displayed
    if(mysqli_num_rows($result)==0){
        echo "-------------------------------------------------------------------------</br>";


        echo "<h3>Incorrect order number!</br>Please check your order number and correct url</br>or 
                <a href=\"order.php\">Make a new order</a></h3>";
    }
    //if order was found the order details are displayed (displayOrder function call)
    else{
        $row = mysqli_fetch_assoc($result);
        $size = $row['size'];
        $firstName  = $row['firstname'];
        $lastName = $row['lastname'];
        $address = $row['address'];
        $emailAddress = $row['email'];
        $phoneNo = $row['phone'];
        $anchovies = $row['anchovies'];
        $pineapple = $row['pineapples'];
        $pepperoni = $row['pepperoni'];
        $olives = $row['olives'];
        $onion = $row['onions'];
        $peppers = $row['peppers'];
        $student = $row['student'];
        $createdDateTime = $row['createddatetime'];
        $customerName = $firstName." ".$lastName;

        displayOrder($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
            $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName,  $order_id, $createdDateTime);

        displayHiddenForm($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
            $peppers, $address, $emailAddress, $phoneNo, $student, $customerName,  $order_id, $createdDateTime);
    }

}
// validates values in the form when the form is being updated
// if no filed is blank performs update (updateQuery function)
else if($validateUpdate=='y'){
    $size = $_POST["pizzaSize"];
    $customerName = htmlspecialchars($_POST["customerName"]);
    $firstName  = strtok($customerName, ' ');
    $lastName = strtok(' ');
    $address = htmlspecialchars($_POST["address"]);
    $emailAddress = htmlspecialchars($_POST["emailAddress"]);
    $phoneNo = htmlspecialchars($_POST["phoneNo"]);
    $order_id = $_POST["order_id"];
    if(($firstName=="")or($lastName=="")or($address=="")or($emailAddress=="")or($phoneNo=="")or(!is_numeric($phoneNo))) {
        diplayStickyForm($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
            $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName, "update", $order_id, "validateUpdate");
    }
    else{
        updateQuery($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
                             $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName, $order_id);
        echo "<h3>Your order have been updated</h3></br>";
        echo "<a href=\"vieworder.php?order_id=$order_id\"><h4>See changes</h4></a>";
    }
}
// if user confirmed that order needs to be deleted deletion is performed (deleteOrder function)
else if($confirmDelete=='y'){
    $order_id = $_POST["order_id"];
    echo " <h3>Oderd ID # <em>".$order_id."</em></h3>";
    deleteOrder($order_id);
    echo "<h3>Your order have been deleted</h3></br>";
    echo "<a href=\"order.php\"><h4>Go Back</h4></a>";
}
// if user displayed intention to delete order below reassurance message is displayed allowing to proceed or go back
// Back - is implemented by submitting the form using $_GET method
else if($delete=='y'){
    $order_id = $_POST["order_id"];
    echo "-------------------------------------------------------------------------</br>";
    echo "<h3>To delete your order(#".$order_id.") click confirm button,</br>select back button to return to order summary</h3>";
    echo "-------------------------------------------------------------------------</br>";
    ?>
    <form  id="pizza-form" onSubmit="return validateInput();" name="theform" method="POST" action="vieworder.php">
        <input name="order_id" type="text" value="<?php echo $order_id ?>" hidden />
        <button type="submit" name="confirmDelete" value="y" >Confirm</button>
    </form>
    <form  id="pizza-form" onSubmit="return validateInput();" name="theform" method="GET" action="vieworder.php">
        <input name="order_id" type="text" value="<?php echo $order_id ?>" hidden />
        <button type="submit">Back</button>
    </form>
    <?php
}
// Update form, displays sticky form and populates it with values provided
else if($update=='y'){
    $size = $_POST["pizzaSize"];
    $customerName = htmlspecialchars($_POST["customerName"]);
    $firstName  = strtok($customerName, ' ');
    $lastName = strtok(' ');
    $address = htmlspecialchars($_POST["address"]);
    $emailAddress = htmlspecialchars($_POST["emailAddress"]);
    $phoneNo = htmlspecialchars($_POST["phoneNo"]);
    $order_id = $_POST["order_id"];

    diplayStickyForm($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
        $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName, "update", $order_id, "validateUpdate");

}
// Below is displayed if user just entered url without order id (no $_GET part)
// one filed form is displayed allowing user to enter order id
else if(!isset($_POST["pizzaSize"])){
    echo "<h3>To check order details enter order number in the filed below:</h3></br>";
    ?>
    <form  id="pizza-form" onSubmit="return validateInput();" name="theform" method="GET" action="vieworder.php">
        Order Number: <input name="order_id" type="text"  />
        <button type="submit">Find</button>
    </form>
    <?php
}
// This part of code is only executed if the new order was entered
// if statement validates form fields and if non of the fields were left blank
// inserts data into a database (else part of the statement)
else{
    $size = $_POST["pizzaSize"];
    $customerName = htmlspecialchars($_POST["customerName"]);
    $firstName  = strtok($customerName, ' ');
    $lastName = strtok(' ');
    $address = htmlspecialchars($_POST["address"]);
    $emailAddress = htmlspecialchars($_POST["emailAddress"]);
    $phoneNo = htmlspecialchars($_POST["phoneNo"]);
    if(($firstName=="")or($lastName=="")or($address=="")or($emailAddress=="")or($phoneNo=="")or(!is_numeric($phoneNo))){
        diplayStickyForm($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
            $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName, "", "","");
    }
    else{
        $order_id = insertData($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
            $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName);

        displayHiddenForm($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
            $peppers, $address, $emailAddress, $phoneNo, $student, $customerName,  $order_id);
    }

}

