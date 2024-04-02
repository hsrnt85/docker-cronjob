$(function () {
    ajaxGetCategories();

    if ($('input[name=report_type]:checked').val() !== undefined) {
        let ic_value = ($('#ic').val().length > 0);

        filterSearchInput();
        addAdditionalValidation($('input[name=report_type]:checked').val());
        checkIcKeyin($('input[name=report_type]:checked').val(), ic_value);
    }

    $(".input-mask").inputmask();

    function enabledAndClearAllInputs() {
        $("input, select").prop('disabled', false);
        $("input:not([type=radio]), select:not([name='kategori_laporan'])").val("");
    }

    function addAdditionalValidation(value) {
        $(".custom-validation").parsley().reset();

        if (value == 5) {
            window.Parsley.addValidator('reportPenamaDitawarkan', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#offer-status').val();
                var select4Value = $('#services_type').val();
                var select5Value = $('#date-from').val();
                var select6Value = $('#date-to').val();

                if (select1Value || select2Value || select3Value || select4Value || select5Value || select6Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportPenamaDitawarkan')) {
                window.Parsley.removeValidator('reportPenamaDitawarkan');
            }
        }
        if (value == 6) {
            window.Parsley.addValidator('reportSenaraiPermohonan', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#applicant-status').val();
                var select3Value = $('#services_type').val();
                var select4Value = $('#date-from').val();
                var select5Value = $('#date-to').val();

                if (select1Value || select2Value || select3Value || select4Value || select5Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportSenaraiPermohonan')) {
                window.Parsley.removeValidator('reportSenaraiPermohonan');
            }
        }
        if (value == 8) {
            window.Parsley.addValidator('reportPenghuni', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#services_type').val();
                var select4Value = $('#date-from').val();
                var select5Value = $('#date-to').val();

                if (select1Value || select2Value || select3Value || select4Value || select5Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportPenghuni')) {
                window.Parsley.removeValidator('reportPenghuni');
            }
        }

        if (value == 10) {
            window.Parsley.addValidator('reportPemarkahanIc', function (value, requirement) {

                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#services_type').val();

                if (select1Value || select2Value || select3Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportPemarkahanIc')) {
                window.Parsley.removeValidator('reportPemarkahanIc');
            }
        }

        if (value == 11) {
            window.Parsley.addValidator('reportSejarahPemohon', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#applicant-status').val();
                var select3Value = $('#year').val();

                if (select1Value || select2Value || select3Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportSejarahPemohon')) {
                window.Parsley.removeValidator('reportSejarahPemohon');
            }
        }

        if (value == 12) {
            window.Parsley.addValidator('reportSejarahPenghuni', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#services_type').val();
                var select3Value = $('#year').val();

                if (select1Value || select2Value || select3Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportSejarahPenghuni')) {
                window.Parsley.removeValidator('reportSejarahPenghuni');
            }
        }

        if (value == 13) {
            window.Parsley.addValidator('reportBayaranPenghuni', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#account_type').val();
                var select4Value = $('#year').val();

                if (select1Value || select2Value || select3Value || select4Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportBayaranPenghuni')) {
                window.Parsley.removeValidator('reportBayaranPenghuni');
            }
        }

        if (value == 14) {
            window.Parsley.addValidator('reportHilangKelayakanPenghuni', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#date-from').val();
                var select4Value = $('#date-to').val();

                if (select1Value || select2Value || select3Value || select4Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportHilangKelayakanPenghuni')) {
                window.Parsley.removeValidator('reportHilangKelayakanPenghuni');
            }
        }

        if (value == 15) {
            window.Parsley.addValidator('reportPenghuniDijangkaBersara', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#date-from').val();
                var select3Value = $('#date-to').val();

                if (select1Value || select2Value || select3Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportPenghuniDijangkaBersara')) {
                window.Parsley.removeValidator('reportPenghuniDijangkaBersara');
            }
        }

        if (value == 16) {
            window.Parsley.addValidator('reportMesyuarat', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#meeting-result').val();
                var select4Value = $('#date-from').val();
                var select5Value = $('#date-to').val();

                if (select1Value || select2Value || select3Value || select4Value || select5Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportMesyuarat')) {
                window.Parsley.removeValidator('reportMesyuarat');
            }
        }

        if (value == 17) {
            window.Parsley.addValidator('reportBayaranTerperinciPenghuni', function (value, requirement) {
                var select1Value = $('#district').val();
                var select2Value = $('#quarters-category').val();
                var select3Value = $('#agency').val();
                var select4Value = $('#year').val();
                var select5Value = $('#month').val();

                if (select1Value || select2Value || select3Value || select4Value || select5Value) return true;

                if (value.trim() === '') return false;

                return true;
            }, 32);
        }else{
            // Remove validators only if they haven't been removed already
            if (window.Parsley.hasValidator('reportBayaranTerperinciPenghuni')) {
                window.Parsley.removeValidator('reportBayaranTerperinciPenghuni');
            }
        }
    }


    $(document).on('keyup', '#ic', function () {
        let is_value = ($(this).val().length > 0);
        let report_type = $('input[name=report_type]:checked').val();
        checkIcKeyin(report_type, is_value);
    });

    function checkIcKeyin(report_type, is_value) {
        if (report_type == 5) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#offer_status').prop('disabled', is_value);
            $('#services_type').prop('disabled', is_value);
            $('#date-from').prop('disabled', is_value);
            $('#date-to').prop('disabled', is_value);
        }
        if (report_type == 6) {
            $('#district').prop('disabled', is_value);
            $('#applicant_status').prop('disabled', is_value);
            $('#services_type').prop('disabled', is_value);
            $('#date-from').prop('disabled', is_value);
            $('#date-to').prop('disabled', is_value);
        }
        if (report_type == 8) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#services_type').prop('disabled', is_value);
            $('#date-from').prop('disabled', is_value);
            $('#date-to').prop('disabled', is_value);
        }
        if (report_type == 10) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#services_type').prop('disabled', is_value);
        }

        if (report_type == 11) {
            $('#district').prop('disabled', is_value);
            $('#applicant-status').prop('disabled', is_value);
            $('#year').prop('disabled', is_value);
        }

        if (report_type == 12) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#services_type').prop('disabled', is_value);
            $('#year').prop('disabled', is_value);
        }

        if (report_type == 13) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#account_type').prop('disabled', is_value);
            $('#year').prop('disabled', is_value);
        }

        if (report_type == 14) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#date-from').prop('disabled', is_value);
            $('#date-to').prop('disabled', is_value);
        }

        if (report_type == 15) {
            $('#district').prop('disabled', is_value);
            $('#date-from').prop('disabled', is_value);
            $('#date-to').prop('disabled', is_value);
        }

        if (report_type == 16) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#meeting_result').prop('disabled', is_value);
            $('#date-from').prop('disabled', is_value);
            $('#date-to').prop('disabled', is_value);
        }

        if (report_type == 17) {
            $('#district').prop('disabled', is_value);
            $('#quarters-category').prop('disabled', is_value);
            $('#agency').prop('disabled', is_value);
            $('#year').prop('disabled', is_value);
            $('#month').prop('disabled', is_value);
        }
    }

    $(document).on('change', '#kategori-laporan', function () {
        const currentUrl = window.location.href;

        // Remove the URL parameters
        const urlWithoutParams = currentUrl.split('?')[0];

        // Add new URL parameters
        const newParams = new URLSearchParams();
        newParams.append('category', this.value);

        // Create the new URL with the updated parameters
        const newUrl = `${urlWithoutParams}?${newParams.toString()}`;

        // Redirect to the new URL
        window.location.href = newUrl;
    });

    // Event listener for district select change
    $(document).on("change", '#district, #grade', function () {
        // populateCategories();
        ajaxGetCategories();
    });

    function populateCategories() {
        const json = $("#category-data").data('data');
        // const $districtSelect = $("#district");
        const $townSelect = $("#quarters-category");
        const selectedDistrict = $('#district').val();

        const selectedCategory = ($('#selected-quarters-category').val() === '') ? null : $('#selected-quarters-category').val();
        // Clear previous options in town select
        $townSelect.empty().append("<option value=''>  -- Pilih Lokasi --  </option>");

        // If a district is selected, populate town options
        if (selectedDistrict) {
            const towns = json[selectedDistrict];

            // Generate options based on the towns in the selected district
            $.each(towns, function (index, value) {
                const option = $("<option>").val(value.id).text(value.name);
                if (value.id == selectedCategory) option.attr('selected', 'selected');
                $townSelect.append(option);
            });
        }
    }

    function ajaxGetCategories() {

        const _token = $('meta[name="csrf-token"]').attr('content');
        const url = $('#url-categories').data('url');
        const districtValue = $('select[name=district]').val();
        const gradeOption = $('select[name=gradeOption]').val();
        const landed_type = $('select[name=landed_type]').val();

        // Create data object
        const data = {
            _token: _token,
            district: districtValue,
            gradeOption: gradeOption,
            landed_type: landed_type,
        };

        $.ajax({
            url: url,
            method: 'POST',
            data: data
        })
            .done(function (response) {
                const data = response.data;
                const $select = $("#quarters-category");
                const previousSelectedCategory = ($('#selected-quarters-category').val() === '') ? null : $('#selected-quarters-category').val();

                // Clear previous options
                $select.empty().append("<option value=''>  -- Kategori Kuarters (Lokasi) --  </option>");

                $.each(data, function (index, value) {
                    const option = $("<option>").val(value.id).text(value.name);
                    if (value.id == previousSelectedCategory) option.attr('selected', 'selected');
                    $select.append(option);
                });
            });
    }

    $('input[name=report_type]').on('click', function () {
        enabledAndClearAllInputs()
        filterSearchInput();
        addAdditionalValidation(this.value);
    });

    function filterSearchInput() {
        // Get CSRF token
        const _token = $('meta[name="csrf-token"]').attr('content');
        const url = $('#url-report').data('url');

        // Get radio button value
        const radioValue = $('input[name=report_type]:checked').val();

        // Create data object
        const data = {
            _token: _token,
            name: radioValue
        };

        // Make AJAX POST request
        $.ajax({
            url: url,    // Change to your URL
            method: 'POST',
            data: data
        })
            .done(function (response) {
                // Success response
                let data = response.data;
                $('#district').closest('div.col-md-3').prop('hidden', !(data.flag_district));
                $('#quarters-category').closest('div.col-md-3').prop('hidden', !(data.flag_quarters_category));
                $('#landed-type').closest('div.col-md-3').prop('hidden', !(data.flag_landed_type));
                $('#services_type').closest('div.col-md-3').prop('hidden', !(data.flag_services_type));
                $('#grade').closest('div.col-md-3').prop('hidden', !(data.flag_position_grade));
                $('#applicant-status').closest('div.col-md-3').prop('hidden', !(data.flag_status_pemohon));
                $('#offer-status').closest('div.col-md-3').prop('hidden', !(data.flag_status_tawaran));
                $('#tenant-status').closest('div.col-md-3').prop('hidden', !(data.flag_status_penghuni));
                $('#condition').closest('div.col-md-3').prop('hidden', !(data.flag_quarters_condition));
                $('#vacancy').closest('div.col-md-3').prop('hidden', !(data.flag_quarters_vacancy));
                $('#eligibility').closest('div.col-md-3').prop('hidden', !(data.flag_quarters_eligibility));
                $('#account_type').closest('div.col-md-3').prop('hidden', !(data.flag_account_type));
                $('#agency').closest('div.col-md-12').prop('hidden', !(data.flag_agency));
                $('#meeting-result').closest('div.col-md-3').prop('hidden', !(data.flag_meeting_result));
                $('#ic').closest('div.col-md-3').prop('hidden', !(data.flag_ic));
                $('#year').closest('div.col-md-3').prop('hidden', !(data.flag_tahun));
                $('#month').closest('div.col-md-3').prop('hidden', !(data.flag_bulan));
                $('#date-to').closest('div.col-md-3').prop('hidden', !(data.flag_to));
                $('#date-from').closest('div.col-md-3').prop('hidden', !(data.flag_from));

                $('#district').prop('required', determineRequiredProperty(data.flag_district));
                $('#quarters-category').prop('required', determineRequiredProperty(data.flag_quarters_category));
                $('#landed-type').prop('required', determineRequiredProperty(data.flag_landed_type));
                $('#services_type').prop('required', determineRequiredProperty(data.flag_services_type));
                $('#grade').prop('required', determineRequiredProperty(data.flag_position_grade));
                // $('#applicant-status').prop('required', data.flag_status_pemohon);
                // $('#tenant-status').prop('required', data.flag_status_penghuni);
                $('#condition').prop('required', determineRequiredProperty(data.flag_quarters_condition));
                $('#vacancy').prop('required', determineRequiredProperty(data.flag_quarters_vacancy));
                $('#eligibility').prop('required', determineRequiredProperty(data.flag_quarters_eligibility));
                $('#account_type').prop('required', determineRequiredProperty(data.flag_account_type));
                $('#agency').prop('required', determineRequiredProperty(data.flag_agency));
                $('#meeting-result').prop('required', determineRequiredProperty(data.flag_meeting_result));
                $('#ic').prop('required', determineRequiredProperty(data.flag_ic));
                $('#year').prop('required', determineRequiredProperty(data.flag_tahun));
                $('#month').prop('required', determineRequiredProperty(data.flag_bulan));
                $('#date-to').prop('required', determineRequiredProperty(data.flag_to));
                $('#date-from').prop('required', determineRequiredProperty(data.flag_from));
            })
            .fail(function (jqXHR, textStatus, error) {
                // Fail response
                console.log('Error:', textStatus, error);
            });
    }

    function determineRequiredProperty(flag) {
        return flag != 2 && Boolean(flag);
    }

    // $(document).on('click', '#download-pdf', function (e) {
    //     e.preventDefault();
    //     // exportToPDF("chart");
    //     generatePDFContent()
    // });


    const map = {
        'BAIK': {
            'Berpenghuni': ['Tidak Boleh Ditawarkan'],
            'Kosong': ['Boleh Ditawarkan']
        },
        'SEDANG DISELENGGARA': {
            'Kosong': ['Tidak Boleh Ditawarkan']
        },
        'ROSAK': {
            'Kosong': ['Tidak Boleh Ditawarkan']
        }
    };

    // $(document).on("change", '#condition, #vacancy, #eligibility', function () {
    //     const currSelect = $(this);
    //     const prevSelect = currSelect.closest('div.select-container').prev('div.select-container').find('select');
    //     updateOptions(currSelect, prevSelect, map);
    //     unSelectDisabledOptions();
    //     enabledDisabledNextSelect(currSelect);
    // });

    function enabledDisabledNextSelect(currSelect) {
        const currSelectValue = currSelect.val();
        const nextSelect = currSelect.closest('div.select-container').next('div.select-container').find('select');

        if (nextSelect.length > 0) {
            // Set the value of the next select to the first option value
            nextSelect.val(nextSelect.find('option:first').val());
            nextSelect.prop('disabled', (!currSelectValue));
            enabledDisabledNextSelect(nextSelect);
        }
    }

    function updateOptions(currSelect, prevSelect, map) {
        const currSelectValue = currSelect.val();
        const prevSelectValue = prevSelect.val();
        const nextSelect = currSelect.closest('div.select-container').next('div.select-container').find('select');
        const options = nextSelect.find('option');

        // Get the options based on the selected values
        const availableOptions = traversingMap(currSelectValue, prevSelectValue, map);

        if (availableOptions) {
            $(options).each(function (index, option) {
                option = $(option);
                let optionValue = option.val();

                if (optionValue) {
                    option.prop('disabled', !availableOptions.includes(optionValue));
                }
            });
        }
    }

    function traversingMap(currValue, prevValue, map) {
        let result = [];

        for (let condition in map) {
            if (condition == currValue) {
                result.push(Object.keys(map[condition])); // Vacancy value
            }

            for (let vacancy in map[condition]) {
                if (vacancy == currValue && (!prevValue || prevValue == condition)) {
                    result.push(map[condition][vacancy]); // Eligibility value
                }
            }
        }

        result = $.map(result, function (n) {
            return n;
        });

        return result;
    }

    function traversing(currValue, prevValue, map) {
        let result = [];

        for (let key in map) {

            if (key == currValue) {
                result.push(key);
            }

            for (vacancy in map[key]) {

                if (vacancy == currValue || key == currValue) {
                    result.push(vacancy);
                    result.push(key);
                }

                if (map[key][vacancy][0] == currValue || vacancy == currValue || key == currValue) {
                    result.push(map[key][vacancy][0]);
                    result.push(vacancy);
                    result.push(key);
                }
            }
        }

        return [...new Set(result)];
    }

    function unSelectDisabledOptions() {
        let selectAll = $('select');

        selectAll.each(function (index, select) {
            select = $(select);

            // Get currently selected option
            const selected = select.find(":selected");

            // If selected is disabled
            if (selected.prop("disabled")) {

                // Get first enabled option
                const firstEnabled = select.find("option:enabled")[0];

                // Change selected to first enabled
                select.val(firstEnabled.value);
            }
        });
    }


    // const { jsPDF } = window.jspdf;

    // async function exportToPDF(className) {

    // $('#download_pdf').onclick = function (event) {
    //     if (event.target.id == "download-pdf") {
    //         generatePDFContent();
    //     }
    // }

    // $(document).on("click", "#generate_pdf", function(e){

    // function  generatePDFHeader() {

    // (async () => {
    //     try {
    //     // Load the header HTML
    //     const headerHtml = await $.ajax({ url: '/LaporanDinamik/generatePdf', dataType: 'html' });

    //     const doc = new jsPDF("landscape", "mm", "a4");
    //     const pdfWidth = doc.internal.pageSize.getWidth();
    //     const pdfHeight = doc.internal.pageSize.getHeight();
    //     let Yaxis = 0;

    //      // Add header to PDF
    //      doc.fromHTML(headerHtml, 10, Yaxis);
    //      Yaxis += 50; // Adjust the Y position after adding header

    //     const logoUrl = $("#jata").data("logo-url");
    //     const logo64 = await getBase64ImageFromURL(logoUrl);
    //     const logoWidth = 30; // Set the desired width of the logo
    //     const logoHeight = 23; // Set the desired height of the logo
    //     const logoX = pdfWidth / 2 - logoWidth / 2; // Calculate the X position to center the logo
    //     Yaxis = Yaxis + 10;

    //     doc.addImage(logo64, 'PNG', logoX, Yaxis, logoWidth, logoHeight);

    //     Yaxis = Yaxis + logoHeight;

    //     const title1 = "Sistem Pengurusan Kuarters Kerajaan Negeri Johor";
    //     const title1X = pdfWidth / 2;
    //     Yaxis = Yaxis + 10;
    //     doc.setFontSize(12);
    //     doc.text(title1X, Yaxis, title1, { align: 'center' });

    //     const title2 = $('#title-laporan').text();
    //     const title2X = pdfWidth / 2;
    //     Yaxis = Yaxis + 10;
    //     doc.setFontSize(12);
    //     doc.text(title2X, Yaxis, title2, { align: 'center' });

    //     const subTitles = ['#daerah-pdf', '#kategori-pdf', '#taraf-pdf', '#tahun-pdf', '#tarikh-pdf'];
    //     subTitles.forEach(function (element) {
    //         if ($(element).length) {
    //             var subTitle = ($(element).length) ? $(element).text() : '';
    //             const subTitleX = 15;
    //             Yaxis = Yaxis + 5;
    //             doc.setFontSize(8);
    //             doc.text(subTitleX, Yaxis, subTitle, { align: 'left' });
    //         }
    //     });

    //     Yaxis = Yaxis + 5;
    //     doc.setFontSize(8);

    //     var dontCenterKey = [];

    //     doc.autoTable({
    //         html: '#my-table',
    //         startY: Yaxis,
    //         pageBreak: 'auto',
    //         styles: {
    //             fontSize: 8
    //         },
    //         didParseCell: (hookData) => {

    //             if (hookData.section === 'head') {
    //                 if (['Catatan', 'Pilihan Kuarters'].includes(hookData.cell.text.map(String).join(''))) {
    //                     dontCenterKey.push(hookData.column.dataKey);
    //                 }

    //                 //Center all header
    //                 hookData.cell.styles.halign = 'center';
    //             }

    //             if (hookData.section === 'body') {
    //                 // Center except ...
    //                 if (!dontCenterKey.includes(hookData.column.dataKey)) {
    //                     hookData.cell.styles.halign = 'center';
    //                 } else {
    //                     let formattedList = hookData.cell.text.filter(item => item.trim() !== '').map(item => '• ' + item.trim());
    //                     hookData.cell.text = formattedList;
    //                 }
    //             }
    //         },
    //     });

    //     return doc.output();


    // } catch (error) {
    //     console.error('Error fetching or processing image:', error);
    // }

    // })();

    // function getBase64ImageFromURL(url) {
    //     return new Promise((resolve, reject) => {
    //         const img = new Image();
    //         img.setAttribute('crossOrigin', 'anonymous');
    //         img.onload = () => {
    //             const canvas = document.createElement('canvas');
    //             canvas.width = img.width;
    //             canvas.height = img.height;
    //             const ctx = canvas.getContext('2d');
    //             ctx.drawImage(img, 0, 0);
    //             const dataURL = canvas.toDataURL('image/png');
    //             resolve(dataURL);
    //         };
    //         img.onerror = error => {
    //             reject(error);
    //         };
    //         img.src = url;
    //     });
    // };

    //-------------------------------------------------------------------------------------
    //CUSTOM CSS TO GENERATE PDF
    //-------------------------------------------------------------------------------------



        // $(document).on('click', '#muat_turun_pdf', function (e) { alert('hei');
        //     // e.preventDefault();
        //     var myTable = document.getElementById('my-table');
        //     // Add the "info_content_border" class to the table
        //     myTable.classList.add('info_content_border');
        //     // Get the tr element by its class name
        //     var trElement = document.querySelector('.table_row');

        //     trElement.classList.add('info_content_border');

        //  });




});


// });
