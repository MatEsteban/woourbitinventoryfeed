jQuery(document).ready(function($) {
    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />'
        },
        fireSearch: function(value) {
            return value.length > 3;
        }
    });
    $(".select-tags-config, .select-collects-config, .input-stock-config").change(function() {
        var selectedTags = $(".select-tags-config").val();
        var selectedCollects = $(".select-collects-config").val();
        var stock = $(".input-stock-config").val();
        var result = $.map($("#search_to option"), function(option) {
            return option.value;
        });
        $.ajax({
            beforeSend: function () {
                $loading.show();
            },
            type: "POST",
            url: ajaxurl,
            data: {
                action:"filter_handler",
                tags: selectedTags,
                collects: selectedCollects,
                stock: stock,
                result: result
            }
        }).done(function( data ) {
            var json = JSON.stringify(data);
            var products = JSON.parse(json);
            console.log(products);
            var new_select = "";
            products['result'].forEach(function(item) {
                new_select += '<option value="' + item['product_id'] + '">' + item['product_id'] + ' - ' + item['title'] + '</option>';
            });
            $("#search").html(new_select);
            $loading.hide();
        });
    });
    var $loading = $('.loader').hide();
});