<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" href="resources/css/main-style.css" name="style">
        <link rel="stylesheet" href="resources/css/home-user.css" name="style">
        <script src="resources/main.js"> </script>
        <script src="resources/jquery-3.5.1.min.js"> </script>
        <?php include 'resources/_connect.php'; ?>
        
        <?php 
            if(!isset($_POST['id'])){
                header("Location: index.php");
            }
            $id = $_POST["id"];
            $pass =  $_POST["pass"];
            $sql = "SELECT * FROM user WHERE id=$id and pass='$pass'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if($result->num_rows > 0){
                
            }else{
                header("Location: index.php?redirect=true");
                    session_destroy();
                    die();
            }
            
        ?>
    </head>
    <body>
        <?php include "resources/_header.php" ?>
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
            <div id="enter-post">     
                <form action="resources/_submit-announcement.php" method="post" target="iframe_content">
                    <table>
                        <tr> 
                            <td> Post: </td>
                            <td> <textarea name='post_text' placeholder="Post as Announcment"></textarea>
                        </tr>
                        <tr> 
                            <td> <input type='submit' name='submit_post' onclick="alert('Submitted! Refresh the page to see your post!')"> </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="insert_match" class="inserts">
            <h1>Add Match</h1>
            <?php
                $sql = "SELECT id FROM sportify2.match_ order by id desc limit 1; ";
                $new_id = $conn->query($sql)->fetch_assoc()['id'] + 1;
            ?>
            <form action="resources/_user-submit-match.php" method='post' target="i_match">
                <table>
                    <tr>
                        <td> <label for='id'> Match ID: </label> </td>
                        <td> <input type='text' name='id' value=<?= $new_id; ?>> </td>
                    </tr>
                    <tr>
                        <td> <label for='status'> Status: </label> </td>
                        <td> <select name='status'>
                                <option value="Upcoming"> Upcoming </option>
                                <option value="Ongoing"> Ongoing </option>
                                <option value="Done"> Done </option>
                            </select>
                        </td>
                    <tr>
                        <td> <label for='sports'> Sport: </label> </td>
                        <td> 
                            <?php
                                $sql = "SELECT * From sports";
                                $result = $conn->query($sql);
                            ?>
                            <select name='sports'>
                                <?php
                                    foreach($result as $row){
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        echo "<option value='$id'> $name </option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> <label for='date'> Date: </label></td>
                        <td> <input type='date' name="date"> </td>
                    </tr>
                    <tr>
                        <td> <label for='time'> Time: </label></td>
                        <td> <input type='time' name="time"> </td>
                    </tr>
                    <tr>
                        <td> <input type='submit' name='submit' value='Submit'> </td>
                    </tr>
                </table>
            </form>
            <iframe name='i_match'> </iframe>
        </div>
        
        <div id="insert_match_result" class="inserts">
            <h1> Add Match Result </h1>
            <form action="resources/_user-submit-match-result.php" method='post' target="i_match_result">
                <table>
                    <tr>
                        <td> <label for='id'> Match ID: </label> </td>
                        <td> <input type='text' name='id' placeholder='Input ID here'> </td>
                    </tr>
                    <tr>
                        <td> <label for='house'> House: </label> </td>
                        <td> 
                            <?php
                                $sql = "SELECT * From house";
                                $result = $conn->query($sql);
                            ?>
                            <select name='house'>
                                <?php
                                    foreach($result as $row){
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        echo "<option value='$id'> $name </option>";
                                    }
                                ?>
                            </select>
                        </td>
                    <tr>
                        <td> <label for='rank'> Rank: </label> </td>
                        <td> <input type='number' min="1" name='rank' max='4'> </td>
                    </tr>
                    <tr>
                        <td> <label for='points'> Points: </label></td>
                        <td> <input type='number' name='points' min="50" max="100" step="5"> </td>
                    </tr>
                    <tr>
                        <td> <input type='submit' name='submit' value='Submit'> </td>
                    </tr>
                </table>
            </form>
            <iframe name='i_match_result'> </iframe>
        </div>
        <div id='insert_team' class="inserts">
            <h1> Insert Team </h1>
            <form action="resources/_user-submit-team.php" method='post' target="i_team">
                <table>
                    <tr>
                        <td> <label for='team_id'> Team ID: </label> </td>
                        <?php
                            $sql = "SELECT count(*) from match_teams";
                            $result = $conn->query($sql)->fetch_assoc();
                            $size = reset($result)+1;
                        ?>
                        <td> <input type='text' name='team_id' placeholder='Input ID here' value='<?= $size ?>' disabled> </td>
                    </tr>
                    <tr>
                        <td> <label for='house'> House: </label> </td>
                        <td> 
                            <?php
                                $sql = "SELECT * From house";
                                $result = $conn->query($sql);
                            ?>
                            <select name='house'>
                                <?php
                                    foreach($result as $row){
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        echo "<option value='$id'> $name </option>";
                                    }
                                ?>
                            </select>
                        </td>
                    <tr>
                        <td> <label for='sports'> Sport: </label> </td>
                        <td> 
                            <?php
                                $sql = "SELECT * From sports";
                                $result = $conn->query($sql);
                            ?>
                            <select name='sports'>
                                <?php
                                    foreach($result as $row){
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        echo "<option value='$id'> $name </option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td> <label for='match_id'> Match ID: </label></td>
                        <td> <input type='text' name='match_id'> </td>
                    </tr>
                    <tr>
                        <td> <label for='player1'> Player 1 ID: </label></td>
                        <td> <input type='text' name='player1'> </td>
                    </tr>
                    <span id='clones'>
                        
                    </span>
                    <tr>
                        <td colspan=2>
                            <button onclick='add_clone()'> + </button> 
                            <input type='submit' name='submit' value='Submit'> 
                        </td>
                    </tr>
                </table>
            </form>
            <iframe name='i_team'> </iframe>
        </div>
        <div id="change_status" class='inserts'>
            <form action="resources/_user-change-status.php" method='post' target="i_change_status">
                <h1> Change Match Status </h1>
                <table>
                    <tr>
                        <td> ID </td>
                        <td> Status </td>
                        <td> Sport </td>
                        <td> Time </td>
                        <td> Date </td>
                    </tr>
                <?php
                    $sql = "SELECT match_.id m_id, match_.status, match_.sports_id s_id, time, date, name, level from match_ join sports on match_.sports_id=sports.id";
                    $result = $conn->query($sql);
                    foreach($result as $row){
                        $id = $row['m_id'];
                        $status = $row['status'];
                        $sport = $row['name'];
                        $date = $row['date'];
                        $time = $row['time'];

                        $A = "";
                        $B = "";
                        $C = "";
                        if($status == "Done"){ $A = "SELECTED"; }
                        if($status == "Ongoing"){ $B = "SELECTED"; }
                        if($status == "Upcoming"){ $C = "SELECTED"; }
                        echo <<< EOT
                            <tr>
                                <td> $id </td>
                                <td> <select name='match_$id'>
                                        <option value="Done" $A> Done </option>
                                        <option value="Ongoing" $B> Ongoing </option>
                                        <option value="Upcoming" $C> Upcoming </option>
                                    </select>
                                </td>
                                <td> $sport </td>
                                <td> $date </td>
                                <td> $time </td>
                            </tr>
                        EOT;
                    }
                ?>
                    <tr>
                        <td> <input type='submit' name='submit' value='Apply'> </td>
                    </tr>
                </table>
            </form>
            <iframe name='i_change_status'> </iframe>
        </div>
    </body>
</html>