function createGraphd3(data, divId, length)
{
	//Settings
	var w = length,
		h = length,
		r = Math.min(w, h)/2,
		color = d3.scale.category20(),
		donut = d3.layout.pie()
				.value(function(d) { return d.duration; }),
		arc = d3.svg.arc().outerRadius(r);

	//Setup visualization
	var vis = d3.select("#"+divId)
	  .append("svg")
		.data([data])
		.attr("width", w)
		.attr("height", h);
		
	var arcs = vis.selectAll("g.arc")
		.data(donut)
	  .enter().append("g")
		.attr("class", "arc")
		.attr("transform", "translate(" + r + "," + r + ")");
		
	var paths = arcs.append("path")
				.attr("fill", function(d, i) { return color(i); })
				.attr("d", arc);
		
	arcs.append("text")
		.attr("transform", function(d, i) { 
			d.innerRadius = r/10 + i*20;
			d.outerRadius = r ; // Scale so that the labels are at different radiaii
			return "translate("  + arc.centroid(d) + ")"; 
		})
		.attr("dy", ".35em")
		.attr("text-anchor", "end")
		.attr("display", function(d) { return d.value > .15 ? null : "none"; })
		.text(function(d, i) { return data[i].name; });
		
	//Transition
	paths.transition()
    .ease("bounce")
    .duration(2000)
    .attrTween("d", tweenPie);
	
	function tweenPie(b) {
	  b.innerRadius = 0;
	  var i = d3.interpolate({startAngle: 0, endAngle: 0}, b);
	  return function(t) {
		return arc(i(t));
	  };
	}
}