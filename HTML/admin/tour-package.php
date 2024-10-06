<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:index.php?Login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <link rel="stylesheet" href="css/main.css">
    <title>Tour Packages</title>
    <style>
        #myTable_wrapper {
            width: 100%;
        }

        #myTable_wrapper thead {
            background: var(--maindark);
            color: white;
        }

        #myTable_wrapper .add {
            padding: 2px 12px;
            background: var(--maindark);
            border: 1px solid var(--maindark);
            border-radius: 2px;

        }

        #myTable_wrapper tbody td.dt-type-numeric,
        #myTable_wrapper thead tr th:nth-child(1) {
            text-align: left;
        }

        #myTable th:nth-child(6),
        #myTable td:nth-child(6) p {
            white-space: nowrap;
            /* Prevent text from wrapping */
            overflow: hidden;
            /* Hide overflowed text */
            text-overflow: ellipsis;
            /* Add ellipsis for overflowed text */
        }

        #myTable {
            table-layout: fixed;
        }

        p {
            margin: 0;
        }

        #myTable td {
            vertical-align: middle;
        }

        form .form-control {
            font-size: 12px;
        }
    </style>

</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Tour";
    $on = "off";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $query = "SELECT * FROM tourpackage WHERE isArchive = FALSE";
    $res = mysqli_query($conn, $query);

    ?>

    <div class="overlay">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <h1><i class="fa-solid fa-box-archive fa-fw"></i>&nbsp;&nbsp;Tour Packages</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <table id="myTable" class="table table-striped hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Booking Period</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="data">
                            <?php
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $bsdate = date_create($row['bsdate']);
                                    $bedate = date_create($row['bedate']);

                                    // Format each date
                                    $formattedBsdate = date_format($bsdate, 'M j, Y');
                                    $formattedBedate = date_format($bedate, 'M j, Y');
                            ?>
                                    <tr>
                                        <td><?php echo "$row[tourid]" ?></td>
                                        <td>
                                            <img src="uploads/<?php echo "$row[img]" ?>" alt="tour-package" width="50px" height="50px" style="object-fit: cover;">
                                        </td>
                                        <td><?php echo "$row[title]" ?></td>
                                        <td>&#x20B1; <?php echo number_format($row['price'], 0) ?></td>
                                        <td><?php echo "$formattedBsdate - $formattedBedate" ?></td>
                                        <td>
                                            <p><?php echo "$row[description]" ?></p>
                                        </td>

                                        <td class="text-center">
                                            <!-- <i class="fa-solid fa-pen fa-fw text-primary"></i> -->
                                            <i class="fa-solid fa-box-archive fa-fw text-primary archive" style="cursor: pointer;" data-id="<?php echo $row['tourid'] ?>"></i>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal" id="add" tabindex="-1" role="dialog" aria-labelledby="addLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="request">Add Tour</h5>
                    </div>
                    <form action="inc/tour.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-4 mt-3">
                            <div class="row w-100">
                                <div class="col-8">
                                    <label for="">Title</label><label for="" class="required">*</label>
                                    <input type="text" id="title"
                                        placeholder="Osaka Japan" name="title"
                                        class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label for="">Type</label><label for="" class="required">*</label>
                                    <select name="type" id="type" class="form-control">
                                        <option selected>Domestic</option>
                                        <option>International</option>
                                    </select>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-4 mt-3">
                                    <label for="">Price</label><label for="" class="required">*</label>
                                    <input type="text" id="price"
                                        placeholder="&#x20B1; 9,999.00" name="price"
                                        class="form-control" required>
                                </div>

                                <div class="col-4 mt-3">
                                    <label for="">Booking Start Date</label><label for="" class="required">*</label>
                                    <input type="date" id="bsdate" name="bsdate"
                                        class="form-control" required>
                                </div>

                                <div class="col-4 mt-3">
                                    <label for="">Booking End Date</label><label for="" class="required">*</label>
                                    <input type="date" id="bedate" name="bedate"
                                        class="form-control" required>
                                </div>

                                <div class="row w-100 date-inputs">
                                    <div class="col-4 mt-3">
                                        <label for="">Travel Date Start</label><label for="" class="required">*</label>
                                        <input type="date" id="tsdates" name="tsdates[]" class="form-control" required>
                                    </div>

                                    <div class="col-4 mt-3">
                                        <label for="">Travel Date End</label><label for="" class="required">*</label>
                                        <input type="date" id="tedates" name="tedates[]" class="form-control" required>
                                    </div>

                                    <div class="col-4 mt-3">
                                        <button type="button" class='ml-3 add-date' style="border:none;padding: 4px;background:transparent;"><i class='fa-solid fa-plus fa-fw'></i></button>
                                        <button type="button" class='ml-3 remove-date' style="border:none;padding: 4px;background:transparent;"><i class='fa-solid fa-minus fa-fw'></i></button>
                                    </div>
                                </div>

                                <div class="w-100"></div>
                                <div class="col-12 mt-3">
                                    <label for="description">Short Description</label>
                                    <textarea class="form-control" id="description" name="description" maxlength="250" rows="2" required></textarea>
                                    <div class="char-count float-right">
                                        <span id="charCount">250</span> characters remaining
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="itinerary">Itinerary:</label>
                                    <textarea id="itinerary-textarea" name="itinerary" rows="10"></textarea>
                                </div>
                                <div class="col-12  mt-3">
                                    <label for="">Tour Image</label><label for=""
                                        class="required">*</label>
                                    <input type="file" class="form-control-file" name="img" id="img" required>
                                    <div class="img invalid-feedback">
                                        Please upload file
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button style="display:none" type="submit" name="tour" id="submit">Next</button>
                            <button type="button" id="next" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../user/js/validation.js"></script>
    <script>
        tinymce.init({
            selector: '#itinerary-textarea', // Attach TinyMCE to the textarea with id 'itinerary'
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Oct 2, 2024:
                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            height: 300, // Set height for the editor
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            images_upload_url: 'postAcceptor.php', // Handle image uploads
            branding: false // Remove "Powered by TinyMCE" branding
        });
    </script>
    <script>
        $(document).ready(function() {

            const queryString = window.location.search;
            const urlParams = new URLSearchParams(window.location.search);
            const alert = urlParams.get('alert');

            if (alert == 1) {
                toastr["success"]("Tour package added successfully")
            }

            if (alert == 3) {
                toastr["success"]("Tour package archived successfully")
            }

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "preventDuplicates": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            $('.archive').click(function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, archive it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        const id = $(this).data('id');
                        console.log(id);

                        var urlA = "ajax/load.php";

                        $(document).ajaxSend(function() {
                            $(".overlay").fadeIn(300);
                        });

                        adminid = <?php echo $adminid ?>;

                        $.ajax({
                            type: 'POST',
                            url: urlA,
                            data: {
                                id: id,
                                adminid: adminid,
                                archive: 'archive'
                            },
                            success: function(data) {
                                $('#data').html(data);
                                toastr["success"]("Tour package archived successfully");
                            },
                            error: function(e) {
                                alert("error");
                            }
                        }).done(function() {
                            setTimeout(function() {
                                $(".overlay").fadeOut(300);
                            }, 500);
                        });

                        Swal.fire({
                            title: "Archived",
                            text: "Package has been archived.",
                            icon: "success"
                        });
                    }
                });
            })

            $('.add-date').click(function() {
                var newDate = `<div class="row w-100 date-inputs">
                                    <div class="col-4 mt-3">
                                        <label for="">Travel Date Start</label><label for="" class="required">*</label>
                                        <input type="date" id="tsdates" name="tsdates[]" class="form-control" required>
                                    </div>

                                    <div class="col-4 mt-3">
                                        <label for="">Travel Date End</label><label for="" class="required">*</label>
                                        <input type="date" id="tedates" name="tedates[]" class="form-control" required>
                                    </div>
                                </div>`;
                $('.date-inputs').last().after(newDate);
            });


            $(document).on('click', '.remove-date', function() {

                $(this).closest('.date-inputs').next('.date-inputs').remove();
            });

            $('#myTable').DataTable({
                layout: {
                    topStart: function() {
                        let toolbar = document.createElement('div');
                        toolbar.innerHTML = "<button  class='btn btn-primary add' data-target='#add' data-toggle='modal'><i class='fa-solid fa-plus fa-fw'></i></button>";

                        return toolbar;
                    }
                },
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        width: '40%'
                    }, // Column width example
                    null
                ]
            });

            const $textarea = $('#description');
            const $charCount = $('#charCount');
            const maxLength = $textarea.attr('maxlength');

            $textarea.on('input', function() {
                const remaining = maxLength - $(this).val().length;
                $charCount.text(remaining);
            });

            // Initialize the character count
            $charCount.text(maxLength);
        });
    </script>
</body>

</html>