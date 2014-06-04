$(document).ready(function() {
	// affix sidebar
	$('#sidebar').affix({offset : {top : 75}});
	var $body = $(document.body);
	var navHeight = $('.top-nav').outerHeight(true) + 10;
	$body.scrollspy({target : '#leftCol',offset : navHeight	});
	// affix sidebar
	
	
	// google maps setup
	var center = new google.maps.LatLng(-25.053169, 133.867844); // Default center area of map ex:AUS
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom : 4,
		minZoom : 4,
		maxZoom : 11,
		disableDefaultUI : true,
		center : center,
		mapTypeId : google.maps.MapTypeId.ROADMAP
	});
	var markers = [];
	var count = data.count;
	for (var i = 0; i < count; i++) {
		var dataLocation = data.locations[i];
		var latLng = new google.maps.LatLng(dataLocation.latitude, dataLocation.longitude);
		var marker = new google.maps.Marker({position : latLng,map : map});
		markers.push(marker);
	}
	var markerCluster = new MarkerClusterer(map, markers); 
	// google maps setup
	
	
	
// bar combination chart
var chart1 = c3.generate({
		bindto : '#chart1',
        data: {
          x: 'x',
          columns: [
          	['x', 'March', 'April', 'May', 'June', 'July','August'],
            ['Mike', 30, 20, 50, 40, 60, 50],
            ['James', 200, 130, 90, 240, 130, 220],
            ['data3', 300, 200, 160, 400, 250, 250],
            ['Target', 200, 130, 90, 240, 130, 220],
            ['data5', 130, 120, 150, 140, 160, 150],
            ['Invoice', 90, 70, 20, 50, 60, 120],
          ],
          types: {
            Mike: 'bar',
            James: 'bar',
            data3: 'spline',
            Target: 'line',
            data5: 'bar',
            Invoice: 'area'
          },
          /*groups: [
            ['Mike','James']
          ]*/
        },
        axis: {
          x: {
            type: 'categorized',label: {
              text: 'X Axis Label',
              position: 'outer-right'
            }
          },           
          y: {
            label: {
              text: 'Y Axis Label',
              position: 'outer-middle'
            }
          }
        }
    });
// bar combination chart
       
       // donut chart
      var chart2 = c3.generate({
			bindto : '#chart2',
        data: {        
          columns: [
            ["Invoiced", 45],
            ["Budgeted", 15],
            ["Project Cost", 30],
          ],
          type : 'donut',
        },
        
        
		donut: { title: "Focus Projects Cost",
			onmouseover: function(d, i) {
				console.log(d, i);
			}, onmouseout: function (d, i) {
				console.log(d, i);
			}, onclick: function (d, i) {
				console.log(d, i);
			},
		}
      });
     // donut chart


	// Instance the tour
	var tour = new Tour({
		
		steps : [{
			element : "#my-element",
        placement: "top",
        backdrop: true,
			title : "Title of my step",
			content : "Introduce new users to your product by walking them through it step by step."
		}, {
			element : "#my-other-element",
			title : "Title of my step",
        placement: "right",
        backdrop: true,
			content : "Content of my step"
		}]
	});

	
	
		
		// Initialize the tour
		tour.init();

$('.start-tour').click(function(e){
	tour.restart();
    //tour.start();

    // it's also good practice to preventDefault on the click event
    // to avoid the click triggering whatever is within href:
    e.preventDefault(); 
});

	$('.popover-test').popover();
	$('.tooltip-test').tooltip();	
	
	
	$('#loading-example-btn').click(function () {
        var btn = $(this);
        btn.button('loading');
        setTimeout(function () {
            btn.button('reset');
            $(".alert").alert('close');
        }, 1000);
    });

});
