<!-- PHP Resource file: Connect to database -->
<?php
    # MySQL Connection
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $db = "sportify2";
    $conn = new mysqli($servername, "root", "", $db);
    if( $conn -> connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    function __execute__($sql){
        return $conn->query($sql);
    }
?>