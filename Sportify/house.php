<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" type="text/css" href="resources/css/main-style.css">
        <link rel="stylesheet" type="text/css" href="resources/css/house-style.css">
        <script src="script.js"></script>
        <?php
            include "resources/_connect.php";
        ?>
    </head>
    
    <body>
        <?php include "resources/_header.php"; ?>
        <?php
            $sql = "SELECT id FROM house";
            $result = $conn->query($sql);
            $path = "resources/data/logos/logo-";
            if(!isset($_GET['hid'])){
                echo "<div id='houses'>";
                foreach($result as $r){
                    $r = $r['id'];
                    echo <<< EOT
                        <a href="house.php?hid=$r"><img src="$path$r.png" alt="logo"></a>
                    EOT;
                }
                echo "</div>";
            }
            else{
                $h_id = $_GET['hid'];
                $sql = "SELECT * FROM player WHERE id in 
                            (SELECT player_id from player_house where house_id='$h_id')";
                $results = $conn->query($sql);

                $sql = "SELECT name from house where id='$h_id'";
                $house_name = $conn->query($sql)->fetch_assoc()['name'];
                echo "<div id='player_list'> <h1 id='h_list'>List of players</h1>";
                foreach($results as $result){
                    echo $result['name'];
                    echo "<br>";
                }
                echo "</div>";                
                echo <<< EOT
                <div id='top'>
                    <h1> Welcome to House $house_name</h1>
                    <h1> Games Won </h1>
                </div>
                EOT;
                $sql = "SELECT * FROM sports_winners where house_winner_id=$h_id";
                $results = $conn->query($sql);
                

                echo <<< EOT
                    <div id='house_logo_single'> 
                        <img style='width:250px; height:250px;' src='resources/data/logos/logo-$h_id.png'>
                    </div>
                EOT;
                echo "<table><tr><td>Sport</td><td>Points Won</td></tr>";
                    $sql = "SELECT * from match_ join match_result join sports on match_.id = match_result.match_id and match_.sports_id=sports.id where status='Done' and house_id=$h_id;";
                    $result = $conn->query($sql)->fetch_assoc();
                    $name = $result['name'];
                    $points = $result['team_points']; 
                    echo <<< EOT
                        <tr>
                            <td> $name </td>
                            <td> $points </td>
                        </tr>
                    EOT;
                echo "</table>"; 
            }
        ?>
        
    </body>
</html>