$(document).ready(function() {
    // Function to populate submodules based on module ID
    function populateSubmodules(moduleId, selectedSubmoduleId) {
        var _token = $('meta[name="csrf-token"]').attr('content');
        let route = $('#module_id').attr('data-route');
        var submoduleDropdown = $('#submodule_id');

        $('#loading-spinner').show();

        // Make an AJAX request to fetch submodules
        $.ajax({
            url: route,
            method: 'POST',
            data: {
                _token: _token,
                module_id: moduleId
            },
            success: function(response) {
                // Clear and populate the submodule dropdown
                submoduleDropdown.empty().append('<option value="">-- Sila Pilih --</option>');

                $.each(response.submodules, function(key, value) {
                    if (value.data_status === 1) {
                        submoduleDropdown.append($('<option></option>').attr('value', value.id).text(value.submenu));
                    }
                });

                // Set the selected value for the Submodule dropdown
                submoduleDropdown.val(selectedSubmoduleId);

                $('#loading-spinner').hide();
            },
            error: function(xhr, status, error) {
                // Handle any errors that occur during the AJAX request
                console.error("AJAX Error:", error);

                $('#loading-spinner').hide();
            }
        });
    }

    // Event handler for module dropdown change
    $('#module_id').change(function() {
        var module_id = $(this).val();
        var selectedSubmoduleId = $('#submodule_id').val(); // Get the currently selected Submodule ID

        populateSubmodules(module_id, selectedSubmoduleId);
    });

    // Initialize submodule dropdown when the page loads
    var initialModuleId = $('#module_id').val();
    var initialSubmoduleId = $('#submodule_id').val(); // Get the initial selected Submodule ID
    populateSubmodules(initialModuleId, initialSubmoduleId);
});



