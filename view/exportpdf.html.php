<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php
js::set("jsonObject",$jsonObject);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js" integrity="sha512-5yTVoG0jFRsDhgYEoKrZCj5Bazxqa0VnETLN7k0SazQcARBsbgrSb6um+YpzWKNKV2kjb8bhna4fDfOk3YPr4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js" integrity="sha512-P3z5YHtqjIxRAu1AjkWiIPWmMwO9jApnCMsa5s0UTgiDDEjTBjgEqRK0Wn0Uo8Ku3IDa1oer1CIBpTWAvqbmCA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    // Obtaining Data and Parsing from JSON
    function dataProcess() {
        // parsing json

        const obj = JSON.parse(jsonObject);//json_input
        const data = {
            // contract data
            contractName: obj.contract.contractName,
            contractRefNo: obj.contract.refNo,
            contractID: obj.contract.id,
            contractAmount: obj.contract.amount,
            assetName: obj.assetName,
            contractManager: obj.contract.contractManager,
            appointedParty: obj.contract.appointedParty,
            contractDesc: obj.contract.desc,

            // invoice data
            invoiceID: obj.invoice.id,
            invoiceDesc: obj.invoice.description,
            invoiceRefNo: obj.invoice.refNo,
            invoiceAmount: obj.invoice.amount,
            invoicePayment: obj.invoice.payment,
            status: obj.invoice.status,
            
            // table data  
            buildup: parseBuildUp(obj.invoice.details),
            approval: parseApproval(obj.approval),
            details: parseInvoiceDetails(obj.invoice.details),
            validation: parseValidation(obj.approval),

            // fin / piechart data
            finTotal: obj.financeData.contractAmount,
            finPaid: obj.financeData.paid,
            finPending: obj.financeData.panding,

            // for footer
            exportUser: "admin",
        }
            
        function parseApproval(data) {
            var keys = Object.keys(data);
            var result = [];

            keys.forEach(function(key, index){
                var approval = data[key];

                result.push({
                    index: index + 1,
                    name: approval.user,
                    status: approval.status,
                    sign: approval.sign,
                })
            });
            return result;
        }

        function parseInvoiceDetails(data) {
            var keys = Object.keys(data);
            var result = [];

            keys.forEach(function(key, index){
                var detail = data[key];

                result.push({
                    index: index,
                    description: detail.item,
                    price: detail.price,
                })
            });
            return result;
        }

        function parseBuildUp(data) {
            var keys = Object.keys(data);
            var result = [];

            keys.forEach(function(key, index){
                var buildup = data[key];

                result.push({
                    item: buildup.item,
                    price: buildup.price
                })
            });
            return result;
        }

        function parseValidation(data) {
            var keys = Object.keys(data);
            var result = [];

            keys.forEach(function(key, index){
                var valid = data[key];

                result.push({
                    name: valid.user,
                    date: valid.approveDate,
                })
            });
            return result;
        }
        return data;
    }
    
    // generating and structuring report using jsPDF
    // tables generated from AutoTable plugin
    // chart generated from Google Chart plugin
    function generateReport(data) {
        window.jsPDF = window.jspdf.jsPDF; // backwards compatability for jspdf 2.0
        var doc = new jsPDF();

        // coordinates of different parts in the report
        const keyX = 10;
        const valueX = 60;
        const iBuildUpX = 115;
        const detailSpac = 8;
        
        const iDetailY = 44;
        const iBuildUpY = 44;
        const iDescY = 97;
        const iApprovalY = 114;

        const cDetailY = 167;
        const cApprovalY = 224;
        const cDescY = 267;


        // HEADING & TITLE
        doc.setFont("times", "bold");
        doc.setFontSize("25");
        doc.text("Invoice Report", 105, 20, null, null, "center");
        doc.setFontSize("16");
        var title = data.contractName + "\t" + data.invoiceRefNo;
        var width = doc.getTextWidth(title)/2
        doc.text(title, 105, 30, null, null, "center");
        doc.setLineWidth(0.5);
        doc.line(105 - width - 4, 32, 105 + width + 10, 32);
        doc.addImage("hku_logo.png", 'png', 38, 13, 18, 21);

        // INVOICE DETAILS
        doc.setFontSize("14");
        doc.setFont("times", "bold");
        doc.setTextColor(0, 0, 255)
        doc.textWithLink('Invoice Details:', keyX, iDetailY, { url: 'http://www.google.com' });
        doc.setLineWidth(0.25);
        doc.setDrawColor(0, 0, 255); // draw blue lines
        doc.line(keyX-0.5, iDetailY+1, keyX+32, iDetailY+1);

        doc.setFontSize("12");
        doc.setFont("times", "normal");
        doc.setTextColor(0, 0, 0)
        doc.text("Ref. No.:", keyX, iDetailY + detailSpac*1)
        doc.text("Invoice ID:", keyX, iDetailY + detailSpac*2)
        doc.text("Payment No.:", keyX, iDetailY + detailSpac*3)
        doc.text("Submission Status:", keyX, iDetailY + detailSpac*4)
        doc.text("Appointed Party:", keyX, iDetailY + detailSpac*5)


        doc.text(data.invoiceRefNo.toString(), valueX, iDetailY + detailSpac*1)
        doc.text(data.invoiceID.toString(), valueX, iDetailY + detailSpac*2)
        doc.text(data.invoicePayment.toString(), valueX, iDetailY + detailSpac*3)
        doc.text(data.status.toString(), valueX, iDetailY + detailSpac*4)
        doc.text(data.appointedParty.toString(), valueX, iDetailY + detailSpac*5)


        // INVOICE BUILDUP TABLE
        doc.setFont("times", "bold");
        doc.setFontSize("14");

        doc.text("Invoice Build-up", iBuildUpX, iBuildUpY)

        doc.autoTable({
            columns: [
                { dataKey: 'item', header: "Description"},
                { dataKey: 'price', header: "Amount ($HKD)"},
            ],
            body: data.buildup,
            startY: iBuildUpY + 3,
            margin: { left: 115 },
            theme: "grid",
            styles: { font: "times" },

            bodyStyles: {
                lineColor: 10,
                lineWidth: 0.1,
            },
            headStyles: {
                fillColor: [255, 255, 255],
                textColor: [0, 0, 0],
                lineColor: 10,
                lineWidth: 0.1,
            },
        })

        // INVOICE PARSE DESCRIPTION
        // TODO: parse HTML later
        doc.setFontSize("14");
        doc.setFont("times", "bold");
        doc.text("Invoice Description", keyX, iDescY);

        doc.setFontSize("12");
        doc.setFont("times", "normal");
        doc.text(data.invoiceDesc.toString(), keyX, iDescY + 8);

        // SIGNATURE TABLE
        doc.setFont("times", "bold");
        doc.text("Approval Process", keyX, iApprovalY)

        doc.autoTable({
            columns: [
                { dataKey: 'name', header: "Name"},
                { dataKey: 'date', header: "Approval Date"},
                { dataKey: 'sign', header: "Signatures"}
            ],
            body: data.validation,
            startY: iApprovalY + 3,
            margin: { left: keyX},
            theme: "grid",
            styles: { font: "times" },
            bodyStyles: {
                minCellHeight: 10,
                lineColor: 10,
                lineWidth: 0.1,
            },
            headStyles: {
                fillColor: [255, 255, 255],
                textColor: [0, 0, 0],
                halign: 'left',
                lineColor: 10,
                lineWidth: 0.1,
            },
            didDrawCell: (data) => {
                if (data.section === 'body' && data.column.index === 2) {
		    console.warn(data);
                    if (data.row.index === 1) {
                        doc.text("review only", data.cell.x + 2, data.cell.y + 5)
                    }
                    else {
                        doc.addImage("LPC_Logo_CS6-06.png", 'PNG', data.cell.x + 2, data.cell.y + 2, 10, 10)
                    }
                }
            }
        })


        // CONTRACT DETAILS
        doc.setTextColor(0, 0, 255)
        doc.setFontSize("14");
        doc.setFont("times", "bold");
        doc.textWithLink('Contract Details:', keyX, cDetailY, { url: 'http://www.google.com' });
        doc.setLineWidth(0.25);
        doc.setDrawColor(0, 0, 255); // draw blue lines
        doc.line(keyX-0.5, cDetailY+1, keyX+36, cDetailY+1);
        
        doc.setTextColor(0, 0, 0)
        doc.setFontSize("12");
        doc.setFont("times", "normal");
        doc.text("Name:", keyX, cDetailY + detailSpac*1)
        doc.text("Ref. No:", keyX, cDetailY + detailSpac*2)
        doc.text("Contract ID:", keyX, cDetailY + detailSpac*3)
        doc.text("Total Contract Amount:", keyX, cDetailY + detailSpac*4)
        doc.text("Asset Name:", keyX, cDetailY + detailSpac*5)
        doc.text("Contract Manager:", keyX, cDetailY + detailSpac*6)

        doc.text(data.contractName, valueX, cDetailY + detailSpac*1)
        doc.text(data.contractRefNo.toString(), valueX, cDetailY + detailSpac*2)
        doc.text(data.contractID.toString(), valueX, cDetailY + detailSpac*3)
        doc.text(data.contractAmount.toString(), valueX, cDetailY + detailSpac*4)
        doc.text(data.assetName.toString(), valueX, cDetailY + detailSpac*5)
        doc.text(data.contractManager.toString(), valueX, cDetailY + detailSpac*6)

        // CONTRACT DESCRIPTION
        // TODO: parse HTML later
        doc.setFontSize("14");
        doc.setFont("times", "bold");
        doc.text("Contract Description", keyX, cDescY)

        doc.setFontSize("12");
        doc.setFont("times", "normal");
        doc.text(data.contractDesc.toString(), keyX, cDescY + 8)

        // FOOTER
        const d = new Date();
        doc.text(`Exported on: ${d.getDate()}/${d.getMonth()}/${d.getFullYear()}    By: ${data.exportUser}`, 105, 290, null, null, "center");


        // APPROVAL TABLE
        // This function was called in drawChart(), so that the Z-value would be higher than the chart
        // Was done because couldn't find a way to get value of chart.getImageURI() outside the callback function
        // hence doc.save() was put in the callback function, and drawChart() was put at the bottom
        function drawApprovalTable() {
            doc.setFont("times", "bold");
            doc.text("Approval Process", keyX, cApprovalY)
            doc.setFont("times", "normal");

            doc.autoTable({
                columns: [
                    { dataKey: 'index', header: "Sequence" },
                    { dataKey: 'name', header: "Name"},
                    { dataKey: 'status', header: "Status"},
                    { dataKey: 'sign', header: "Sign"}
                ],
                body: data.approval,
                startY: cApprovalY + 3,
                margin: { left: keyX, right: 100 },
                theme: "grid",
                styles: { font: "times" },
                bodyStyles: {
                    lineColor: 10,
                    lineWidth: 0.1,
                },
                headStyles: {
                    fillColor: [255, 255, 255],
                    textColor: [0, 0, 0],
                    halign: 'left',
                    lineColor: 10,
                    lineWidth: 0.1,
                },
            })
        }
        
        
        // PIE CHART
        // Reference: https://developers.google.com/chart/interactive/docs/quick_start
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table.
            var committed = data.finTotal-data.finPaid-data.finPending;
            var test = new google.visualization.DataTable();
            test.addColumn('string', 'Status');
            test.addColumn('number', 'Amount');
            test.addRows([
                ['Total Paid Amount', parseInt(data.finPaid)],
                ['Reporting Invoice Amount', parseInt(data.finPending)],
                ['Total Committed Amount', parseInt(committed)],
            ]);

            // Set chart options
            var options = {'title': data.contractName,
                            'width': 520,
                            'height': 300,
                            'legend': {
                                position: 'labeled'
                            },
                            'fontSize': 12,
                            'pieSliceText': 'value'
                        };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(test, options);

            doc.setPage(1);
            doc.addImage(chart.getImageURI(),'PNG', 90, cDetailY + 10);

            // APPROVAL TABLE AND DOWNLOAD REPORT
            // Explanation in drawApprovalTable()
            drawApprovalTable();
            doc.save();
        }
    }
    data = dataProcess();
    generateReport(data);

</script>

Plases wait, The file will be downloaded
<div id="chart_div"  style='visibility:hidden;'></div>

<?php include '../../common/view/footer.html.php';?>
