<!-- Modal -->
<div class="modal" id="settings" tabindex="-1" role="dialog" aria-labelledby="settingsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="align-items: baseline;">
                <h4 class="modal-title" id="settingsTitle" class="w-700">
                    <?php echo "$rows[name]" ?>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../AJAX//total-passenger.php" method="POST" class="settings">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="w-500">Name</label>
                                <input name="name" type="text" class="form-control" id="name" aria-describedby="emailHelp"
                                    value="<?php echo htmlspecialchars($rows['name'], ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="email" class="w-500">Email Address</label>
                                <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp"
                                    value="<?php echo "$rows[email]" ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="old-password" class="w-500">Old Password</label>
                                <input name="old-password" type="password" class="form-control" id="old-password"
                                    aria-describedby="emailHelp" required>
                                <small class="text-danger opass" style="display: none;">Old Password do not match</small>
                            </div>
                        </div>
                        <div class="w-100"></div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="new-password" class="w-500">New Password</label>
                                <input name="new-password" type="password" class="form-control" id="new-password"
                                    aria-describedby="emailHelp">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="confirm-password" class="w-500">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password"
                                    aria-describedby="emailHelp">
                                <small class="text-danger cpass" style="display: none;">Password do not match</small>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" id="aDdn" name="settings-admin" class="btn btn-primary" value="Save changes">
            </div>
            </form>
        </div>
    </div>

    <script>
        $('#new-password, #confirm-password').keyup(function() {
            const pass = $('#new-password').val();
            const cpass = $('#confirm-password').val();
            const message = $('.cpass');

            if (pass !== cpass) {
                message.show();
                message.removeClass('text-success').addClass('text-danger').text('Passwords do not match.');
                $('#aDdn').attr('disabled', true);
            } else {
                message.show();
                message.removeClass('text-danger').addClass('text-success').text('Passwords match');
                $('#aDdn').attr('disabled', false);
            }

            // console.log(pass, cpass, settings);
        });

        $('#old-password').keyup(function() {
            const old = $('#old-password').val();
            const input = '<?php echo $rows['password']; ?>';

            $.ajax({
                url: '../AJAX/total-passenger.php', // URL to your server-side script
                type: 'POST',
                data: {
                    old: old,
                    input: input,
                },
                success: function(response) {
                    // console.log(old);
                    // console.log(response);
                    if (response == 'correct') {
                        $('.opass').text('Old password is correct.').removeClass('text-danger').addClass('text-success').show();
                    } else {
                        $('.opass').text('Old password is incorrect.').removeClass('text-success').addClass('text-danger').show();
                    }
                },
                error: function() {
                    console.log('ERR');
                    $('.opass').text('An error occurred. Please try again.').addClass('text-danger').show();
                }
            }).done(function() {
                $(".overlay").fadeOut(300);
            });
        })
    </script>
</div>