<?php
include '../../Connections/Include.php';

if(isset($_POST['adult'])) {
    $adult = intval($_POST['adult']);
    $child = intval($_POST['child']);
    $infant = intval($_POST['infant']);
    $senior = intval($_POST['senior']);

    $total = $adult + $child + $infant + $senior;

    for ($x=1; $x <= $total; $x++) {
        ?>

<div class="row body">
    <div class="row wd-100">
        <div class="col-4">
            <label for="" style="font-size: 20px;">Applicant
                <?php echo "$x"?></label>
        </div>
    </div>
    <div class="row mt-2 wd-100">

        <div class="col-4">
            <label for="">Mobile Number</label><label for="" class="required">*</label>
            <input type="text" class="form-control" placeholder="0912312324" maxlength="12" name="number" required>
        </div>
        <div class="col-4">
            <label for="">Email Address</label><label for="" class="required">*</label>
            <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" name="email" required>
        </div>
        <div class="col-4">
            <label for="">Confirm Email Address</label><label for="" class="required">*</label>
            <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" required name="confirm-email">
        </div>
    </div>

    <div class="row mt-5 wd-100">
        <div class="col-4">
            <label for="">Last Name</label><label for="" class="required">*</label>
            <input type="text" class="form-control" placeholder="Dela Cruz" name="lastname" required>
        </div>
        <div class="col-4">
            <label for="">First Name</label><label for="" class="required">*</label>
            <input type="text" class="form-control" placeholder="Juan" name="firstname" required>
        </div>
        <div class="col-4">
            <label for="">Middle Name</label><label for="" class="required">*</label>
            <input type="text" class="form-control" placeholder="Bertho" name="middlename" required>
        </div>
    </div>

    <div class="row mt-5 wd-100">
        <div class="col-4">
            <label for="">Age</label>
            <select name="" id="" class="form-control">
                <?php
                        for($i=1; $i <= 100; $i++) {
                            echo "
                                                <option>$i</option>
                                                ";
                        }
        ?>
            </select>
        </div>
        <div class="col-4">
            <label for="">Date of Birth</label><label for="" class="required">*</label>
            <input type="date" name="bdate" class="form-control" required>
        </div>
        <div class="col-4">
            <label for="">Sex</label><label for="" class="required">*</label><br>
            <input type="radio" name="sex" value="male" required>
            <label for="" class="ml-2 w-500">Male</label>
            <input type="radio" name="sex" value="female" class="ml-5">
            <label for="" class="ml-2 w-500">Female</label>
        </div>
    </div>

    <div class="row mt-5 wd-100">
        <div class="col-4">
            <label for="">Civil Status</label><label for="" class="required">*</label>
            <select name="" id="" class="form-control">
                <option value="">Single</option>
                <option value="">Married</option>
                <option value="">Separated</option>
                <option value="">Divorce</option>
                <option value="">Widowed</option>
            </select>
        </div>
        <div class="col-8">
            <label for="">Complete Address</label><label for="" class="required">*</label>
            <input type="text" name="address" class="form-control" required
                placeholder="123 Street Purok 2 Brgy. Bagumbayan, Pasay City, Metro Manila">
        </div>
    </div>


    <div class="row mt-5 wd-100">
        <div class="col-4">
            <div class="form-group">
                <label for="passport">Passport</label><label for="" class="required">*</label>
                <input type="file" class="form-control-file" id="passport" required>
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="id">Valid ID</label><label for="" class="required">*</label>
                <input type="file" class="form-control-file" id="id" required>
            </div>
        </div>
    </div>
</div>

<?php
    }
}

if(isset($_POST['edit'])) {
    $given = $_POST['given'];
    $middle = $_POST['middle'];
    $surname = $_POST['surname'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $regular = $_POST['regular'];

    $facultyid = $_POST['facultyid'];
    $studentid = $_POST['studentid'];

    $select = "SELECT studentid FROM student ORDER BY studentid DESC LIMIT 1";
    $results = mysqli_query($conn, $select);
    $rows = mysqli_fetch_array($results);
    $id = $rows['studentid'] + 1;

    $query = "UPDATE `student` SET `given`='$given',`middle`='$middle',
    `surname`='$surname',`course`='$course',`year`='$year',`regular`='$regular',`section`='$section' WHERE studentid = '$studentid'";

    $query2 = "INSERT INTO `studSubject`(`studentid`, `facultyid`) 
    VALUES ('$id','$facultyid')";

    if(mysqli_query($conn, $query)) {
        mysqli_query($conn, $query2);
        echo "<script>window.location.href='../view-students-faculty.php?facultyid=$facultyid&studentid=$studentid&alert=2';</script>";
    } else {
        echo mysqli_error($conn);
    }

}
?>