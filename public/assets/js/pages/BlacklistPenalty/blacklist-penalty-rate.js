$(document).ready(function () {

    checkOperatorSelection();

    // Function to clone the row
    function cloneRow(row) {
        const clonedRow = row.clone();

        // Increment the numbering
        const newRowNumber = parseInt(row.find("td:first").text()) + 1;
        clonedRow.find("td:first").text(newRowNumber);

        // Increment the names and ids of the input fields
        clonedRow.find("input, select").each(function () {
            $(this).val("");
        //   const name = $(this).attr("name");
        //   const id = $(this).attr("id");

        //   const regex = /\[(\d+)\]\[(\d+)\]/;
        //   const match = name.match(regex);

        //   if (match) {
        //     const incrementedName = name.replace(regex, "[" + newRowNumber + "][" + (parseInt(match[2]) + 1) + "]");
        //     $(this).attr("name", incrementedName);

        //     if (id) {
        //       const incrementedId = id.replace(regex, "[" + newRowNumber + "][" + (parseInt(match[2]) + 1) + "]");
        //       $(this).attr("id", incrementedId);
        //     }
        //   }
        });

        return clonedRow;
    }

    // Add row button click event
    $("#duplicateRow").on("click", function () {
        const row = $("table tbody tr:last");
        const newRow = cloneRow(row);
        newRow.appendTo("table tbody");
    });

    $.fn.validateMonthRanges = function () {
        let isValid = true;
        let previousRangeTo = 0;

        this.find("tr").each(function () {
            const inputRangeFrom = $(this).find("input[name='range_from[]']");
            const inputRangeTo = $(this).find("input[name='range_to[]']");
            const errmsgSpan = $(this).find("span.range-error-msg");
            const rangeFrom = parseInt(inputRangeFrom.val());
            const rangeTo = parseInt(inputRangeTo.val());

            if(rangeFrom)
            {
                if (rangeFrom - 1 !== previousRangeTo) {
                    isValid = false;
                    errmsgSpan.toggleClass("d-none");
                    if(!inputRangeFrom.hasClass('is-invalid')) inputRangeFrom.toggleClass('is-invalid');
                    $('#btn-submit').prop('disabled', !isValid);
                    // console.log(errmsgSpan);
                    return false; // break the loop
                }
            }

            if(!errmsgSpan.hasClass('d-none')) errmsgSpan.toggleClass('d-none');
            if(inputRangeFrom.hasClass('is-invalid')) inputRangeFrom.toggleClass('is-invalid');
            $('#btn-submit').prop('disabled', !isValid);

            previousRangeTo = rangeTo;
        });

        return isValid;
    };

    $("table").on("change", "input[name='range_from[]'], input[name='range_to[]']", function () {
        const isValid = $("table tbody").validateMonthRanges();
    });

    // Event handler for btnRemoveCriteria click
    $("table").on("click", ".btnRemoveCriteria", function () {
        const rowCount = $("table tbody tr").length;

        // Check if there's more than one row, then remove the current row
        if (rowCount > 1)
        {
            $(this).closest("tr").remove();

            // Recalculate numbering
            $("table tbody tr").each(function (index) {
                $(this).find("td:first").text(index + 1);
            });

            //Recheck month range
            $("input[name='range_from[]").first().trigger('change');
        }
    });


    $(document).on("change", "select[name='operator_id[]']", function () {
        const value = $(this).val();
        const inputRangeTo = $(this).closest('tr').find("input[name='range_to[]']");
        const is_disabled = (value == 1);
        const is_required = (!is_disabled);

        inputRangeTo.prop('disabled', is_disabled);
        inputRangeTo.prop('required', is_required);
    });

    function checkOperatorSelection()
    {
        const tr = $('table').find('tbody tr');

        tr.each(function(){
            const operator_id = $(this).find("select option:selected").val();
            if(operator_id == 1)
            {
                const inputRangeTo = $(this).find('input[name="range_to[]"]');
                inputRangeTo.prop('required', false);
                inputRangeTo.prop('disabled', true);
            }
        });
    }
});
