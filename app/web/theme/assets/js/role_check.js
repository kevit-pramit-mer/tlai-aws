$(document).ready(function () {
    selectAllText();
});

var selectAllText = function () {
    var nbCbs = $('table').find('.all-checkbox').has('input[type=checkbox]:enabled').length; //the number of checkboxes
    var nbChecked = $('table').find('.all-checkbox').has('input[type=checkbox]:checked').length;

    checkAllOnLoad('create_check', 'create_all');
    checkAllOnLoad('update_check', 'update_all');
    checkAllOnLoad('delete_check', 'delete_all');
    checkAllOnLoad('view_checkbox', 'view_all');

    if (nbCbs == nbChecked) {
        //$('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;Unselect All');

        $('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;'+custom_unselect_all+'');
    } else {
        //$('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;Select All');

        $('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;'+custom_select_all+'');
    }

    if(nbChecked == 0){
        $('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;'+custom_select_all+'');
    }
};
$(document).on('click', '#assign_all', function (e) {
    var tx = $('#assign_all').html();
    //var str2 = '<i class=\"fa fa-check m-right-xs\"></i>&nbsp;Select All';

    var str2 = '<i class=\"fa fa-check m-right-xs\"></i>&nbsp;'+custom_select_all+'';


    if(tx.indexOf(str2) != -1){
        $('input[type=checkbox]:enabled').prop('checked', true);
        //$('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;Unselect All');

        $('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp; '+custom_unselect_all+'');
    } else {
        $('input[type=checkbox]').prop('checked', false);
        //$('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;Select All');

        $('#assign_all').html('<i class=\"fa fa-check m-right-xs\"></i>&nbsp;'+custom_select_all+'');
    }
});

var checkAllActionWise = function (cls, check) {
    $('table').find('.' + cls).has('input[type=checkbox]:enabled').each(function () {
        $(this).find('input[type=checkbox]').prop('checked', check);
        var checkedCnt = $(this).closest('.a_row').find('.action_checkbox').has('input[type=checkbox]:checked').length;
        if (checkedCnt > 0) {
            $(this).closest('.a_row').find('.view_check').closest('.view_check').prop('checked', true);
            checkUncheckOnCnt($(this).closest('.a_row').find('.view_check'));
        }
    });
    selectAllText();
};

var checkAllOnLoad = function (cls, chid) {
    var nbCbs = $('table').find('.' + cls).has('input[type=checkbox]:enabled').length; //the number of checkboxes
    var nbChecked = $('table').find('.' + cls).has('input[type=checkbox]:checked').length;

    if (nbCbs == nbChecked) {
        $('table').find('#' + chid).prop('checked', true);
    }
};

var checkUncheckOnCnt = function (obj) {
    var cls = '';
    var chid = '';
    if (obj.parent().hasClass('create_check')) {
        cls = 'create_check';
        chid = 'create_all';
    }
    if (obj.parent().hasClass('update_check')) {
        cls = 'update_check';
        chid = 'update_all';
    }
    if (obj.parent().hasClass('delete_check')) {
        cls = 'delete_check';
        chid = 'delete_all';
    }
    if (obj.parent().hasClass('view_checkbox')) {
        cls = 'view_checkbox';
        chid = 'view_all';
    }

    var nbCbs = $('table').find('.' + cls).has('input[type=checkbox]:enabled').length; //the number of checkboxes
    var nbChecked = $('table').find('.' + cls).has('input[type=checkbox]:checked').length;

    if (nbCbs == nbChecked) {
        $('table').find('#' + chid).prop('checked', true);
    } else {
        $('table').find('#' + chid).prop('checked', false);
    }
}

// Create All
$(document).on('click', '#create_all', function (e) {
    checkAllActionWise('create_check', this.checked);
});

// Update All
$(document).on('click', '#update_all', function (e) {
    checkAllActionWise('update_check', this.checked);
});

// Delete All
$(document).on('click', '#delete_all', function (e) {
    checkAllActionWise('delete_check', this.checked);
});

// View All
$(document).on('click', '#view_all', function (e) {
    var check = this.checked;
    $('table').find('.view_checkbox').has('input[type=checkbox]:enabled').each(function () {
        $(this).find('input[type=checkbox]').prop('checked', check);
    });
    if (!check) {
        $('input[type=checkbox]').prop('checked', false);
    }
    selectAllText();
});

// single action(create | update | delete) checkbox click
$(document).on('click', '.action_check', function (e) {
    if (this.checked) {
        $(this).prop('checked', true);
    } else {
        $(this).prop('checked', false);
    }

    checkUncheckOnCnt($(this));

    var checkedCnt = $(this).closest('.a_row').find('.action_checkbox').has('input[type=checkbox]:checked').length;
    if (checkedCnt > 0) {
        $(this).closest('.a_row').find('.view_check').closest('.view_check').prop('checked', true);
        checkUncheckOnCnt($(this).closest('.a_row').find('.view_check'));
    }
    selectAllText();
});

// single view check box click
$(document).on('click', '.view_check', function (e) {
    checkUncheckOnCnt($(this));
    if (!this.checked) {
        $(this).closest('.a_row').find('.action_check').prop('checked', false);
        $(this).closest('.a_row').find('.action_check').each(function () {
            checkUncheckOnCnt($(this));
        });
    }
    selectAllText();
});


function AssignPageAccess() {
    var keys = [];
    $('table').find('tbody').find("input[type=checkbox]:checked").each(function (i, v) {
        var id = $(this).attr("id");
        var array = id.split("_");
        array.push($(this).attr('data_module'));
        array.push($(this).attr('data_name'));
        array.push($(this).attr('data_priority'));
        keys[i] = array;
    });
    var isAssign = (keys.length > 0 ? 'Y' : 'N');
    $.ajax({
        url: window.rpath,
        data: {data: keys, 'isAssign': isAssign},
        method: 'post',
        success: function (response) {
            if (response.trim() == 'success') {
                window.location.reload();
            }
        }
    });
}
