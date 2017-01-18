<?php
/**********************************************************************
 *  File contains all functions used by vieworder file
 *  functionality of each function have been described in the line
 *  above the function
 *
 * @author: Marcin Krzeminski
 *          R00117906
 *          marcin.krzeminski@mycit.ie
 *
/**********************************************************************/

//inserts order into a database, creates order id and current time
function insertData($size, $anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers, $address, $emailAddress,
                    $phoneNo, $student, $firstName, $lastName){

    $order_id = uniqid();
    $createdDateTime =  date("Y-m-d h:i:s");
    //enters escape character \ if apostrophe enter in the last name
    $lastName=str_replace("'","\\'",$lastName);
    $price = getTotalPrice($size, $anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers, $student);
    

    $sqlAdd = "INSERT INTO `pizza`.`orders` (`order_id`, `student`, `firstname`,
    `lastname`, `email`, `address`, `phone`, `price`, `size`, `anchovies`,
    `pineapples`, `pepperoni`, `olives`, `onions`, `peppers`, `createddatetime`)
    VALUES ('$order_id', '$student','$firstName', '$lastName', '$emailAddress', '$address',
    '$phoneNo', '$price', '$size', '$anchovies', '$pineapple', '$pepperoni', '$olives',
    '$onion', '$peppers', '$createdDateTime')";
    exQuery($sqlAdd);
    $lastName=str_replace("\\'","'",$lastName);
    displayOrder($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
    $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName,  $order_id, $createdDateTime);
    return $order_id;
}
// total cost calculator determines size and base on the size adjust price of toppings
// value store in the database include 10% student discount if applicable
function getTotalPrice($size, $anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers, $student){
        if($size=='small'){
            $price = 6;
            $toppingPrice = 0.5;
        }
        elseif($size=='medium'){
            $price = 10;
            $toppingPrice = 1;
        }
        else{
            $price = 12;
            $toppingPrice = 1;
        }
        if($anchovies=='y'){
            $price += $toppingPrice;
        }
        if($pineapple=='y'){
            $price += $toppingPrice;
        }
        if($pepperoni=='y'){
            $price += $toppingPrice;
        }
        if($olives=='y'){
            $price += $toppingPrice;
        }
        if($onion=='y'){
            $price += $toppingPrice;
        }
        if($peppers=='y'){
            $price += $toppingPrice;
        }
        if($student=='y'){
            $price = $price*0.9;
        }
        return $price;
        }
// Function used to determine if the check box and hidden fields have been selected, all values in the form have been set to "y"
// returns "y" only if the filed have been selected and have a value of "y"
function isItSet($var){

    if(isset($_POST[$var])) {
        if(($_POST[$var])=='y') {
             return 'y';
        }
        else{
            return 'n';
        }
    }
    else{
        return 'n';
    }
}
//Prints order details
function displayOrder($size, $anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers, $address,
                      $emailAddress, $phoneNo, $student, $firstName, $lastName,  $order_id, $createdDateTime){

        echo " <h3>Oderd ID # <em>".$order_id."</em></h3>";
        echo "<strong>Ordered on: </strong> ".$createdDateTime."</br>";
        echo "<a href=\"http://localhost:777/ssd/vieworder.php?order_id=$order_id\">http://localhost:777/ssd/vieworder.php?order_id=$order_id</a></br>";
        echo "-------------------------------------------------------------------------</br>";
        echo " <h3>Order Details: </h3>";
        echo " <strong>Size: </strong>".$size."</br>";
        echo " <strong>Toppings: </strong>".getToppings($anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers)."</br>";

        echo "-------------------------------------------------------------------------</br>";
        echo " <h3>Customer Details: </h3>";
        echo " <strong>First name: </strong>".$firstName."</br>";
        echo " <strong>Last name: </strong>".$lastName."</br>";
        echo " <strong>Address: </strong>". $address."</br>";
        echo " <strong>Email address: </strong>".$emailAddress."</br>";
        echo " <strong>Phone number: </strong>".$phoneNo."</br>";
        if($student=='y'){
        echo " <em>10% Student Discount will apply to your order </em></br>";
        }
        echo "-------------------------------------------------------------------------</br>";
}
//Method used to create a String with all the toppings that have been selected
// all selected toppings have value of "Y"
function getToppings($anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers){

        $toppings="";
        if($anchovies=="y"){
        $toppings .= 'anchovies, ';
        }
        if($pineapple=='y'){
        $toppings .= "pineapple, ";
        }
        if($pepperoni=='y'){
        $toppings .= "pepperoni, ";
        }
        if($olives=='y'){
        $toppings .= "olives, ";
        }
        if($onion=='y'){
        $toppings .= "onion, ";
        }
        if($peppers=='y'){
        $toppings .= "peppers, ";
        }
        //deletes last space
        $toppings=substr($toppings,0,-2);
        return $toppings;
}
// Displays Delete and Update buttons and hidden fields in order to pass variables to itself w
function displayHiddenForm($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
    $peppers, $address, $emailAddress, $phoneNo, $student, $customerName,  $order_id){

    ?>
    <form  id="pizza-form" onSubmit="return validateInput();" name="theform" method="POST" action="vieworder.php">
        <input name="addAnchovies" type="text" value="<?php echo $anchovies ?>" hidden />
        <input name="addPineapple" type="text" value="<?php echo $pineapple ?>" hidden />
        <input name="addPepperoni" type="text" value="<?php echo $pepperoni ?>" hidden />
        <input name="addOlives" type="text" value="<?php echo $olives ?>" hidden />
        <input name="addOnion" type="text" value="<?php echo $onion ?>" hidden />
        <input name="addPeppers" type="text" value="<?php echo $peppers ?>" hidden />
        <input name="student" type="text" value="<?php echo $student ?>" hidden />
        <input name="pizzaSize" type="text" value="<?php echo $size ?>" hidden />
        <input name="customerName" type="text" value="<?php echo $customerName ?>" hidden />
        <input name="address" type="text" value="<?php echo $address ?>" hidden />
        <input name="emailAddress" type="text" value="<?php echo $emailAddress ?>" hidden />
        <input name="phoneNo" type="text" value="<?php echo $phoneNo ?>" hidden />
        <input name="order_id" type="text" value="<?php echo $order_id ?>" hidden />
        <button type="submit" name="delete" value="y" >Delete</button>
        <button type="submit" name="update" value="y" >Update</button>
    </form>
    <?php
}
?>