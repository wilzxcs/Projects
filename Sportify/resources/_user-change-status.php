<?php
    include "_connect.php";
    if(isset($_POST["submit"])){
        $sql = "SELECT id from match_";
        $result = $conn->query($sql);
        foreach($result as $id){
            $id = $id['id'];
            $stat_name = "match_$id";
            echo $id;
            if(isset($_POST["$stat_name"])){
                $stat = $_POST[$stat_name];
                $sql_ = "UPDATE match_ set status='$stat' where id=$id";
                if($conn->query($sql_) === TRUE){
                    echo "Successful!";
                }else{
                    echo "Error on inserting data: $conn->error";
                }
            }
        }
    }
?>