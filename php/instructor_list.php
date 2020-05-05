<?php
    require_once('config.php');
    require_once('session.php');
    include('header.php');
?>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Email Address</th>
        <th>Instrument(s)</th>
        <th>Charge Rate</th>
        <th>Register for Lessons</th>
    </tr>
<?php
    $instructors_sql = "select concat(`fname`, ' ', `lname`) as `teacher_name`, `email_address`, `user_id` as `teacher_id`, `charge_rate` from `users` where `is_teacher` = 1;";
    $instructors_result = $conn->query($instructors_sql);
    while ($row = $instructors_result->fetch_assoc()) {
?>
        <tr>
            <td><?php echo $row['teacher_name']; ?></td>
            <td><?php echo $row['email_address']; ?></td>
            <td>
<?php
    $instruments_sql = "select instruments.name as instrument_name from instruments natural join teaches_r inner join users on user_id = teacher_id where teacher_id = {$row['teacher_id']} order by instrument_name asc;";
    $instruments_result = $conn->query($instruments_sql);
    $instrument_row = $instruments_result->fetch_assoc();
    echo $instrument_row['instrument_name'];
    while ($instrument_row = $instruments_result->fetch_assoc()) {
        echo ", " . $instrument_row['instrument_name'];
    }
?>
            </td>
            <td>$<?php echo $row['charge_rate']; ?>/hr</td>
            <td>
<form action="./lesson_registration.php" method="post">
    <input type="hidden" name="teacher_id" value="<?php echo $row['teacher_id']; ?>"/>
    <input type="submit" value="Register"/>
</form>
            </td>
        </tr>
<?php
    }
?>