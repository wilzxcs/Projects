<?php
    include "_connect.php";
    if(isset($_POST['submit'])){
        $id = $_POST['team_id'];
        $match = $_POST['match_id'];
        $house = $_POST['house'];
        $sport = $_POST['sports'];


        $sql = "INSERT INTO match_teams (match_id, team_id) VALUES ('$match' ,'$id')";
        if($conn->query($sql) === TRUE){
            echo "Successful team insertion!";
            echo "<script> alert('Successfully inserted Match Result'); </script>";
        } else{
            echo "'Error on inserting team: $conn->error');";
            echo "<script> alert('Error on inserting match result: $conn->error'); </script>";
        }

        
        for($i=1; $i<20; $i++){
            $player = "player$i";
            if(empty($_POST[$player])){

            }else{
                $stud_id = $_POST[$player];
                $sql = "INSERT INTO match_lineup (match_team_id, player_id) VALUES($id, $stud_id);";
                if($conn->query($sql) === TRUE){
                    echo "Succesful lineup insertion!";
                }
                else{
                    echo "<script> alert('Error on inserting lineup: $conn->error'); </script>";
                }
            }
        }
    }
?>