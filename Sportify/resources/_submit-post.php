
<?php
    include "_connect.php";
    if(isset($_POST['submit_post'])){
        $text = $_POST['post_text'];
        $name = $_POST['displayname'];
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO wall (username, post_desc, date, post_type) VALUES ('$name', '$text', '$now', 'playe')";
        if($conn->query($sql) === TRUE){
            echo "Successful!";
        } else{
            echo "Error on inserting data: " . $conn->error;
        }
    }
?>