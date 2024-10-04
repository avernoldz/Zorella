<?php
session_start();
include '../../inc/Include.php';

if (isset($_POST['adult'])) {
    $adult = intval($_POST['adult']);
    $child = intval($_POST['child']);
    $infant = intval($_POST['infant']);
    $senior = intval($_POST['senior']);

    if (empty($adult)) {
        $adult = 0;
    }
    if (empty($child)) {
        $child = 0;
    }
    if (empty($infant)) {
        $infant = 0;
    }
    if (empty($senior)) {
        $senior = 0;
    }

    $total = $adult + $child + $infant + $senior;

    for ($x = 0; $x < $total; $x++) {
?>

        <div class="row body">
            <div class="row wd-100">
                <div class="col-4">
                    <label for="" style="font-size: 20px;">Applicant
                        <?php $b = $x + 1;
                        echo "$b" ?></label>
                </div>
            </div>
            <div class="row mt-2 wd-100">

                <div class="col-4">
                    <label for="">Mobile Number</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="0912312324" maxlength="12" name="number<?php echo "[$x]" ?>" required>
                </div>
                <div class="col-4">
                    <label for="">Email Address</label><label for="" class="required">*</label>
                    <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" name="email<?php echo "[$x]" ?>" required>
                </div>
                <div class="col-4">
                    <label for="">Confirm Email Address</label><label for="" class="required">*</label>
                    <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" required name="confirm-email">
                </div>
            </div>

            <div class="row mt-5 wd-100">
                <div class="col-4">
                    <label for="">Last Name</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="Dela Cruz" name="lastname<?php echo "[$x]" ?>" required>
                </div>
                <div class="col-4">
                    <label for="">First Name</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="Juan" name="firstname<?php echo "[$x]" ?>" required>
                </div>
                <div class="col-4">
                    <label for="">Middle Name</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="Bertho" name="middlename<?php echo "[$x]" ?>" required>
                </div>
            </div>

            <div class="row mt-5 wd-100">
                <div class="col-4">
                    <label for="age">Age</label>
                    <select name="age<?php echo "[$x]" ?>" id="age" class="form-control">
                        <?php
                        for ($i = 1; $i <= 100; $i++) {
                            echo "
                                                <option>$i</option>
                                                ";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-4">
                    <label for="">Date of Birth</label><label for="" class="required">*</label>
                    <input type="date" name="bdate<?php echo "[$x]" ?>" class="form-control" required>
                </div>
                <div class="col-4">
                    <label for="">Sex</label><label for="" class="required">*</label><br>
                    <input type="radio" name="sex<?php echo "[$x]" ?>" value="male" required>
                    <label for="" class="ml-2 w-500">Male</label>
                    <input type="radio" name="sex<?php echo "[$x]" ?>" value="female" class="ml-5">
                    <label for="" class="ml-2 w-500">Female</label>
                </div>
            </div>

            <div class="row mt-5 wd-100">
                <div class="col-4">
                    <label for="civil">Civil Status</label><label for="" class="required">*</label>
                    <select name="civil<?php echo "[$x]" ?>" id="civil" class="form-control">
                        <option value="">Single</option>
                        <option value="">Married</option>
                        <option value="">Separated</option>
                        <option value="">Divorce</option>
                        <option value="">Widowed</option>
                    </select>
                </div>
                <div class="col-8">
                    <label for="">Complete Address</label><label for="" class="required">*</label>
                    <input type="text" name="address<?php echo "[$x]" ?>" class="form-control" required
                        placeholder="123 Street Purok 2 Brgy. Bagumbayan, Pasay City, Metro Manila">
                </div>
            </div>


            <div class="row mt-5 wd-100">
                <div class="col-4">
                    <div class="form-group">
                        <label for="passport">Passport</label><label for="" class="required">*</label>
                        <input type="file" class="form-control-file" id="passport" name="passport<?php echo "[$x]" ?>" required>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="id">Valid ID</label><label for="" class="required">*</label>
                        <input type="file" class="form-control-file" id="id" name="valid-id<?php echo "[$x]" ?>" required>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
}

if (isset($_POST['old'])) {
    $old = $_POST['old'];
    $input = $_POST['input'];

    if (password_verify($old, $input)) {
        echo "correct";
    } else {
        echo "invalid.";
    }
}

if (isset($_POST['settings'])) {
    $options = ['cost' => 12,];

    // Retrieve and escape POST data
    $userid = $_SESSION['userid'];
    $firstname = isset($_POST['firstname']) ? mysqli_real_escape_string($conn, $_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? mysqli_real_escape_string($conn, $_POST['lastname']) : '';
    $phonenumber = isset($_POST['phonenumber']) ? mysqli_real_escape_string($conn, $_POST['phonenumber']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $old_password = isset($_POST['old-password']) ? mysqli_real_escape_string($conn, $_POST['old-password']) : '';
    $new_password = isset($_POST['new-password']) ? mysqli_real_escape_string($conn, $_POST['new-password']) : '';

    if ($new_password != '') {
        $hash_pass = password_hash("$new_password", PASSWORD_BCRYPT, $options);
        $password = $hash_pass;
    } else {
        $hash_pass = password_hash("$old_password", PASSWORD_BCRYPT, $options);
        $password = $hash_pass;
    }


    $stmt = $conn->prepare("UPDATE user SET firstname = ?, lastname = ?, phonenumber = ?, email = ?, password = ? WHERE userid = ?");
    $stmt->bind_param("ssssss", $firstname, $lastname, $phonenumber, $email, $password, $userid);

    // Execute the statement
    header("Location: ../../user/dashboard.php?userid=$userid&alert=2");

    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['settings-admin'])) {
    $options = ['cost' => 12,];

    // Retrieve and escape POST data
    $adminid = $_SESSION['adminid'];
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $old_password = isset($_POST['old-password']) ? mysqli_real_escape_string($conn, $_POST['old-password']) : '';
    $new_password = isset($_POST['new-password']) ? mysqli_real_escape_string($conn, $_POST['new-password']) : '';

    if ($new_password != '') {
        $hash_pass = password_hash("$new_password", PASSWORD_BCRYPT, $options);
        $password = $hash_pass;
    } else {
        $hash_pass = password_hash("$old_password", PASSWORD_BCRYPT, $options);
        $password = $hash_pass;
    }


    $stmt = $conn->prepare("UPDATE admin SET `name` = ?, email = ?, `password` = ? WHERE adminid = ?");
    $stmt->bind_param("ssss", $name, $email, $password, $adminid);

    // Execute the statement
    header("Location: ../../admin/dashboard-admin.php?adminid=$adminid&alert=2");

    $stmt->execute();
    $stmt->close();
}
