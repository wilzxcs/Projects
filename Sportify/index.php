
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Sportify Homepage </title>
        <link rel="stylesheet" type="text/css" href="resources/css/main-style.css">
        <link rel="stylesheet" type="text/css" href="resources/css/index-style.css">  
        <?php include 'resources/_connect.php'; ?>
        <?php
            if(isset($_GET['redirect'])){
                echo "<script> alert('No User Account Exists! Check your ID or password.') </script>";
            }
        ?>
    </head>
    <body>
        <?php include "resources/_header.php" ?>
        <div id="title">
            <p> Welcome to the UPVTC Sportsfest 2021! Login as a player or as a sports committee member (user)</p>
            <p><small>
                This version of Sportify is V.1.5.0
            </small></p>
        </div>

        <div>
            <form action="home_player.php" method="post">
                <b> Player Login </b>
                <table>
                    <tr>
                        <td> <label for="name"> Name: </label> </td>
                        <td> <input type="text" id="name" name="name" placeholder="Your Name Here" required> </td>
                    </tr>
                    <tr>
                        <td> <label for="id"> ID: </label> </td>
                        <td> <input type="text" id="id" name="id" placeholder="Student ID" required> </td>
                    </tr>
                    <tr> <td> <input type="submit" name="submit" value="Log-in" id="submit"> </td> </tr>
                    <tr> <td><br> <a onclick="alert('Contact the Administrator for your password')"> Forgot Password? </a> </td> </tr>
                </table>
            </form>
        </div>
        <div>
            <form action="home_user.php" method="post">
                <b> SC Login </b>
                <table>
                    <tr>
                        <td> <label for="id"> ID: </label> </td>
                        <td> <input type="text" id="id" name="id" placeholder="Your ID Here" required> </td>
                    </tr>
                    <tr>
                        <td> <label for="pass_ID"> Password: </label> </td>
                        <td> <input type="text" id="pass" name="pass" placeholder="Your Password Here" required> </td>
                    </tr>
                    <tr> <td> <input type="submit" name="submit" value="Log-in" id="submit"> </td> </tr>
                    <tr> <td><br> <a onclick="alert('Contact the Administrator for your password')"> Forgot Password? </a> </td> </tr>
                </table>
            </form>
        </div>
    </body>
</html>