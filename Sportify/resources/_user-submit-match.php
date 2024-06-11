<?php
    include "_connect.php";
    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $status = $_POST['status'];
        $sport = $_POST['sports'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        
        $sql = "INSERT INTO match_ (id, status, sports_id, time, date) VALUES ('$id', '$status', '$sport', '$date', '$time')";
        if($conn->query($sql) === TRUE){
            echo "<script> alert('Successfully inserted Match'); </script>";
        } else{
            echo "'Error on inserting match: $conn->error');";
            echo "<script> alert('Error on inserting match result: $conn->error'); </script>";
        }
    }
?> 