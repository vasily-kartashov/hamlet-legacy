$(document).ready(function() {

    var reloadList = function() {
        $.ajax({
            url: '/items',
            type: 'GET',
            success: function(data) {
                var $list = $('ul');
                $list.empty();
                for (var i in data) {
                    var $edit = $('<a/>').append('edit').attr('href', '#').click(function() {
                        var $e = $(this);
                        var $span = $e.parent().find('span');
                        var value = $span.text();
                        var $editField = $('<input/>').val($span.text()).keyup(function(event) {
                            if (event.which == 13) {
                                var $e = $(this);
                                var id = $e.parent().attr('id');
                                $.ajax({
                                    url: '/items/' + id,
                                    type: 'PUT',
                                    data: {
                                        content: $e.val()
                                    },
                                    success: reloadList
                                });
                            } else if (event.which == 27) {
                                $editField.replaceWith($span);
                            }
                        });
                        $span.replaceWith($editField);
                        $editField.focus();
                        return false;
                    });
                    var $delete = $('<a/>').append('delete').attr('href', '#').click(function() {
                        var $e = $(this);
                        var id = $e.parent().attr('id');
                        $.ajax({
                            url: '/items/' + id,
                            type: 'DELETE',
                            success: reloadList
                        });
                        return false;
                    });
                    var $span = $('<span/>').append(data[i].content);
                    $list.append($('<li/>').attr('id', data[i].id).append($span).append($edit).append($delete));
                }
            }
        });
    }

    reloadList();

    $('#content').keyup(function(event) {
        if (event.which == 13 || event.which == 27) {
            var $e = $(this);
            if (event.which == 13) {
                $.ajax({
                    url: '/items',
                    type: 'POST',
                    data: {
                        content: $e.val()
                    },
                    success: reloadList
                });
            }
            $e.val('');
            return false;
        }
        return true;
    });
});