$(document).ready(function () { // To Keep Browser Tab Active
    function keepTabActive() {
        let hiddenElement = document.createElement('span');
        hiddenElement.innerHTML = '.';
        hiddenElement.style.opacity = '0';
        document.body.appendChild(hiddenElement);
        hiddenElement.remove(); // Forces the DOM to update, preventing sleep
    }

    setInterval(keepTabActive, 20000); // Trigger every 20 seconds
});


$(document).ready(function(){
    $('[title]').tooltip({html: true});
});


$(document).ready(function () {
    $('.select2[multiple]').each(function () {
        let selectElement = $(this);

        selectElement.select2({
            placeholder: "Select options",
            allowClear: true,
            closeOnSelect: false
        });

        selectElement.on('select2:select', function (e) {
            let selectedValue = e.params.data.id;
            let allOptions = selectElement.find('option[value!="selectAllValues"]').map(function () {
                return this.value;
            }).get();

            if (selectedValue === "selectAllValues") {
                // Select all valid options
                selectElement.val([...allOptions, "selectAllValues"]).trigger("change");
            } else {
                // Keep "Select All" selected if all options are selected
                let selectedValues = selectElement.val() || [];
                if (allOptions.every(val => selectedValues.includes(val))) {
                    selectElement.val([...selectedValues, "selectAllValues"]).trigger("change");
                }
            }
        });

        selectElement.on('select2:unselect', function (e) {
            let unselectedValue = e.params.data.id;
            let selectedValues = selectElement.val() || [];

            if (unselectedValue === "selectAllValues") {
                // Deselect all if "Select All" is unselected
                selectElement.val(null).trigger("change");
            } else {
                // Remove "Select All" if any individual option is deselected
                selectElement.val(selectedValues.filter(val => val !== "selectAllValues")).trigger("change");
            }
        });

        // Remove "selectAllValues" before form submission
        selectElement.closest("form").on("submit", function () {
            let selectedValues = selectElement.val() || [];
            selectElement.val(selectedValues.filter(val => val !== "selectAllValues"));
        });
    });
});
