<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" type="text/css" href="resources/css/main-style.css">
        <link rel="stylesheet" type="text/css" href="resources/css/ranking-style.css">
        <script src="script.js"></script>
        <?php
            include "resources/_connect.php"; 
        ?>
    </head>
    
    <body>
        <?php include "resources/_header.php" ?>
        <?php
            $sql = "SELECT name, house_id, team_points from match_result join house on match_result.house_id=house.id group by house_id order by team_points desc;";
            $result = $conn->query($sql); 
            $top_houses_id = Array();
            $top_houses_id[] = $result->fetch_assoc()['house_id'];
            $top_houses_id[] = $result->fetch_assoc()['house_id'];
            $top_houses_id[] = $result->fetch_assoc()['house_id'];
            $path = "resources/data/logos/logo-";
        ?>

        <div>
            <div id='rank-2'><img src="<?php echo $path . $top_houses_id[1]?>.png" alt="logo"> <p>2nd ♕♕</p></div>
            <div id='rank-1'><img src="<?php echo $path . $top_houses_id[0]?>.png" alt="logo"> <p>1st ♕♕♕</p></div>
            <div id='rank-3'><img src="<?php echo $path . $top_houses_id[2]?>.png" alt="logo"> <p>3rd ♕</p></div>
        </div>
        <?php
            $result = $conn->query($sql);
            $rank = Array('1st', '2nd', '3rd', '4th', '5th');
            echo "<table>";
            echo <<< EOT
                    <tr>
                        <td> <b>Ranking</b> </td>
                        <td> <b>House</b> </td>
                        <td> <b>Total Points</b></td>
                    </tr>
                EOT;
                for($i=0;$i < 5; $i++){
                reset($result);
                $row = $result->fetch_assoc();
                $house = $row['name'];
                $pts = $row['team_points'];
                echo <<< EOT
                    <tr> 
                        <td> $rank[$i]: </td>
                        <td> $house</td>
                        <td> - $pts pts</td>
                    </tr>
                EOT;
            }   
            echo "</table>";
        ?>
        
    </body>
</html>