$(function() {
    const { jsPDF } = window.jspdf;
    const chartData = $('#chartData').data('chart-data');
    let animationCompleted = false;

    const ctx = document.getElementById('myChart').getContext('2d');
    
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.data,
                backgroundColor: chartData.bg
            }]
        },
        options: {
            animation: {
                onComplete: function() {
                    animationCompleted = true;
                }
            },
            plugins: {
                labels: 'value',
                datalabels: {
                    color: 'white',
                    font: {
                        weight: 'bold',
                        size: 16
                    },
                    formatter: function(value, index, values) {
                        return (value > 0) ? value : '';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

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

        const start = moment($('#carian_tarikh_aduan_dari').val() ).format('DD/MM/YYYY');
        const end = moment($('#carian_tarikh_aduan_hingga').val() ).format('DD/MM/YYYY');
        const title2 = `Analisis Aduan Awam Kuarters dari ${start} sehingga ${end}`;
        const title2X = pdfWidth / 2;
        Yaxis = Yaxis + 10;
        doc.setFontSize(12);
        doc.text(title2X, Yaxis, title2, { align: 'center' });

        const elements = document.getElementsByClassName(className);

        const { top } = await addChart(doc, elements, pdfWidth, pdfHeight, Yaxis);
        Yaxis = Yaxis + top;

        doc.autoTable({ 
            html: '#my-table',
            startY: Yaxis - 30,
            pageBreak: 'auto',
            didParseCell: (hookData) => {
                if (hookData.section === 'head' || hookData.section === 'body') {
                    //Center all except nama
                    if (hookData.column.dataKey != 1) {
                        hookData.cell.styles.halign = 'center';
                    }   
                }

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
        });

        doc.save(`charts.pdf`);
    }

    async function addChart(doc, elements, pdfWidth, pdfHeight, top)
    {
        top = top + 10;

        for (let i = 0; i < elements.length; i++) {
            const el = elements.item(i);

            const base64Image = myChart.toBase64Image();

            const elWidth = el.offsetWidth;
            const elHeight = el.offsetHeight;
            const aspectRatio = elWidth / elHeight;

            let newImgWidth = elWidth;
            let newImgHeight = newImgWidth / aspectRatio;

            if (newImgWidth > pdfWidth) {
                newImgWidth = pdfWidth;
                newImgHeight = newImgWidth / aspectRatio;
            }
            const centerX = pdfWidth / 2 - newImgWidth / 2; // Calculate the X position to center the logo

            doc.addImage(base64Image, 'PNG', centerX, top, newImgWidth, newImgHeight);
            // doc.addImage(logo64, 'PNG', logoX, Yaxis, logoWidth, logoHeight);
            
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

        if (animationCompleted) {
            exportToPDF("chart");
        }
    });

});



