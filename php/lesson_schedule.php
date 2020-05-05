<?php
    require_once('config.php');
    require_once('session.php');
    include('header.php');

    if ($login_session['is_teacher'] == 1) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
            $sql = "delete from lessons where lesson_id = {$_POST['lesson_id']} and teacher_id = {$login_session['user_id']};";
                
            $conn->query($sql);
            if ($conn->affected_rows == 1) {
                echo "<p>Lesson removed successfully</p>";
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_lesson'])) {
            $data_missing = array();
            if (isset($_POST['lesson_instrument'])) {
                $lesson_instrument = $_POST['lesson_instrument'];
            } else {
                $data_missing[] = 'Lesson Instrument';
            }

            if (isset($_POST['lesson_date'])) {
                $lesson_date = $_POST['lesson_date'];
            } else {
                $data_missing[] = 'Lesson Date';
            }

            if (isset($_POST['lesson_start_time'])) {
                $lesson_start = $_POST['lesson_start_time'];
            } else {
                $data_missing[] = 'Lesson Start Time';
            }

            if (isset($_POST['lesson_end_time'])) {
                $lesson_end = $_POST['lesson_end_time'];
            } else {
                $data_missing[] = 'Lesson End Time';
            }

            if (empty($data_missing)) {
                if ($lesson_start < $lesson_end) {
                    $duplicate_lesson_query = "select lesson_id from lessons where teacher_id = {$login_session['user_id']} and date = '$lesson_date' and ((start_time between '$lesson_start' and '$lesson_end') or (end_time between '$lesson_start' and '$lesson_end'));";
                    $duplicate_lesson_result = $conn->query($duplicate_lesson_query);

                    
                    
                    if ($duplicate_lesson_result->num_rows > 0) {
                        echo "Your new lesson overlaps with one of your current ones.";
                    } else {
                        $new_lesson_query = "INSERT INTO `lessons` (`date`, `start_time`, `end_time`, `teacher_id`, `student_id`, `instrument_id`) VALUES ('$lesson_date', '$lesson_start', '$lesson_end', {$login_session['user_id']}, NULL, $lesson_instrument);";
                        $conn->query($new_lesson_query);
                        echo $conn->error;

                        if($conn->affected_rows == 1) {
                            echo "Successfully added a lesson!";
                        }
                    }
                } else {
                    echo "Your lesson ends before it begins!";
                }
            } else {
                echo $error;
            }
        }

        $lessons_sql = "select lesson_id, date, start_time, end_time, instruments.name as instrument_name, charge_rate * timestampdiff(HOUR, start_time, end_time) as total_price, student_id from lessons natural join instruments inner join users on teacher_id = user_id where lessons.`teacher_id` = {$login_session['user_id']} order by date asc, start_time asc;";

        $lessons_result = $conn->query($lessons_sql);

?>
        <table border='1'>
            <tr>
                <th>instrument</th>
                <th>date</th>
                <th>start time</th>
                <th>end time</th>
                <th>booked?</th>
                <th>student</th>
                <th>delete?</th>
            </tr>
<?php
        while ($row = $lessons_result->fetch_assoc()) {
?>
            <tr>
                <td><?php echo $row['instrument_name']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
<?php
            if (isset($row['student_id'])) {
                $student_sql = "select concat(`fname`, ' ', `lname`) as `student_name`, `email_address` from `users` where `user_id` = {$row['student_id']};";
                $student_result = $conn->query($student_sql);
                $student_row = $student_result->fetch_assoc();
?>
                <td>Yes</td>
                <td><?php echo $student_row['student_name'] . " (" . $student_row['email_address'] . ")"; ?></td>
<?php
            } else {
?>
                <td>No</td>
                <td></td>
<?php
            }
?>
            <td>
                <form action="" method="post">
                        <input type="hidden" name="lesson_id" value="<?php echo $row['lesson_id']; ?>" />
                        <input type="submit" name="delete" value="Delete"/>
                </form>
            </td>
<?php
        }
?>
        </table>
        <p>Add a Lesson</p>
        <form action="" method="post">
            <label>What instrument?</label>
            <br>
<?php
        $instruments_query = "select instrument_id, instruments.name as instrument_name from instruments natural join teaches_r where teaches_r.teacher_id = {$login_session['user_id']};";
        $instruments_result = $conn->query($instruments_query);
        while ($instrument_row = $instruments_result->fetch_assoc()) {
?>
            <input type="radio" name="lesson_instrument" value="<?php echo $instrument_row['instrument_id']; ?>" />
            <label><?php echo $instrument_row['instrument_name']; ?></label>
            <br>
<?php
        }
?>
            <label>What day?</label>
            <input type="date" name="lesson_date"/>
            <br>
            <label>Start time?</label>
            <input type="time" name="lesson_start_time" />
            <br>
            <label>End time?</label>
            <input type="time" name="lesson_end_time" />
            <br>
            <input type="submit" name="submit_lesson" value="Add Lesson" />
        </form>

<?php
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
            $sql = "update lessons set student_id = NULL where lesson_id = {$_POST['lesson_id']}";
                    
            $result = $conn->query($sql);
            if ($conn->affected_rows == 1) {
                echo "<p>Lesson cancelled successfully</p>";
            }    
        }

        $lessons_sql = "select lesson_id, teacher_id, date, start_time, end_time, instruments.name as instrument_name, concat(fname, ' ', lname) as teacher_name, email_address as teacher_email, charge_rate, timestampdiff(HOUR, start_time, end_time) as duration, charge_rate * timestampdiff(HOUR, start_time, end_time) as total_cost from lessons natural join instruments inner join users on teacher_id = user_id where `student_id` = {$login_session['user_id']} order by date asc, start_time asc;";

        $lessons_result = $conn->query($lessons_sql);
?>

        <table border='1'>
            <tr>
                <th>teacher</th>
                <th>instrument</th>
                <th>date</th>
                <th>start time</th>
                <th>end time</th>
                <th>charge rate</th>
                <th>total price</th>
                <th>cancel?</th>
            </tr>
<?php
        while ($row = $lessons_result->fetch_assoc()) {
?>
            <tr>
                <td><?php echo $row['teacher_name'] . " (" . $row['teacher_email'] . ")"; ?></td>
                <td><?php echo $row['instrument_name']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
                <td>$<?php echo $row['charge_rate']; ?>/hr</td>
                <td>$<?php echo $row['total_cost']; ?>/hr</td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="lesson_id" value="<?php echo $row['lesson_id']; ?>" />
                        <input type="hidden" name="teacher_id" value="<?php echo $row['teacher_id'];?>" />
                        <input type="submit" name="cancel" value="Cancel"/>
                    </form>
                </td>
            </tr>
<?php
        }
    }
?>