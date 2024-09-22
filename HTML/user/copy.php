<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Selection</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tiny.cloud/1/ogbra9mjiyzmaexzehl0788mvowvxjr48ehixh8wgl11walu/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
</head>

<body>
    <div class="container">
        <h1>Select Payment Options</h1>
        <form>
            <div class="payment-type">
                <h2>Payment Type</h2>
                <textarea id="itinerary" name="itinerary" rows="10"></textarea>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
    <script>
        tinymce.init({
            selector: '#itinerary', // Attach TinyMCE to the textarea with id 'itinerary'
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
</body>

</html>