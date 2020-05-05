<?php
    require_once('config.php');
    require_once('session.php');
?>
<div>
    <span>Welcome, <?php echo $login_session['fname'] . ' ' . $login_session['lname'];?></span>
<?php
    if ($login_session['is_student'] == 1) {
?>
    <a href="./instructor_list.php">View Instructors</a>
<?php
    }
?>

    <a href="./lesson_schedule.php">My Lesson Schedule</a>
    <a href="./logout.php">Log Out</a>
</div>