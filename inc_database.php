<?php
/**********************************************************************
 *  database.php contains 4 funcions:
 *      - exQuery that establish database connection and execute passed
 *              query
 *      - updateQuery updates exsting order with the arguments provided
 *      - deleteOrder delets order with id provided
 *      - retrieveOrder returns
 *
 * @author: Marcin Krzeminski
 *          R00117906
 *          marcin.krzeminski@mycit.ie
 *
/**********************************************************************/
function exQuery($query){
    // Create connection
    $conn = mysqli_connect('localhost', 'root', '', 'pizza');
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $result = mysqli_query($conn, $query);
    if((!$result)){
        echo "<h2>SQL Error </h2></br>";
    }
    return $result;
    mysqli_close($conn);
}
/****************************** Update *******************************************************/
function updateQuery($size, $anchovies, $pineapple, $pepperoni, $olives, $onion,
                     $peppers, $address, $emailAddress, $phoneNo, $student, $firstName, $lastName, $order_id){

        $price = getTotalPrice($size, $anchovies, $pineapple, $pepperoni, $olives, $onion, $peppers, $student);
        $lastName=str_replace("'","\\'",$lastName);
        $sqlUpdate = "UPDATE `pizza`.`orders` SET `student`='$student', `firstname`='$firstName', 
          `lastname`='$lastName', `email`='$emailAddress', `address`='$address', `phone`='$phoneNo', `price`='$price', 
          `size`='$size', `anchovies`='$anchovies', `pineapples`='$pineapple', `pepperoni`='$peppers',
           `olives`='$olives', `onions`='$onion', `peppers`='$peppers' WHERE `order_id`='$order_id'";
        exQuery($sqlUpdate);
}
/******************************* Delete ******************************************************/
function deleteOrder($order_id){
    $sqlDelete = "DELETE FROM `pizza`.`orders` WHERE `order_id`='$order_id'";
    exQuery($sqlDelete);
}
/******************************** Select *****************************************************/
function orderRetrive($order_id){
    $sqlOrderRetrieve = "SELECT * FROM pizza.orders where order_id='$order_id'";
    $result = exQuery($sqlOrderRetrieve);
    return $result;
}

?>
