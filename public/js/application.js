$(document).ready(function() {
    $.ajax({
        url: '/items',
        type: 'GET',
        success: function(data) {
            var $list = $('ul');
            for (var i in data) {
                $list.append($('<li/>').append(data[i]));
            }
        }
    })
});