<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" href="resources/css/main-style.css" name="style">
        <link rel="stylesheet" href="resources/css/match-style.css" name="style">
        <script src="resources/main.js"> </script>
        <?php include 'resources/_connect.php'; ?>
        
        <?php 
            $id = $_GET["id"];
            $sql = "SELECT match_.id, concat(match_.date, ' ', match_.time) DateTime, sports.name Sport, match_.status Status, player.name Name, house.name House, match_lineup.match_team_id
			FROM match_ join match_teams join match_lineup join player join sports join player_house join house on 
            match_.id=match_teams.match_id and match_lineup.player_id=player.id and player.id=player_house.player_id and match_.sports_id=sports.id
            and house.id=player_house.house_id and match_teams.team_id=match_lineup.match_team_id where match_.id=$id";

            $result = $conn->query($sql);
            $teams = Array();
            foreach($result as $row){
                $sport = $row['Sport'];
                $status = $row['Status'];
                $date = $row['DateTime'];
                break;
            }
            
            foreach($result as $row){
                if(!in_array($row['match_team_id'], $teams))
                $teams[] = $row['match_team_id'];
            }
            $team_count = sizeof($teams);
        ?>
    </head>
    <body>
        <?= include "resources/_header.php"; ?>
        <div id='main'>
            <div id='basic'>
                <p> Sport: <?= $sport; ?></p>
                <p> Date: <?= $date; ?></p>
                <p> Status: <?= $status; ?></p>
            </div>
            <div id='houses' style="column-count: <?= $team_count?>">
                <?php
                    foreach($teams as $team_id){
                        $sql = "SELECT match_.id, sports.name Sport, match_.status Status, player.name Name, house.name House, match_lineup.match_team_id FROM match_ join match_teams join match_lineup join player join sports join player_house join house on 
                        match_.id=match_teams.match_id and match_lineup.match_team_id=match_teams.team_id and player.id=match_lineup.player_id
                        and sports.id=match_.sports_id and player.id=player_house.player_id and house.id=player_house.house_id where match_team_id=$team_id";
                        $names = $conn->query($sql);
                        foreach($names as $house){
                            $house = $house['House'];
                            break;
                        }
                        echo "<div class='house'> <p><b> House $house </b></p>";
                        foreach($names as $name){
                            echo $name['Name'] . "<br>";
                        }
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>