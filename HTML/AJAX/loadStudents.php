<?php
include '../../inc/Include.php';

if (isset($_POST["year"])) {
    $course = $_POST["course"];
    $year = $_POST["year"];
    $adminid = $_POST["adminid"];

    $query = "SELECT * FROM student WHERE course = '{$course}' && year = '{$year}'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {


        while ($rows = mysqli_fetch_array($result)) {
            $middle = substr($rows['middle'], 0, 1);
?>


            <tr>
                <td><?php echo "$rows[studentid]" ?></td>
                <td><?php echo "$rows[given] $middle. $rows[surname]" ?></td>
                <td><?php echo "$rows[section]" ?></td>
                <td><?php echo "$rows[regular]" ?></td>
                <td><a
                        href="viewStudents.php?adminid=<?php echo "$adminid&studentid=$rows[studentid]" ?>">View</a>
                </td>
            </tr>

        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="4" style="text-align:center;">No data found</td>
        </tr>
        <?php
    }
}

if (isset($_POST["subject"])) {
    // $course = $_POST["course"];
    $subject = $_POST["subject"];
    $adminid = $_POST["adminid"];

    $query = "SELECT * FROM faculty WHERE subject = '{$subject}' || subject2 = '{$subject}'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td><?php echo "$rows[facultyid]" ?></td>
                <td><?php echo "$rows[name]" ?></td>
                <td><?php echo "$rows[subject]" ?></td>
                <td><?php echo "$rows[subject2]" ?></td>
            </tr>

        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="4" style="text-align:center;">No data found</td>
        </tr>
<?php
    }
}
?>