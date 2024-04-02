$(function() {
    const { jsPDF } = window.jspdf;

    async function exportToPDF(className){
        const doc = new jsPDF("p", "mm", "a4");
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = doc.internal.pageSize.getHeight();
        let Yaxis = 0;

        const logoUrl = $("#jata").data("logo-url");
        const logo64 = await getBase64ImageFromURL(logoUrl);
        const logoWidth = 30; // Set the desired width of the logo
        const logoHeight = 23; // Set the desired height of the logo
        const logoX = pdfWidth / 2 - logoWidth / 2; // Calculate the X position to center the logo
        Yaxis = Yaxis + 10;
      
        doc.addImage(logo64, 'PNG', logoX, Yaxis, logoWidth, logoHeight);

        Yaxis = Yaxis + logoHeight;

        const title1 = "Sistem Pengurusan Kuarters Kerajaan Negeri Johor";
        const title1X = pdfWidth / 2;
        Yaxis = Yaxis + 10;
        doc.setFontSize(12);
        doc.text(title1X, Yaxis, title1, { align: 'center' });

        var kuarters = ($('#kategori-kuarters').val() !== '') ? $("#kategori-kuarters option:selected").text() : '';

        const title2 = `Analisis Maklumat Kuarters ${kuarters}`;
        const title2X = pdfWidth / 2;
        Yaxis = Yaxis + 10;
        doc.setFontSize(12);
        doc.text(title2X, Yaxis, title2, { align: 'center' });

        const elements = document.getElementsByClassName(className);

        Yaxis = Yaxis + 10;

        doc.autoTable({ 
            html: '#my-table',
            startY: Yaxis,
            pageBreak: 'auto',
            didParseCell: (hookData) => {
                if (hookData.section === 'head' || hookData.section === 'body') {
                    //Center all except nama
                    if (hookData.column.dataKey != 1) {
                        hookData.cell.styles.halign = 'center';
                    }   
                }

                if(kuarters === '')
                {
                    if (hookData.section === 'body') {
                        const lastRowIndex = hookData.table.body.length - 1;

                        if (hookData.row.index === lastRowIndex) {
                            hookData.cell.styles.fontStyle = 'bold';
                            hookData.cell.styles.textColor = [255, 255, 255]; // White font color
                            hookData.cell.styles.fillColor = [116, 120, 141]; // Gray fill color
                        }

                        if (hookData.row.index === lastRowIndex && hookData.column.dataKey == 0) {
                            hookData.cell.styles.halign = 'right';
                        }
                    }
                }
            }
        });

        doc.save(title2 + `.pdf`);
    }

    async function addChart(doc, elements, pdfWidth, pdfHeight, top)
    {
        top = top + 10;

        const paddingLeft = 15;
        const paddingRight = 15;

        for (let i = 0; i < elements.length; i++) {
            const el = elements.item(i);

            const base64Image = myChart.toBase64Image();

            const elWidth = el.offsetWidth;
            const elHeight = el.offsetHeight;
            const aspectRatio = elWidth / elHeight;

            let newImgWidth = elWidth;
            let newImgHeight = newImgWidth / aspectRatio;

            if (newImgWidth > pdfWidth - (paddingLeft + paddingRight)) {
                newImgWidth = pdfWidth - (paddingLeft + paddingRight);
                newImgHeight = newImgWidth / aspectRatio;
            }
            const centerX = pdfWidth / 2 - newImgWidth / 2; // Calculate the X position to center

            doc.addImage(base64Image, 'PNG', centerX, top, newImgWidth, newImgHeight);
            
            top += newImgHeight;
        }

        return {top:top}
    }

    function getBase64ImageFromURL(url) {
        return new Promise((resolve, reject) => {
          const img = new Image();
          img.setAttribute('crossOrigin', 'anonymous');
          img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            const dataURL = canvas.toDataURL('image/png');
            resolve(dataURL);
          };
          img.onerror = error => {
            reject(error);
          };
          img.src = url;
        });
    };

    $("#download-pdf").click(function(event)
    {
        event.preventDefault(); 

        exportToPDF("chart");
    });

    setVacancySelection($('#status-kuarters'));

    $(document).on('change', '#status-kuarters', function(){
        setVacancySelection($(this));
    });

    function setVacancySelection(elem)
    {
        const condition = elem.val();
        const is_disabled = (condition != 1);

        $('#kekosongan').prop('disabled', is_disabled);
        $('#kekosongan option:first').prop('selected', is_disabled);
    }
    
    $(document).on('click', '#reset', function(){
        const url = $('#url-reset').data('url');
        window.location.href = url;
    });
});



