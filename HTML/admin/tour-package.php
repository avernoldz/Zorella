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

    $query = "SELECT * FROM tourpackage";
    $res = mysqli_query($conn, $query);

    ?>
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
                                <th>Duration</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                            ?>
                                    <tr>
                                        <td><?php echo "$row[tourid]" ?></td>
                                        <td>
                                            <img src="uploads/<?php echo "$row[img]" ?>" alt="tour-package" width="50px" height="50px" style="object-fit: cover;">
                                        </td>
                                        <td><?php echo "$row[title]" ?></td>
                                        <td>&#x20B1; <?php echo "$row[price]" ?></td>
                                        <td><?php echo "$row[duration]" ?></td>
                                        <td>
                                            <p><?php echo "$row[description]" ?></p>
                                        </td>

                                        <td class="text-center"><i class="fa-solid fa-pen fa-fw text-primary"></i></td>
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
                                    <label for="">Duration</label><label for="" class="required">*</label>
                                    <input type="text" id="duration"
                                        placeholder="11 Days, 10 Night" name="duration"
                                        class="form-control" required>
                                </div>
                                <div class="row w-100 date-inputs">
                                    <div class="col-4 mt-3">
                                        <label for="">Date</label><label for="" class="required">*</label>
                                        <button type="button" class='ml-3 add-date' style="border:none;padding: 4px;background:transparent;"><i class='fa-solid fa-plus fa-fw'></i></button>
                                        <input type="date" id="date" name="dates[]" class="form-control" required>
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

            $('.add-date').click(function() {
                var newDate = ` <div class="col-4 mt-3">
                                        <label for="" class="mb-2">Date</label><label for="" class="required">*</label>
                                        <input type="date" id="date" name="dates[]" class="form-control" required>
                                    </div>`;
                $('.date-inputs').append(newDate);
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