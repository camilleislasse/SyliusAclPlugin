$(function () {
    $('#bitbag-permissions-tree').jstree({
        'core': {
            'expand_selected_onload': false
        },
        'checkbox': {
            "keep_selected_style": false
        },
        'plugins': ['checkbox']
    });

    $('#bitbag-permissions-tree').on('changed.jstree', function (e, data) {
        $('#bitbag-permissions-checkboxes input[type="checkbox"]').attr('checked', false);

        var ids = '';

        jQuery.each(data.selected, function(key, selected) {
            ids += '#' + selected;

            if (key !== (data.selected.length - 1)) {
                ids += ', ';
            }
        });

        $(ids).attr('checked', true);
    });

    $('.ui.dropdown.link.button').each(function(index) {
        var linksHidden = 0;
        var links = 0;

        $(this).find('a.item').each(function (index) {
            if ('ACCESS_DENIED' === $(this).attr('href')) {
                linksHidden++;
            }

            links++;
        });

        if (linksHidden === links) {
            $(this).hide();
        }
    });
});
