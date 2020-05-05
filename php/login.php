<?php
    require_once('./config.php');
    session_start();
    $error = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $conn->real_escape_string($_POST['email']);
        $pword = $conn->real_escape_string($_POST['password']);
        
        $sql = "SELECT `user_id` FROM `users` WHERE `email_address` = '$email' and `password` = '$pword'";
        $result = $conn->query($sql);

        $row = $result->fetch_assoc();
        $count = $result->num_rows;

        if($count == 1) {
            $_SESSION['login_user'] = $email;
            header("location: index.php");
        } else {
            $error = "Your email address or password is invalid";
        }
    }
?>

<html>
   <head>
      <title>Login Page</title>      
   </head>
   <body>
        <form action = "" method = "post">
            <label>Email:</label>
            <input type = "text" name = "email"/>
            <label>Password:</label>
            <input type = "password" name = "password"/>
            <input type = "submit" value = "Submit"/>
        </form>
        <p>Don't have an account yet? Register <a href="./register.php">here</a></p>
        <?php echo $error; ?>
   </body>
</html>