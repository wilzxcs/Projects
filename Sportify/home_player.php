<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" href="resources/css/main-style.css" name="style">
        <link rel="stylesheet" href="resources/css/home-style.css" name="style">
        <script src="resources/main.js"> </script>
        <?php include 'resources/_connect.php'; ?>
        
        <?php 
            if(isset($_POST['id'])){
                $id = $_POST["id"];
                $name =  $_POST["name"];
            }

            $sql = "SELECT * FROM player WHERE id='$id' and name='$name'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            #if not found on db, redirect back to index with prompt
            
            if(empty($row)){
                header("Location: index.php?redirect=true");
                session_destroy();
                die();
            }
            $player_id = $id;
            $player_name = $name;

            
            #gets the house id of the player given player id
            $sql = "SELECT player_house.house_id FROM player join player_house on player.id = player_house.player_id";             
            $result = $conn->query($sql);
            $house_id = $result->fetch_assoc()['house_id'];
            
            
            #gets the name of the house given the house od
            $sql = "SELECT name from house where id='$house_id'";
            $result = $conn->query($sql);
            $house_name = $result->fetch_assoc() ['name'];
        ?>
    </head>
    <body onload="startTime()">
        <?php include 'resources/_time.php' ?>
        <?php include 'resources/_header.php' ?>
        <div id="posts">
            <div id="announcements">
                <h1> Announcements </h1>
                <div class='scrollable'>
                    <?php
                        # selects all announcements made by admins
                        $sql = "SELECT * FROM WALL WHERE post_type='admin' ORDER BY date DESC";
                        $result = $conn->query($sql);
                        foreach($result as $r){
                            $post = $r['post_desc'];
                            $date = $r['date'];
                            echo "<p><em><q> $post </q></em> <br> Posted on $date </p>";
                        }
                    ?>
                </div>
            </div>
            <div id="wall">
                <h1> Public Wall </h1>
                <div class='scrollable'>
                    <?php
                        # selects all announcements made by players
                        $sql = "SELECT * FROM WALL WHERE post_type='playe' ORDER BY date DESC";
                        $result = $conn->query($sql);
                        foreach($result as $r){
                            $post = $r['post_desc'];
                            $date = $r['date'];
                            $name = $r['username'];
                            echo <<< EOT
                                <p>
                                    <span>$post</span> <br>
                                    <span><small><small>$date</small></small></span><br>
                                    <span id='name'>- $name </span><br>
                                </p>
                            EOT;

                        }
                    ?>
                </div>
            </div>
            <div id="enter-post">
                <form action="resources/_submit-post.php" method="post" target="iframe_content">
                    <table>
                        <tr> 
                            <td> Post: </td>
                            <td> <textarea name='post_text' placeholder="Reminder to be kind in the post!"></textarea>
                        </tr>
                        <tr> 
                            <td> Enter display name: </td>
                            <td> <input type="text" name='displayname' placeholder='Any displayname will do'> </td>
                            <td> <input type='submit' name='submit_post' onclick="alert('Submitted! Refresh the page to see your post!')"> </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="side-container">
            <div id='house-info'>
                <img src="resources/images/<?php echo $house_name; ?>_logo.png" id="house_logo" alt="logo">
                <?php
                    $sql = "SELECT * FROM house WHERE name='$house_name'";
                    $result = $conn->query($sql);
                    $result = $result->fetch_assoc();
                    $id = $result['id']; 
                    $name = $result['name']; 
                    $desc = $result['description'];
                    
                    $sql = "SELECT SUM(team_points) from match_result where house_id=$house_id";
                    $result = $conn->query($sql)->fetch_assoc();
                    $pts = reset($result); 
                    echo <<< EOT
                        <table>
                            <tr>
                                <td> House ID: </td>
                                <td> $id</td>
                            </tr>
                            <tr>
                                <td> House Name: </td>
                                <td> $name</td>
                            </tr>
                            <tr>
                                <td> Current Points: </td>
                                <td> $pts </td>
                            </tr>
                            <tr>
                                <td> House Description: </td>
                                <td> $desc </td>
                            </tr> </table>
                        
                    EOT;
                ?>
            </div>
            

            <div id="player-info">
                <p> ID: <?php echo $player_id;?> </p>
                <p> Name: <?php echo $player_name; ?> </p> 
                <p> House: <?php echo $house_name; ?> </p>
                <p> My Sports: </p> 
                    <ul>
                        <?php
                            $sql = "SELECT player.id, sports.name Sport, sports.id s_id, match_.status Status, player.name Name, house.name House, 
                            match_lineup.match_team_id FROM match_ join match_teams join match_lineup join player join sports join player_house join house on 
                            match_.id=match_teams.match_id and match_lineup.match_team_id=match_teams.team_id and player.id=match_lineup.player_id
                            and sports.id=match_.sports_id and player.id=player_house.player_id and house.id=player_house.house_id where player.id='$player_id'";
                            $result = $conn->query($sql);
                            foreach($result as $r){
                                $sport = $r['Sport'];
                                $s_id = $r['s_id'];
                                echo "<li> <a href='sports.php?sports_id=$s_id'> &nbsp;" . $sport . " </a> </li>";
                            }
                        ?> 
                    </ul>
            </div>
        </div>
    </body>
    <iframe name='iframe_content'> </iframe>
</html>