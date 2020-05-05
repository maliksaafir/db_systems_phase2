<?php
    require_once('config.php');
    require_once('session.php');
    include('header.php');

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_or_cancel"])) {
        if ($_POST["choice"] == "book") {
            $chosen_lesson_sql = "select date, start_time, end_time from lessons where lesson_id = {$_POST['lesson_id']};";
            $chosen_lesson_result = $conn->query($chosen_lesson_sql);
            $chosen_lesson_row = $chosen_lesson_result->fetch_assoc();

            $lesson_date = $chosen_lesson_row['date'];
            $lesson_start = $chosen_lesson_row['start_time'];
            $lesson_end = $chosen_lesson_row['end_time'];

            $overlap_query = "select lesson_id from lessons where student_id = {$login_session['user_id']} and date = '$lesson_date' and ((start_time between '$lesson_start' and '$lesson_end') or (end_time between '$lesson_start' and '$lesson_end'));";
            $overlap_result = $conn->query($overlap_query);
            if($overlap_result->num_rows > 0) {
                echo "This lesson overlaps with one currently in your schedule.";
            } else {
                $sql = "update lessons set student_id = {$login_session['user_id']} where lesson_id = {$_POST['lesson_id']}";

                $result = $conn->query($sql);
                if ($conn->affected_rows == 1) {
                    echo "<p>Lesson booked successfully</p>";
                }
            }
        } else if ($_POST["choice"] == "cancel") {
            $sql = "update lessons set student_id = NULL where lesson_id = {$_POST['lesson_id']}";
            
            $result = $conn->query($sql);
            if ($conn->affected_rows == 1) {
                echo "<p>Lesson cancelled successfully</p>";
            }
        } else {
            die();
        }
    }

    $teacher_id = $_POST['teacher_id'];
    $lessons_sql = "select lesson_id, instruments.name as instrument_name, date, start_time, end_time, charge_rate, round(timestampdiff(MINUTE, start_time, end_time) / 60, 2) as duration, round(charge_rate * timestampdiff(MINUTE, start_time, end_time) / 60, 2) as total_cost, lessons.student_id as student_id from users inner join lessons on users.user_id = lessons.teacher_id inner join instruments on lessons.instrument_id = instruments.instrument_id where lessons.teacher_id = {$teacher_id} and (lessons.student_id is NULL or lessons.student_id = {$login_session['user_id']}) order by date asc, start_time asc;";
    $lessons_result = $conn->query($lessons_sql);

    $teacher_sql = "select concat(fname, ' ', lname) as teacher_name from users where user_id = $teacher_id;";
    $teacher_result = $conn->query($teacher_sql);
    $teacher_row = $teacher_result->fetch_assoc();
    $teacher_name = $teacher_row['teacher_name'];
?>
    <h2><?php echo $teacher_name; ?>'s Open Lessons</h2>
    <table border='1'>
        <tr>
            <th>instrument</th>
            <th>date</th>
            <th>start time</th>
            <th>end time</th>
            <th>duration</th>
            <th>total cost</th>
            <th>sign up</th>
        </tr>
<?php
    while ($row = $lessons_result->fetch_assoc()) {
?>
        <tr>
            <td><?php echo $row['instrument_name'];?></td>
            <td><?php echo $row['date'];?></td>
            <td><?php echo $row['start_time'];?></td>
            <td><?php echo $row['end_time'];?></td>
            <td><?php echo $row['duration'];?> hrs</td>
            <td>$<?php echo $row['total_cost'];?></td>
            <td>
                <form action="" method="post">
                    <input type="hidden" name="lesson_id" value="<?php echo $row['lesson_id'];?>" />
                    <input type="hidden" name="teacher_id" value="<?php echo $teacher_id;?>" />
<?php
        if ($row['student_id'] == $login_session['user_id']) {
?>
                    <input type="hidden" name="choice" value="cancel"/>
                    <input type="submit" name="book_or_cancel" value="Cancel" />
<?php
        } else if ($row['student_id'] == ""){
?>
                    <input type="hidden" name="choice" value="book"/>
                    <input type="submit" name="book_or_cancel" value="Book" />
<?php
        }
        ?>
                </form>
            </td>
        </tr>
<?php
    }
?>