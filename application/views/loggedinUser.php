<html>    
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>

			$(function () {
				var dropZoneId = "drop-zone";
				var buttonId = "clickHere";
				var mouseOverClass = "mouse-over";

				var dropZone = $("#" + dropZoneId);
				var ooleft = dropZone.offset().left;
				var ooright = dropZone.outerWidth() + ooleft;
				var ootop = dropZone.offset().top;
				var oobottom = dropZone.outerHeight() + ootop;
				var inputFile = dropZone.find("input");
				document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
					e.preventDefault();
					e.stopPropagation();
					dropZone.addClass(mouseOverClass);
					var x = e.pageX;
					var y = e.pageY;

					if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
							inputFile.offset({ top: y - 15, left: x - 100 });
					} else {
							inputFile.offset({ top: -400, left: -400 });
					}

				}, true);

				if (buttonId != "") {
					var clickZone = $("#" + buttonId);

					var oleft = clickZone.offset().left;
					var oright = clickZone.outerWidth() + oleft;
					var otop = clickZone.offset().top;
					var obottom = clickZone.outerHeight() + otop;

					$("#" + buttonId).mousemove(function (e) {
							var x = e.pageX;
							var y = e.pageY;
							if (!(x < oleft || x > oright || y < otop || y > obottom)) {
								inputFile.offset({ top: y - 15, left: x - 160 });
							} else {
								inputFile.offset({ top: -400, left: -400 });
							}
					});
				}

				document.getElementById(dropZoneId).addEventListener("drop", function (e) {
					$("#" + dropZoneId).removeClass(mouseOverClass);
				}, true);

			})
		</script>
		<style>
			#drop-zone {
				width: 300px;
				height: 200px;
				position:absolute;
				left:50%;
				top:100px;
				margin-left:-150px;
				border: 2px dashed rgba(0,0,0,.3);
				border-radius: 20px;
				font-family: Arial;
				text-align: center;
				position: relative;
				line-height: 180px;
				font-size: 20px;
				color: rgba(0,0,0,.3);
			}

			#drop-zone input {
				position: absolute;
				cursor: pointer;
				left: 0px;
				top: 0px;
				
				opacity:0; */
			}

			#drop-zone.mouse-over {
				border: 2px dashed rgba(0,0,0,.5);
				color: rgba(0,0,0,.5);
			}

			#clickHere {
				position: absolute;
				cursor: pointer;
				left: 50%;
				top: 50%;
				margin-left: -50px;
				margin-top: 20px;
				line-height: 26px;
				color: white;
				font-size: 12px;
				width: 100px;
				height: 26px;
				border-radius: 4px;
				background-color: #3b85c3;

			}

			#clickHere:hover {
				background-color: #4499DD;
			}
			body {
						text-align: center;
			}
			#colns1, #colns2 {
						margin-top: 10px;
						margin-bottom: 10px;
			}
			label {
						color: blue;
			}
			textarea {
						border: 1px solid blue;
						border-radius: 25px;
						color:red;
			}
			#outputfile, #inputfile {
						margin-top: 10px;
						cursor: pointer;
			}
			#container {
						border-radius: 25px;
						text-align: center;
						margin: 5px 5px;
						height: 100%;
						margin-top:15%;
			}
		</style>
	</head>
   <body>
		<div id="drop-zone">
				Drop files here...
				<div id="clickHere">
					or click here..
					<input type="file" name="file" id="file" />
				</div>
		</div>
		<div id="container">
				<label> Sheet 1:</label>
				<input id="colns1" /><br/>
				<label> Sheet 2:</label>
				<input id="colns2" /><br/>
				<textarea placeholder="Set Order for output columns" id="orderedcolns"></textarea><br/>
				<input id="outputfile" type="button" value="download output file" onclick="getXlsFile()" />
		</div>
   </body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
   <script type="text/javascript">
		var overallData = [];
		var ExcelToJSON = function() {

		this.parseExcel = function(file) {
		var reader = new FileReader();

		reader.onload = function(e) {
				var data = e.target.result;
				var workbook = XLSX.read(data, {  
				type: 'binary'
				});
				var sheetcount =1;
				workbook.SheetNames.forEach(function(sheetName) {
				var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
				var json_object = JSON.stringify(XL_row_object);
				overallData.push(JSON.parse(json_object));
				if(sheetcount == 1) {
					$("#colns1").val(Object.keys(JSON.parse(json_object)[0]));
				}
				if(sheetcount == 2) {
					$("#colns2").val(Object.keys(JSON.parse(json_object)[0]));
				}
				sheetcount++;
				});
		};

		reader.onerror = function(ex) {
				console.log(ex);
		};

		reader.readAsBinaryString(file);
		};
		};

		function handleFileSelect(evt) {

		var files = evt.target.files;
		var xl2json = new ExcelToJSON();
		xl2json.parseExcel(files[0]);
		}
		document.getElementById('file').addEventListener('change', handleFileSelect, false);

		function getXlsFile() {
			reqcolns = $("#orderedcolns").val();
			  window.finalData = [];
			var requiredColns = reqcolns.split(',');
			var sheet1Data = overallData[0];
			var sheet2Data = overallData[1];
			var sheet1keys = Object.keys(sheet1Data[0]);
			var sheet2keys = Object.keys(sheet2Data[0]);
			console.log(sheet1Data, sheet2Data, sheet1keys, sheet2keys, requiredColns);
				if(requiredColns[0] == '') {
					alert('please add some column names');
					return;	
				}
				for(var i = 0; i<requiredColns.length; i++) {
					if(!(sheet1keys.includes(requiredColns[i].trim()) || sheet2keys.includes(requiredColns[i].trim()))) {
						alert(requiredColns[i]+' is not any key');
						return;
					}
					
				}
				console.log('all ok');
				requiredColns.forEach(function(columnname) {
					columnname = columnname.trim();
					if (sheet1keys.includes(columnname)) {
						for(var i=0; i<sheet1Data.length; i++) {
							 window.finalData[i] =window.finalData[i] || {};
							 window.finalData[i][columnname] = sheet1Data[i][columnname]; 
						}
				
					}
					if (sheet2keys.includes(columnname)) {
						for(var i=0; i<sheet2keys.length; i++)  {
							 window.finalData[i] =window.finalData[i] ||{};
							 window.finalData[i][columnname] = sheet2Data[i][columnname]; 
						}
					}
				})
					console.log( window.finalData);
					JSONToCSVConvertor(window.finalData, 'machineTest', true);
		}

		function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
			var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
			
			var CSV = '';    

			
			CSV += ReportTitle + '\r\n\n';

			if (ShowLabel) {
				var row = "";
				for (var index in arrData[0]) {
					row += index + ',';
				}

				row = row.slice(0, -1);
				CSV += row + '\r\n';
			}
			
			for (var i = 0; i < arrData.length; i++) {
				var row = "";
				for (var index in arrData[i]) {
					row += '"' + arrData[i][index] + '",';
				}

				row.slice(0, row.length - 1);
				CSV += row + '\r\n';
			}

			if (CSV == '') {        
				alert("Invalid data");
				return;
			}   
			
			var fileName = "MyReport_";
			fileName += ReportTitle.replace(/ /g,"_");   
			
			var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
			
			var link = document.createElement("a");    
			link.href = uri;
			
			link.style = "visibility:hidden";
			link.download = fileName + ".csv";
			
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		}
	</script>
</html>
