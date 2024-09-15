<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <title>Home</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }

        .wd-100 {
            width: 100%;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $ticketid = $_GET['ticketid'];
    $active = "Ticketed";
    $totalPass = $_SESSION['total'];
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";
    ?>
    <form action="inc/personal.php" style="width: 100%;" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
        <?php
        ?>
        <div class="main">
            <input type="hidden" value="<?php echo "$userid"; ?>"
                name="userid">
            <input type="hidden" value="round-trip" name="trip">

            <div class="row wrap" id="personal-info">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Personal Information</h4>
                        </div>
                    </div>

                    <div class="row info">
                        <div class="row data">
                            <?php
                            for ($x = 0; $x < $totalPass; $x++) {
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
                                        <div class="col-4 number">
                                            <label for="">Mobile Number</label><label for="" class="required">*</label>
                                            <input type="text" pattern="\d*" class="form-control contact-number" placeholder="0912312324" maxlength="12" name="number<?php echo "[$x]" ?>" required>
                                            <div class="phone invalid-feedback">
                                                Please input a valid contact number
                                            </div>
                                        </div>
                                        <div class="col email-group">
                                            <div class="row">
                                                <div class="col-6 ">
                                                    <label for="">Email Address</label><label for="" class="required">*</label>
                                                    <input type="email" class="form-control email" placeholder="juandelacruz@gmail.com" name="email<?php echo "[$x]" ?>" required>

                                                </div>
                                                <div class="col-6 ">
                                                    <label for="">Confirm Email Address</label><label for="" class="required">*</label>

                                                    <input type="email" class="form-control confirm-email" placeholder="juandelacruz@gmail.com" required name="cemail">
                                                    <div class="invalid-feedback">
                                                        Email addresses do not match.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4 wd-100">
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
                                            <input type="text" class="form-control" placeholder="Bertho" name="middlename<?php echo "[$x]" ?>">
                                        </div>
                                    </div>

                                    <div class="row mt-4 wd-100 form-group">
                                        <div class="col-4">
                                            <label for="">Date of Birth</label><label for="" class="required">*</label>
                                            <input type="date" name="bdate<?php echo "[$x]" ?>" class="form-control bdate" required>
                                        </div>
                                        <div class="col-4">
                                            <label for="age">Age</label>
                                            <input type="text" name="age<?php echo "[$x]" ?>" class="form-control age" readonly>
                                        </div>
                                        <div class="col-4">
                                            <label for="">Sex</label><label for="" class="required">*</label><br>
                                            <input type="radio" name="sex<?php echo "[$x]" ?>" value="Male" required>
                                            <label for="" class="ml-2 w-500">Male</label>
                                            <input type="radio" name="sex<?php echo "[$x]" ?>" value="Female" class="ml-5">
                                            <label for="" class="ml-2 w-500">Female</label>
                                        </div>
                                    </div>

                                    <div class="row mt-4 wd-100">
                                        <div class="col-4">
                                            <label for="civil">Civil Status</label><label for="" class="required">*</label>
                                            <select name="civil<?php echo "[$x]" ?>" class="form-control">
                                                <option>Single</option>
                                                <option>Married</option>
                                                <option>Separated</option>
                                                <option>Divorce</option>
                                                <option>Widowed</option>
                                            </select>
                                        </div>
                                        <div class="col-8">
                                            <label for="">Complete Address</label><label for="" class="required">*</label>
                                            <input type="text" name="address<?php echo "[$x]" ?>" class="form-control" required
                                                placeholder="123 Street Purok 2 Brgy. Bagumbayan, Pasay City, Metro Manila">
                                        </div>
                                    </div>


                                    <div class="row mt-4 wd-100 ids">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="passport">Passport</label><label for="" class="required">*</label>
                                                <input type="file" class="form-control-file" name="passport<?php echo "[$x]" ?>" required>

                                                <div class="img invalid-feedback">
                                                    Please upload a passport
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="id">Valid ID</label><label for="" class="required">*</label>
                                                <input type="file" class="form-control-file" name="valid-id<?php echo "[$x]" ?>" required>

                                                <div class="img invalid-feedback">
                                                    Please upload a valid id
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="ticketid" value="<?php echo $ticketid ?>">
                                </div>

                            <?php
                            }
                            ?>
                        </div>

                        <div class="col-12 mt-4" style="display:flex; justify-content: flex-end;">
                            <button id="next" class="btn btn-primary pl-5 pr-5 btn-next" type="button">Next</button>
                            <button style="display:none" type="submit" name="personal" id="submit">Next</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
    </div>

    <script src="js/validation.js"></script>
    <script>
        <?php if (isset($_SESSION['upload_error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Upload Error',
                text: '<?php echo $_SESSION['upload_error']; ?>'
            });
            <?php unset($_SESSION['upload_error']); ?>
        <?php endif; ?>
    </script>
</body>

</html>