var totalRows = 0;

$(function () {
    var intType = $(".int-type");
    var extType = $(".ext-type");
    var btn = "<i class='material-icons' style='cursor: pointer;' title='Delete' onClick='del(this)'>delete</i>";
    var type;

    $("#extensiontype").on('change', function () {
        var type = $(this).val();
        if (type == "INTERNAL") {
            intType.show();
            extType.hide();
        } else {
            intType.hide();
            extType.show();
        }
    });


    $(".add-extension").bind("click", function () {
        var intval = $("#intvalue option:selected").html();
        var extval = $("#extvalue").val();
        var appendResult;

        type = $("#extensiontype").val();

        if (type == "INTERNAL" && $("#intvalue").val() != "") {
            appendResult = appendData(type, intval, 'individual');
        } else if (type == "EXTERNAL" && extval != "" && extval > 0) {
            appendResult = appendData(type, extval, 'individual');
        } else {
            return false;
        }

        if (appendResult) {
            $(".intvalue input").val("");
            $(".extvalue input").val("");
            $("#intvalue").val("");
        }
    });

    function appendData(type, val, method) {
        var combinedValue = type + "-" + val;
        var uniqueValue = true;
        var valueInTable;

        $("tbody").find('tr').each(function () {
            valueInTable = $(this).find('td:nth-child(2)').text() + "-" + $(this).find('td:nth-child(3)').text();

            if (combinedValue === valueInTable) {
                uniqueValue = false;
                return false;
            }
        });

        if (uniqueValue) {
            totalRows += 1;
            $("#dispaly").append("<tr> <td>" + btn + " </td> <td>" + type + "</td> <td>" + val + "</td> <td> " + totalRows + "</td></tr>");
            $("#no_record").hide();
            $("#extension_error").hide();
            return true;
        } else if (method === "individual") {
            alert(already_exits);
            return false;
        } else {
            return false;
        }
    }

    $("tbody").sortable({
        stop: function (event, ui) {
            $(this).find('tr').each(function (i) {
                $(this).find('td:last').text(i);
            });
        }
    });

    $(".add-all-extensions").click(function () {
        var internalData = $("#intvalue option").clone();

        internalData.each(function () {
            if ($(this).val()) {
                appendData("INTERNAL", $(this).text(), 'group');
            }
        })
    });

   /* $("#ring-group-form").submit(function (e) {*/
    $(document).on('click', '.submitfrom', function () {
        //e.preventDefault();
        var count = 0;
        $('#submittype').val($(this).val());
        var data = [];
        $("tbody").find('tr').each(function () {
            if (!$(this).find('td:nth-child(2)').text() && !$(this).find('td:nth-child(3)').text()) {
                return;
            }

            var obj = {
                rm_type: $(this).find('td:nth-child(2)').text(),
                rm_number: $(this).find('td:nth-child(3)').text(),
                rm_priority: $(this).find('td:nth-child(4)').text(),
            };
            data.push(obj);
        });

        if($('#ringgroup-rg_name').val() == ''){
            $(".field-ringgroup-rg_name").find('.help-block').html('Name cannot be blank.');
            count++;
        }

        if($('#ringgroup-rg_extension').val() == ''){
            $(".field-ringgroup-rg_extension").find('.help-block').html('Extension cannot be blank.');
            count++;
        }

        if($('#ringgroup-rg_type').val() == ''){
            $(".field-ringgroup-rg_type").find('.help-block').html('Type cannot be blank.');
            count++;
        }

        if($('#ringgroup-rg_info_prompt').val() == ''){
            $(".field-ringgroup-rg_info_prompt").find('.help-block').html('Information Prompt cannot be blank.');
            count++;
        }

        if($('#ringgroup-rg_language').val() == ''){
            $(".field-ringgroup-rg_language").find('.help-block').html('Language cannot be blank.');
            count++;
        }

        if($('#ringgroup-rg_timeout_sec').val() == ''){
            $(".field-ringgroup-rg_timeout_sec").find('.help-block').html('Timeout(Sec) cannot be blank.');
            count++;
        }

        if($('#ringgroup-rg_moh').val() == ''){
            $(".field-ringgroup-rg_moh").find('.help-block').html('MOH cannot be blank.');
            count++;
        }

        if (!data.length || $("#ringgroup-extension_list").val() === "[]") {
            setTimeout(function () {
                $("#extension_error").show();
            }, 100);
            count++;

        }
        if(count > 0) {
            return false;
        }else{
            $("#ringgroup-extension_list").val(JSON.stringify(data));
            //return true;
            //$(this).unbind('submit').submit();
            $("#ring-group-form").submit();
        }

    });

    if ($("#ringgroup-extension_list").val()) {
        var extensionList = JSON.parse($("#ringgroup-extension_list").val());

        $.each(extensionList, function () {
            var rmNumber = this.rm_number;
            var dropVal = '';
            if(this.rm_type == 'INTERNAL') {
                $('#intvalue option').each(function () {
                    if (this.text.split('-')[1] == rmNumber) {
                        dropVal = this.text;
                    }
                });
            }else{
                dropVal =  this.rm_number;
            }
            appendData(this.rm_type, dropVal, 'group');
        })
    }

    setTimeout(function () {
        $('#failshow').is(':checked') ? $(".hide1").show() : '';
    });
});

$('#failshow').change(function () {
    var optionSelected = $(this).is(':checked');
    if(optionSelected){
        $(".hide1").show(500);
    }else{
        $(".hide1").hide(500);
    }

});

function del(id) {
    if (!confirm(delete_confirm)) {
        return false;
    }

    var row = id.parentNode.parentNode;
    row.parentNode.removeChild(row);
    totalRows -= 1;
    if (totalRows !== 0) {
        $("tbody").find('tr').each(function (i) {
            $(this).find('td:last').text(i - 1);
        });
    } else {
        $("#no_record").find('td').text('No results found.');
        $("#no_record").show();
        return false;
    }
}
