$(document).ready(function() {
    // $(".editor-basic").summernote({
    //     height: 200,
    //     toolbar: [
    //         // [groupName, [list of button]]
    //         ["style", ["bold", "italic", "underline"]]
    //     ]
    // });
    $.validator.addMethod(
        "dateBefore",
        function(value, element, params) {
            // if end date is valid, validate it as well
            var end = $(params);
            if (end.val() == "" || value == "") {
                return false;
            }
            var start = moment(value, "DD/MM/YYYY")
                .format("YYYY-MM-DD")
                .valueOf();
            end = moment($(params).val(), "DD/MM/YYYY")
                .format("YYYY-MM-DD")
                .valueOf();
            return start < end;
        },
        "Must be before corresponding end date"
    );

    $.validator.addMethod(
        "dateAfter",
        function(value, element, params) {
            // if start date is valid, validate it as well
            var start = $(params);
            if (start.val() == "" || value == "") {
                return false;
            }
            var end = moment(value, "DD/MM/YYYY")
                .format("YYYY-MM-DD")
                .valueOf();
            start = moment(start.val(), "DD/MM/YYYY")
                .format("YYYY-MM-DD")
                .valueOf();
            return end > start;
        },
        "Must be after corresponding start date"
    );

    $(".select2").select2();

    $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch({
            onText: "Enable",
            offText: "Disable"
        });
        $(this).bootstrapSwitch("state", $(this).prop("checked"));
    });

    $("form.class-create").each(function() {
        $(this).validate({
            ignore: "",
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            }
        });
    });
});
function bulkActions(table, page, token) {
    $("#dataTabled-table_length").after(
        '<div id="dataTabled-table_filter" class="dataTables_filter"></div>'
    );
    if (page != "levels") {
        var statusFilter = $(
            '<label>Status:</label> <select class="input-sm mr-2" name="status" id="status"><option value="active">All</option><option value="published">Published</option><option value="draft">Draft</option></select>&nbsp;'
        ).prependTo($(".dataTables_filter"));
    }
    $("#classes-list_wrapper .row:first").after(
        '<div class="row" style="display:none;" id="table-row-action"><div class="col-sm-12 col-md-6"><form method="POST" action="/bulk-action?from=' +
            page +
            '"><input type="hidden" name="_token" value="' +
            token +
            '" /><select name="action" id="table-action" class="mr-2"><option value="">Select</option><option value="draft">Mark Draft</option><option value="publish">Publish</option></select><button class="btn btn-primary" id="check-action">Submit</button></form></div></div>'
    );
    $(document).on("change", "#status", function() {
        table.ajax.reload();
    });

    $("body").on("click", "#check-action", function(e) {
        e.preventDefault();
        let action_ids = [];
        table.$('input[type="checkbox"]').each(function() {
            // If checkbox is checked
            if (this.checked) {
                $("#check-action")
                    .parent("form")
                    .append(
                        "<input type='hidden' name='bulk_ids[]' value='" +
                            this.value +
                            "' />"
                    );
            }
        });
        $("#check-action")
            .parent("form")
            .submit();
    });
    $("#classes-list tbody").on("change", 'input[type="checkbox"]', function() {
        $("#table-row-action").fadeIn();
    });
}
$(document).ready(function() {
    $("body").on("submit", "form", function() {
        $(".overlay").show();
    });

    $(".editor-basic").summernote({
        height: 200,
        toolbar: [
            // [groupName, [list of button]]
            ["style", ["bold", "italic", "underline"]]
        ]
    });

    $(".editor-medium").summernote({
        height: 200,
        toolbar: [
            // [groupName, [list of button]]
            ["style", ["bold", "italic", "underline"]],
            ["para", ["ul", "ol"]],
            ["table", ["table"]],
            ["view", ["fullscreen"]]
        ]
    });

    $(".nav-sidebar li")
        .find("a")
        .each(function() {
            var link = $(this).attr("href"); //Check if some menu compares inside your the browsers link
            if (link == document.location.href) {
                if (
                    !$(this)
                        .parents()
                        .hasClass("active")
                ) {
                    $(this)
                        .parents("li")
                        .addClass("menu-open");
                    $(this)
                        .parents()
                        .addClass("active");
                    $(this).addClass("active"); //Add this too
                }
            }
        });

    $("body").on("click", ".add-more", function() {
        var boxParent = $(this)
            .parent(".card-body")
            .parent(".add-more-clone");
        var itemClone = boxParent
            .find(".row:first")
            .clone()
            .hide();
        itemClone.find("input, textarea").val("");
        itemClone.find(".note-editor").remove();
        // finally, remove the summernote element itself
        itemClone.find(".editor-basic").summernote({
            height: 200,
            toolbar: [
                // [groupName, [list of button]]
                ["style", ["bold", "italic", "underline"]]
            ]
        });
        $(this).before(itemClone);
        console.log(itemClone);
        itemClone.fadeIn();
        $(this)
            .parent(".add-more-clone")
            .find(".steps-label")
            .each(function(i) {
                $(this).attr("name", "steps[label][" + i + "]");
            });
        $(this)
            .parent(".add-more-clone")
            .find(".steps-content")
            .each(function(i) {
                $(this).attr("name", "steps[content][" + i + "]");
            });
    });

    // alert(222);
    $("body")
        .off("click", ".ajax-modal")
        .on("click", ".ajax-modal", function(e) {
            e.preventDefault();
            $(".overlay").show();
            var button = $(this);
            $("#ajaxModal").load($(this).attr("href"), function() {
                var open = $(this)
                    .find(".modal-dialog")
                    .attr("data-easein");
                $("#ajaxModal")
                    .off("show.bs.modal")
                    .on("show.bs.modal", function(l) {
                        if (open) $(".modal-dialog").velocity(open);
                    });
                $(button.attr("data-target")).modal("show");
                $(".overlay").hide();
                // $("#ajaxModal .hasDatePicker").each(function() {
                //     //if($(this).val()){
                //     $("#" + $(this).attr("id")).datepicker({
                //         autoclose: true,
                //         todayHighlight: true,
                //         dateFormat: "dd/mm/yy"
                //     });
                //     //}
                // });
                // $("#ajaxModal .chosen-select").each(function() {
                //     $(".chosen-select").chosen({
                //         disable_search_threshold: 2,
                //         no_results_text: "No similar items!"
                //     });
                // });
            });
        });
    //image preview
    function readURL(input, item) {
        if (input.files) {
            $.each(input.files, function(key, value) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (item.attr("multiple")) {
                        var img = $('<img class="img-preview-holder">');
                        img.attr("src", e.target.result);
                        img.insertAfter(item.parents(".input-group"));
                    } else {
                        item.parents(".form-group")
                            .find(".img-preview-holder")
                            .attr("src", e.target.result);
                    }
                };
                reader.readAsDataURL(value);
            });
        }
    }

    $("body").on("change", ".img-preview", function() {
        readURL(this, $(this));
    });
});
