<?php
    include "_connect.php";
    if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $house = $_POST['house'];
        $rank = $_POST['rank'];
        $points = $_POST['points'];

        switch($rank){
            case 1: $rank = "1st"; break;
            case 2: $rank = "2nd"; break;
            case 3: $rank = "3rd"; break;
            case 4: $rank = "4th"; break;
            case 5: $rank = "5th"; break;
        }
        
        $sql = "INSERT INTO match_result (match_id, house_id, team_rank, team_points) VALUES ('$id', '$house', '$rank', '$points')";
        if($conn->query($sql) === TRUE){
            echo "<script> alert('Successfully inserted Match Result'); </script>";
        } else{
            echo "'Error on inserting match result: $conn->error');";
            echo "<script> alert('Error on inserting match result: $conn->error'); </script>";
        }
    }
?>