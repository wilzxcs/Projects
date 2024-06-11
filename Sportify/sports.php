<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" type="text/css" href="resources/css/main-style.css">
        <link rel="stylesheet" type="text/css" href="resources/css/sports-style.css">
        <script src="resources/main.js"> </script>
        <?php include "resources/_connect.php"; ?>
    </head>
    <body onload="startTime()">
        <?php include "resources/_header.php" ?>
        <?php include "resources/_time.php" ?>
        <?php

        

            $sport_id = $_GET['sports_id'];
            $sql = "SELECT * FROM sports WHERE id='$sport_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $id = $row['id']; $name = $row['name']; $level = $row['level'];
            
            echo <<< EOT
                <table id='sport-info'>
                    <tr>
                        <td> ID: </td>
                        <td> $id </td>
                        <td class='spacer'> </td>
                        <td> Name: </td>
                        <td> $name </td>
                        <td class='spacer'> </td>
                        <td> Level: </td>
                        <td> $level </td>
                        <td class='spacer'> </td>

                        <td class='spacer'> </td>
                    </tr>
                </table>
            EOT;
        ?>
        <div id='all-sports'>
            <ul>
            <?php
                $sql = "SELECT * FROM sports";
                $results = $conn->query($sql);
                foreach($results as $r){
                    $s_id = $r['id'];
                    $s_name = $r['name'];
                    echo "<li> <a href='sports.php?sports_id=$s_id'> $s_name </a></li>";
                }
            ?>
            </ul>
        </div>
        <div id="schedules">
            <?php
                $sql = "SELECT * from match_ where sports_id=$sport_id";
                $result = $conn->query($sql);
                echo "<h3> Displaying all Matches in the <em>$name</em> Sport </h3>";
                echo "<table>";

                echo <<< EOT
                    <tr>
                        <td>Date</td>
                        <td>Time</td>
                        <td>Status</td>
                        <td>Remarks</td>
                    </tr>
                EOT;
                foreach($result as $row){
                    $m_id = $row['id'];
                    $status = $row['status'];
                    $time = $row['time'];
                    $date = $row['date'];
                    echo <<< EOT
                            <tr> 
                                <td> $date </td> 
                                <td> $time </td> 
                                <td> $status </td> 
                                <td> <a href=match.php?id=$m_id> Check </a> </td>
                            </tr>
                    EOT;
                }
                echo "</table>";
            ?>
        </div>
    </body>
</html>