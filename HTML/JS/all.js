$("#course").change(function() {
    var course = $('#course').find(":selected").val();
        $(".dept h2").html(course);
    })