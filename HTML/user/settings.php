<!-- Modal -->
<div class="modal" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="align-items: baseline;">
                <h4 class="modal-title" id="settingsTitle" class="w-700">
                    <?php echo "$rows[firstname] $rows[lastname]"?>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="firstname" class="w-500">Firstname</label>
                                <input type="text" class="form-control" id="firstname" aria-describedby="emailHelp"
                                    value="<?php echo "$rows[firstname]"?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lastname" class="w-500">Lastname</label>
                                <input type="text" class="form-control" id="lastname" aria-describedby="emailHelp"
                                    value="<?php echo "$rows[lastname]"?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email" class="w-500">Email Address</label>
                                <input type="email" class="form-control" id="email" aria-describedby="emailHelp"
                                    value="<?php echo "$rows[email]"?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="old-password" class="w-500">Old Password</label>
                                <input type="password" class="form-control" id="old-password"
                                    aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="new-password" class="w-500">New Password</label>
                                <input type="password" class="form-control" id="new-password"
                                    aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="confirm-password" class="w-500">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password"
                                    aria-describedby="emailHelp">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>