<div class="header">
    <div class="wrapper">
        <div class="row">
            <div class="col-8 flex" style="justify-content: flex-start">
                <label for=""><i class="fa-solid fa-bars fa-fw"></i></label>
                <h4 style="font-family: Analouge;font-weight: bolder;">TRAVELEASE</h4>
            </div>

            <div class="col-4 flex" style="justify-content:flex-end;">
                <div class="dropdown">
                    <a class="" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fa-solid fa-bars" style="color:var(--font);"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <h2 class="dropdown-header font-uppercase">
                            <?php echo "$rows[firstname] $rows[lastname]" ?>
                        </h2>
                        <hr>
                        <a class="dropdown-item" href="my-booking.php?<?php echo "userid=$rows[userid]" ?>"><i class="fa-solid fa-bookmark fa-fw mr-3"></i> My
                            Bookings</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#settings"><i
                                class="fa-solid fa-gear fa-fw mr-3"></i> Settings</a>
                        <a href="../index.php?logout" class="dropdown-item">
                            <i class="fa-solid fa-right-from-bracket fa-fw mr-3"></i> <span> Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>