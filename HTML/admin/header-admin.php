<?php
$query = "SELECT * FROM admin WHERE adminid = '$adminid'";
$res = mysqli_query($conn, $query);
$rows = mysqli_fetch_array($res);
?>

<div class="header">
    <div class="wrapper">
        <div class="row">
            <div class="col-8 flex" style="justify-content: flex-start">
                <label for=""><i class="fa-solid fa-bars fa-fw"></i></label>
                <h4 style="font-family: Analouge;font-weight: bolder;">TRAVELEASE</h4>
            </div>

            <div class="col-4 flex">
                <select name="branch" class="form-control" id="branch">
                    <option value="Calumpang" class="<?php echo $adminid ?>" selected>Calumpang</option>
                    <option value="Calamba" class="<?php echo $adminid ?>">Calamba</option>
                </select>
                <div class="dropdown">
                    <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fa-solid fa-bars" style="color:var(--font);"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <h2 class="dropdown-header font-uppercase">
                            <?php echo "$rows[name]" ?>
                        </h2>
                        <hr>
                        <a class="dropdown-item" href="#"><i class="fa-solid fa-gear fa-fw mr-3"></i> Settings</a>
                        <a href="../index.php?Logout" class="dropdown-item">
                            <i class="fa-solid fa-right-from-bracket fa-fw mr-3"></i> <span> Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>