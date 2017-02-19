$(document).ready(function(){
	//to populate years in the filter
	var years = [""+new Date().getFullYear()];
	$.getJSON(baseURL+'/applications/data', function(data){
		data.forEach(function(d){
			if (d.Key !== null){
				add_unique_to_array(years, d.Key.substr(0,4));
			}
			populate_dropdown(years);
		});
		$("#viz_year_select").change();
	});
	if(years.length == 1){
		populate_dropdown(years);
		$("#viz_year_select").change();
	}
	
	var year = $("#viz_year_select").val();
	//to plot the Calendar using JSON data
	drawCalendar(baseURL+'/applications/data', year);

	
	

	//listener for each cell in the calendar
	$("svg").on("click", "rect", function(e){
		var vizEdit = document.getElementById("vizEditTable");
		vizEdit.innerHTML = ' ';
		var vizEdit = document.getElementById("vizEditTable");
		var tableHeadHtml = '<div class="tableheader"><div class="tablecell">Job ID</div><div class="tablecell">Company Name*</div>'
		tableHeadHtml += '<div class="tablecell">Position* </div><div class="tablecell">Job Link</div>';
		tableHeadHtml += '<div class="tablecell">Applied Date*</div><div class="tablecell">Resume Version</div>';
		tableHeadHtml += '<div class="tablecell">Contact</div><div class="tablecell">Status</div></div>';
		vizEdit.innerHTML += tableHeadHtml;
		var date = e.currentTarget.__data__;
		d3.json(baseURL+'/applications/data', function(error, data){
			if(data !== undefined){
			var found = false;
			data.forEach(function(obj){
				if(obj.Key == date){
					//Populate all application on the clicked date
					obj.Values.forEach(function(Values){
						var vizEdit = document.getElementById("vizEditTable");
						var html1 = '<form id="editApplication_';
						html1 += Values.id;
						html1 += '" action="" method="POST"><div class="tablerow"><div class="tablecell normalCell id" contenteditable="false">';
						html1 += Values.id;
						html1 += '</div><div class="tablecell normalCell company_name" contenteditable="false">';
					 	html1 += Values.company_name;
						html1 +='</div><div class="tablecell normalCell position" contenteditable="false">';
						html1 += Values.position;
						html1 += '</div><div class="tablecell normalCell job_url" contenteditable="false">';
						html1 += Values.job_url;
						html1 += '</div><div class="tablecell normalCell applied_date" contenteditable="false">';
						html1 += Values.applied_date;
						html1 += '</div><div class="tablecell normalCell resume_version" contenteditable="false">';
						html1 += Values.resume_version;
						html1 += '</div><div class="tablecell normalCell contact" contenteditable="false">';
						html1 += Values.contact;
						html1 += '</div><div class="tablecell normalCell status" contenteditable="false">';
						html1 += Values.status;
						html1 += '</div><input class="id" type="hidden" name="id" value="';
						html1 += Values.id;
						html1 += '"><input class="company_name" type="hidden" name="company_name" value="';
						html1 += Values.company_name;
						html1 += '"><input class="position" type="hidden" name="position" value="';
						html1 += Values.position;
						html1 += '"><input class="job_url" type="hidden" name="job_url" value="';
						html1 += Values.job_url;
						html1 += '"><input class="applied_date" type="hidden" name="applied_date" value="';
						html1 += Values.applied_date;
						html1 += '"><input class="resume_version" type="hidden" name="resume_version" value="';
						html1 += Values.resume_version;
						html1 += '"><input class="contact" type="hidden" name="contact" value="';
						html1 += Values.contact;
						html1 += '"><input class="status" type="hidden" name="status" value="';
						html1 += Values.status;
						html1 += '"><button class="edit" form="';
						html1 += Values.id;
						html1 += '" name="edit" type="button" value="edit">&#9998</button><button class="delete" form="';
						html1 += Values.id;
						html1 += '" name="delete" type="button" value="delete">&#10060</a></div></form>';
						vizEdit.innerHTML += html1;
						//addNewApp(date);
						found = true;
					});
					addNewApp(date);
				}				
			});
			if(!found) addNewApp(date);
			}
			else{
				addNewApp(date);
			}
		});
		/* .error(function(){	//Add a blank row to create new application
			addNewApp(date);
		}); */
	});

	//listener for year filter
	$("#viz_year_select").on("change", function(){
		drawCalendar(baseURL+'/applications/data', this.value);
	});

});
function addNewApp(date){
	var vizEdit = document.getElementById("vizEditTable");
			var addAppHtml = '<form id="addApplication" action="" method="POST"><div class="tablerow"><div class="tablecell">';
			addAppHtml += 'New </div><div class="tablecell"><input type="text" name="company_name" required></div>';
			addAppHtml += '<div class="tablecell"><input type="text" name="position" required></div>';
			addAppHtml += '<div class="tablecell"><textarea name="job_url"></textarea></div><div class="tablecell">';
			addAppHtml += '<input type="date" name="applied_date" value =' + date + '></div><div class="tablecell">';
			addAppHtml += '<input type="text" name="resume_version" style="max-width: 50px"></div><div class="tablecell">';
			addAppHtml += '<input type="text" name="contact"></div><div class="tablecell"><select name="status">';
			addAppHtml += '<option>Applied</option><option>Selected</option><option>Rejected</option></select></div>';
			addAppHtml += '<button id="addApp" form="addApplication" type="submit" name="add" value="addApp">&#9989</button></div></form>';
			vizEdit.innerHTML+=addAppHtml;
}
function add_unique_to_array(array, value){
	if (array.indexOf(value)===-1){
		array.push(value);
	}
}

function populate_dropdown(year_array){
	var dropdown_options = "";
	//if (year_array.length>0){ 
		for (year in year_array){
			dropdown_options += "<option value='"+year_array[year]+"'>"+year_array[year]+"</option>";
		}
	//}
	/* else{
		dropdown_options = "<option value='2016'>2016</option>";
	} */
	$("#viz_year_select").html(dropdown_options);
	//console.log(dropdown_options);
}

//https://bl.ocks.org/mbostock/4063318 - Thanks to Bostock!!
function drawCalendar(jsonUrl, year){
	$('svg').empty();

	var width = 1360,
	    height = 200,
	    cellSize = 23, // cell size
		week_days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']
		month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

	var percent = d3.format(".1%"),
		format = d3.time.format("%Y-%m-%d");

	var color = d3.scale.linear()
    	.domain([0, 3])
	.range(["white", '#003300']);

	var svg = d3.select("body").selectAll("svg")
		.data(d3.range(year, year+1))
	 	.attr("width", width)
		.attr("height", height)
		.attr("class", "RdYlGn")
	  .append("g")
		.attr("transform", "translate(" + ((width - cellSize * 53) / 2) + "," + (height - cellSize * 7 - 1) + ")");

	svg.append("text")
		.attr("transform", "translate(-50," + cellSize * 3.5 + ")rotate(-90)")
		.style("text-anchor", "middle")
		.text(function(d) { return d; });

	var rect = svg.selectAll(".day")
		.data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d+1, 0, 1)); })
	  .enter().append("rect")
		.attr("class", "day")
		.attr("width", cellSize)
		.attr("height", cellSize)
		.attr("x", function(d) { return d3.time.weekOfYear(d) * cellSize; })
		.attr("y", function(d) { return d.getDay() * cellSize; })
		.attr("fill", "#fff")
		.attr("stroke", "#ccc")
		.datum(format);

	rect.append("title")
		.text(function(d) { return d; });

	for (var i=0; i<7; i++)
	{
		svg.append("text")
			.attr("transform", "translate(-5," + cellSize*(i+1) + ")")
			.style("text-anchor", "end")
			.attr("dy", "-.25em")
			.text(function(d) { return week_days[i]; });
	}

	svg.selectAll(".month")
		.data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d+1, 0, 1)); })
	  .enter().append("path")
		.attr("class", "month")
		.attr("d", monthPath);

	var legend = svg.selectAll(".legend")
      .data(month)
    .enter().append("g")
      .attr("class", "legend")
      .attr("transform", function(d, i) { return "translate(" + (((i+1) * 98)+8) + ",0)"; });

	legend.append("text")
	   .attr("class", function(d,i){ return month[i] })
	   .style("text-anchor", "end")
	   .attr("dy", "-.25em")
	   .text(function(d,i){ return month[i] });

	d3.json(jsonUrl, function(error, appData){
		//if(error) throw error;
		var data = {};
		if(appData !== undefined){
			appData.forEach(function(obj){
				data[obj.Key] = obj.Values.length;
			});

			rect.filter(function(d) { return d in data; })
				.attr("fill", function(d){return d3.rgb(color(data[d]));})
			.select("title")
			  .text(function(d) { return d + ": " + data[d]; });
		}
	});

	function monthPath(t0) {
	  var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
		  d0 = t0.getDay(), w0 = d3.time.weekOfYear(t0),
		  d1 = t1.getDay(), w1 = d3.time.weekOfYear(t1);
	  return "M" + (w0 + 1) * cellSize + "," + d0 * cellSize
		  + "H" + w0 * cellSize + "V" + 7 * cellSize
		  + "H" + w1 * cellSize + "V" + (d1 + 1) * cellSize
		  + "H" + (w1 + 1) * cellSize + "V" + 0
		  + "H" + (w0 + 1) * cellSize + "Z";
	}
}
