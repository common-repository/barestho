jQuery(document).ready(function($) {
    $('.wp-color-picker-field').wpColorPicker();
});

jQuery(document).ready(function($) {
    
    function updateSpanBackgroundColor(color) {
        $('.barestho-color-span').css('background-color', color);
    }

    
    $('#barestho_theme_color').wpColorPicker({
        
        change: function(event, ui) {
            var selectedColor = ui.color ? ui.color.toString() : $('#barestho_theme_color').attr('data-default-color');
            updateSpanBackgroundColor(selectedColor);
        }
    });

    
    var defaultColor = $('#barestho_theme_color').attr('data-default-color');
    updateSpanBackgroundColor(defaultColor);
});
