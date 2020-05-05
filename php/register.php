<?php
require_once('./config.php');
$message = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitted'])) {
    $data_missing = array();
    $password_verified = false;
    $unique_email = false;
    
    if (empty($_POST['first_name'])) {
        $data_missing[] = 'First Name';
    } else {
        $fname = trim($_POST['first_name']);
    }
    if (empty($_POST['last_name'])) {
        $data_missing[] = 'Last Name';
    } else {
        $lname = trim($_POST['last_name']);
    }
    if (empty($_POST['email'])) {
        $data_missing[] = 'Email Address';
    } else {
        $email = trim($_POST['email']);
        $sql = "select `user_id` from `users` where `email_address` = '$email';";
        $result = $conn->query($sql);
        if ($result) {
            $unique_email = true;
        } else {
            $error = "That email address is already registered to an account.";
        }
    }
    if (empty($_POST['password'])) {
        $data_missing[] = 'Password';
    } else if (empty($_POST['verify_password'])) {
        $data_missing[] = 'Password Verification';
    } else {
        $pword = trim($_POST['password']);
        $pword2 = trim($_POST['verify_password']);
        if ($pword == $pword2) {
            $password_verified = true;
        } else {
            $error = $error." Passwords must be the same.";
        }
    }
    if (empty($_POST['user_type'])) {
        $data_missing[] = 'User Type';
    } else {
        $user_type = $_POST['user_type'];
        if ($user_type == 'teacher') {
            $is_teacher = 1;
            $is_student = 0;
            
            if (empty($_POST['instruments'])) {
                $data_missing[] = 'instrument';
            } else {
                $instruments = $_POST['instruments'];
                $num_instruments = count($instruments);
            }
            
            if (!empty($_POST['charge_rate'])) {
                $rate = $_POST['charge_rate'];
            } else {
                $rate = "NULL";
            }
        } else {
            $is_teacher = 0;
            $is_student = 1;
            $rate = "NULL";
        }
    }

    if(empty($data_missing) && $password_verified && $unique_email) {
        $user_query = "INSERT INTO `users` (`fname`,`lname`,`email_address`,`password`,`is_teacher`,`is_student`,`charge_rate`) VALUES ('$fname','$lname','$email','$pword',$is_teacher,$is_student,$rate);";

        $conn->query($user_query);
        if ($conn->affected_rows == 1) {
            echo 'User created';
            $teacher_id = $conn->insert_id;
            for ($i = 0; $i < $num_instruments; $i++) {
                $instrument_query = "insert into `teaches_r` (`teacher_id`, `instrument_id`) values ({$teacher_id}, {$instruments[$i]});";
                $instrument_result = $conn->query($instrument_query);
                if ($conn->affected_rows != 1) {
                    die("Failed to add instrument");
                }
            }

            $_SESSION['login_user'] = $email;
            header("location: index.php");
        } else {
            echo 'Error occurred<br/>';
            echo $conn->error;
        }
    } else {
        echo $error;
    }
}
?>

<form action="" method="POST">
    <input type="text" name="first_name" placeholder="First Name"/>
    <input type="text" name="last_name" placeholder="Last Name"/>
    <input type="text" name="email" placeholder="Email Address"/>
    <input type="password" name="password" placeholder="Password"/>
    <input type="password" name="verify_password" placeholder="Verify Password"/>
    <p>Are you here to teach or to learn?</p>
    <input type="radio" name="user_type" value="teacher"/>
    <label>Teach</label>
    <input type="radio" name="user_type" value="student"/>
    <label>Learn</label>
    <p>For teachers:</p>
    <label>What do you teach?</label><br>
<?php
    $instruments_sql = "select * from instruments;";
    $instruments_result = $conn->query($instruments_sql);
    while ($instrument_row = $instruments_result->fetch_assoc()) {
?>
    <input type="checkbox" name="instruments[]" value="<?php echo $instrument_row['instrument_id'];?>" />
    <label><?php echo $instrument_row['name']; ?></label>
    <br>
<?php
    }
?>

    <br>
    <label>What do you charge per hour? $</label>
    <input type="number" min="0.01" step="0.01" max="200" name="charge_rate"/>
    <br>
    <input type="submit" name="submitted"/>
</form>
<p>Already have an account? Login <a href="./login.php">here</a></p>