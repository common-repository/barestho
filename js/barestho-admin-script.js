document.addEventListener('DOMContentLoaded', function() {
    jQuery('#barestho_theme_color').wpColorPicker();
    jQuery('.barestho-color-span').css('background-color', barestho.themeColor);

    var checkboxPopup = document.getElementById('barestho_popup_widget');
    var labelPopup = document.getElementById('barestho_popup_widget_label');

    if (checkboxPopup && labelPopup) {
        function updatePopupLabelText() {
            var statusText = checkboxPopup.checked ? barestho.disableText : barestho.enableText;
            labelPopup.textContent = statusText;
        }

        checkboxPopup.addEventListener('change', function() {
            updatePopupLabelText();
        });

        updatePopupLabelText(); 
    }

    var checkboxReservation = document.getElementById('barestho_reservation_button');
    var labelReservation = document.getElementById('barestho_reservation_button_label');

    if (checkboxReservation && labelReservation) {
        function updateReservationLabelText() {
            var statusText = checkboxReservation.checked ? barestho.disableText : barestho.enableText;
            labelReservation.textContent = statusText; 
        }

        checkboxReservation.addEventListener('change', function() {
            updateReservationLabelText();
        });

        updateReservationLabelText(); 
    }

    var checkboxCustom = document.getElementById('barestho_custom_widget');
    var labelCustom = document.getElementById('barestho_custom_widget_label');

    if (checkboxCustom && labelCustom) {
        function updateCustomLabelText() {
            var statusText = checkboxCustom.checked ? barestho.disableText : barestho.enableText;
            labelCustom.textContent = statusText; 
        }

        checkboxCustom.addEventListener('change', function() {
            updateCustomLabelText();
        });

        updateCustomLabelText(); 
    }
});