<?php

    $host = 'localhost';
    $name = 'parser';
    $user = 'root';
    $password = 'root';
    
try{

    $db = new PDO(
        "mysql:host=$host; dbname=$name", 
        $user, $password
    );

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}   catch(PDOException $e){
        
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>