<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Dashboard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="layout" content="main"/>

		<script src="js/jquery/jquery-1.8.2.min.js" type="text/javascript" ></script>
		<link href="css/customize-template.css" type="text/css" media="screen, projection" rel="stylesheet" />

		<style>
			#body-content {
				padding-top: 40px;
			}

			#map {
				width: 100%;
				height: 350px;
			}
		</style>
		
		<script src="http://maps.googleapis.com/maps/api/js?v=3&amp;sensor=false"></script>
		<script type="text/javascript" src="js/maps/data.json"></script>
		<script type="text/javascript" src="js/maps/markerclusterer_packed.js"></script>
		
		<script type="text/javascript">
			function initialize() {
				var center = new google.maps.LatLng(-25.053169, 133.867844);

				var map = new google.maps.Map(document.getElementById('map'), {
					zoom : 4,
					minZoom : 4,
					maxZoom : 9,
					disableDefaultUI: true,
					center : center,
					mapTypeId : google.maps.MapTypeId.TERRAIN
				});

				var markers = [];
				var count = data.count;
				for (var i = 0; i < count; i++) {
					var dataLocation = data.locations[i];
					var latLng = new google.maps.LatLng(dataLocation.latitude, dataLocation.longitude);
					var marker = new google.maps.Marker({
						position : latLng,
						map : map
					});
					markers.push(marker);
				}
				var markerCluster = new MarkerClusterer(map, markers);
			}
			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		
		
		<link href="http://c3js.org/css/c3-e07e76d4.css" rel="stylesheet" type="text/css">
		<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
		<script src="http://c3js.org/js/c3.min-be9bea56.js"></script>

	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button class="btn btn-navbar" data-toggle="collapse" data-target="#app-nav-top-bar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="dashboard.html" class="brand"><i class="icon-dashboard"> Sojourn</i></a>
					<div id="app-nav-top-bar" class="nav-collapse">
						<ul class="nav">

							
						<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Control <b class="caret hidden-phone"></b> </a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Add New User</a>
									</li>
									<li>
										<a href="#">View All Accounts</a>
									</li>
									<li>
										<a href="#">View User Timeline</a>
									</li>
								</ul>
							</li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Contact Person <b class="caret hidden-phone"></b> </a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Add New Contact Person</a>
									</li>
									<li>
										<a href="#">View All Contact Person</a>
									</li>
								</ul>
							</li>

						</ul>
						
						<ul class="nav pull-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> Jervy Zaballa <b class="caret hidden-phone"></b> </a>
								<ul class="dropdown-menu">
									<li>
										<a href="demo-horizontal-nav.html">View Details</a>
									</li>
									<li>
										<a href="demo-horizontal-fixed-nav.html">My Timeline</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="demo-vertical-fixed-nav.html"><i class="icon-signout"></i> Logout</a>
									</li>
								</ul>
							</li>
						</ul>	
						<ul class="nav pull-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-magic"></i> Virtual Tour <b class="caret hidden-phone"></b> </a>
								<ul class="dropdown-menu">
									<li>
										<a href="demo-horizontal-nav.html">New Company</a>
									</li>
									<li>
										<a href="demo-horizontal-fixed-nav.html">New Projects</a>
									</li>
									<li>
										<a href="demo-horizontal-fixed-nav.html">WIP</a>
									</li>
									<li>
										<a href="demo-horizontal-fixed-nav.html">Export</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div id="body-container">
			<div id="body-content">

				<div class="body-nav body-nav-vertical body-nav-fixed">
					<div class="container">
						<ul>
							<li>
								<a href="#"> <i class="icon-dashboard icon-large"></i> Dashboard </a>
							</li>
							<li>
								<a href="#"> <i class="icon-calendar icon-large"></i> Clients </a>
							</li>
							<li>
								<a href="#"> <i class="icon-map-marker icon-large"></i> Projects </a>
							</li>
							<li>
								<a href="#"> <i class="icon-tasks icon-large"></i> WIP </a>
							</li>
							<li>
								<a href="#"> <i class="icon-list-alt icon-large"></i> Invoice </a>
							</li>
							<li>
								<a href="#"> <i class="icon-bar-chart icon-large"></i> Reports </a>
							</li>
							<li>
								<a href="#"> <i class="icon-cogs icon-large"></i> Settings </a>
							</li>
						</ul>
					</div>
				</div>

				<section class="nav nav-page">
					<div class="container">
						<div class="row">
							<div class="span7">
								<header class="page-header">
									<h3>Dashboard
									<br/>
									<small>Monday, May 26, 2014</small></h3>
								</header>
							</div>
							<div class="page-nav-options">
								<div class="span9">
									<ul class="nav nav-pills hide">
										<li>
											<a href="#"><i class="icon-home"></i>Home</a>
										</li>
										<li>
											<a href="#" class="btn-small">Clients</a>
										</li>
										<li>
											<a href="#" class="btn-small">Projects</a>
										</li>
										<li>
											<a href="#" class="btn-small">WIP</a>
										</li>
									</ul>

									<ul class="nav nav-tabs" style="float: right;">
										<li class="active">
											<a href="#"><i class="icon-home"></i>Home</a>
										</li>
										<li>
											<a href="#" class="btn-small">Clients</a>
										</li>
										<li>
											<a href="#" class="btn-small">Projects</a>
										</li>
										<li>
											<a href="#" class="btn-small">WIP</a>
										</li>
									</ul>

								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="page container">
					<div class="row">
						<div class="span8">
							<div class="box">
								<div class="box-header">
									<i class="icon-bookmark"></i>
									<h5>Shortcuts</h5>
								</div>
								<div class="box-content">
									<div class="btn-group-box">
										<button class="btn">
											<i class="icon-dashboard icon-large"></i>
											<br/>
											Dashboard
										</button>
										<button class="btn">
											<i class="icon-user icon-large"></i>
											<br/>
											Account
										</button>
										<button class="btn">
											<i class="icon-search icon-large"></i>
											<br/>
											Search
										</button>
										<button class="btn">
											<i class="icon-list-alt icon-large"></i>
											<br/>
											Reports
										</button>
										<button class="btn">
											<i class="icon-bar-chart icon-large"></i>
											<br/>
											Charts
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="span8">
							<div class="blockoff-left">
								<legend class="lead">
									Welcome
								</legend>
								<p>
									Welcome message goes here. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
									incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
									exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span5">
							<div class="box" style="overflow: hidden;">
								<div class=" box-content pattern pattern-sandstone">
									<div class="pull-left">
										<h2>100</h2>
										<p>
											Total Projects
										</p>
									</div>
									<i class="icon-user icon-large pull-right" style="font-size: 75px; margin-top: 40px;"></i>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="box" style="overflow: hidden;">
								<div class=" box-content pattern pattern-sandstone">
									<div class="pull-left">
										<h2>500</h2>
										<p>
											Total Clients
										</p>
									</div>
									<i class="icon-tasks icon-large pull-right" style="font-size: 75px; margin-top: 40px;"></i>
								</div>
							</div>
						</div><div class="span6">
							<div class="box" style="overflow: hidden;">
								<div class=" box-content pattern pattern-sandstone">
									<div class="pull-left">
										<h2>30</h2>
										<p>
											Invoiced Projects
										</p>
									</div>
									<i class="icon-bar-chart icon-large pull-right" style="font-size: 75px; margin-top: 40px;"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span8">
							<div class="box pattern pattern-sandstone">
								<div class="box-header">
									<i class="icon-list"></i>
									<h5>Lists</h5>
									<button class="btn btn-box-right" data-toggle="collapse" data-target=".box-list">
										<i class="icon-reorder"></i>
									</button>
								</div>
								<div class="box-content box-list collapse in">
									<ul>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
									</ul>
									<div class="box-collapse">
										<button class="btn btn-box" data-toggle="collapse" data-target=".more-list">
											Show More
										</button>
									</div>
									<ul class="more-list collapse out">
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
										<li>
											<div>
												<a href="#" class="news-item-title">Duis aute irure dolor in reprehenderit</a>
												<p class="news-item-preview">
													Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.
												</p>
											</div>
										</li>
									</ul>
								</div>

							</div>
						</div>
						<div class="span8">
							<div class="box">
								<div class="box-header">
									<i class="icon-book"></i>
									<h5>Forms</h5>
								</div>
								<div class="box-content">
									<form class="form-inline">
										<p>
											Login
										</p>
										<div class="input-prepend">
											<span class="add-on"><i class="icon-envelope"></i></span>
											<input class="span4" type="text" placeholder="Email address">
										</div>
										<div class="input-prepend">
											<span class="add-on"><i class="icon-key"></i></span>
											<input class="span4" type="password" placeholder="Password">
										</div>
									</form>
									<form class="form-inline">
										<p>
											Date Picker & Select Boxes
										</p>
										<div class="progress progress-striped active">
											<div class="bar" style="width: 75%;"></div>
										</div>
										<div id="datepicker" class="input-prepend date">
											<span class="add-on"><i class="icon-th"></i></span>
											<input class="span2" type="text" value="02-16-2012">
										</div>
										<select class="chosen span4" data-placeholder="Choose a Country...">
											<option value=""></option>
											<option value="United States">United States</option>
											<option value="United Kingdom">United Kingdom</option>
											<option value="Afghanistan">Afghanistan</option>
											<option value="Albania">Albania</option>
											<option value="Algeria">Algeria</option>
											<option value="American Samoa">American Samoa</option>
											<option value="Andorra">Andorra</option>
											<option value="Angola">Angola</option>
											<option value="Anguilla">Anguilla</option>
											<option value="Antarctica">Antarctica</option>
											<option value="Antigua and Barbuda">Antigua and Barbuda</option>
											<option value="Argentina">Argentina</option>
											<option value="Armenia">Armenia</option>
											<option value="Aruba">Aruba</option>
											<option value="Australia">Australia</option>
											<option value="Austria">Austria</option>
											<option value="Azerbaijan">Azerbaijan</option>
											<option value="Bahamas">Bahamas</option>
											<option value="Bahrain">Bahrain</option>
											<option value="Bangladesh">Bangladesh</option>
											<option value="Barbados">Barbados</option>
											<option value="Belarus">Belarus</option>
											<option value="Belgium">Belgium</option>
											<option value="Belize">Belize</option>
											<option value="Benin">Benin</option>
											<option value="Bermuda">Bermuda</option>
											<option value="Bhutan">Bhutan</option>
											<option value="Bolivia">Bolivia</option>
											<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
											<option value="Botswana">Botswana</option>
											<option value="Bouvet Island">Bouvet Island</option>
											<option value="Brazil">Brazil</option>
											<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
											<option value="Brunei Darussalam">Brunei Darussalam</option>
											<option value="Bulgaria">Bulgaria</option>
											<option value="Burkina Faso">Burkina Faso</option>
											<option value="Burundi">Burundi</option>
											<option value="Cambodia">Cambodia</option>
											<option value="Cameroon">Cameroon</option>
											<option value="Canada">Canada</option>
											<option value="Cape Verde">Cape Verde</option>
											<option value="Cayman Islands">Cayman Islands</option>
											<option value="Central African Republic">Central African Republic</option>
											<option value="Chad">Chad</option>
											<option value="Chile">Chile</option>
											<option value="China">China</option>
											<option value="Christmas Island">Christmas Island</option>
											<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
											<option value="Colombia">Colombia</option>
											<option value="Comoros">Comoros</option>
											<option value="Congo">Congo</option>
											<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
											<option value="Cook Islands">Cook Islands</option>
											<option value="Costa Rica">Costa Rica</option>
											<option value="Cote D'ivoire">Cote D'ivoire</option>
											<option value="Croatia">Croatia</option>
											<option value="Cuba">Cuba</option>
											<option value="Cyprus">Cyprus</option>
											<option value="Czech Republic">Czech Republic</option>
											<option value="Denmark">Denmark</option>
											<option value="Djibouti">Djibouti</option>
											<option value="Dominica">Dominica</option>
											<option value="Dominican Republic">Dominican Republic</option>
											<option value="Ecuador">Ecuador</option>
											<option value="Egypt">Egypt</option>
											<option value="El Salvador">El Salvador</option>
											<option value="Equatorial Guinea">Equatorial Guinea</option>
											<option value="Eritrea">Eritrea</option>
											<option value="Estonia">Estonia</option>
											<option value="Ethiopia">Ethiopia</option>
											<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
											<option value="Faroe Islands">Faroe Islands</option>
											<option value="Fiji">Fiji</option>
											<option value="Finland">Finland</option>
											<option value="France">France</option>
											<option value="French Guiana">French Guiana</option>
											<option value="French Polynesia">French Polynesia</option>
											<option value="French Southern Territories">French Southern Territories</option>
											<option value="Gabon">Gabon</option>
											<option value="Gambia">Gambia</option>
											<option value="Georgia">Georgia</option>
											<option value="Germany">Germany</option>
											<option value="Ghana">Ghana</option>
											<option value="Gibraltar">Gibraltar</option>
											<option value="Greece">Greece</option>
											<option value="Greenland">Greenland</option>
											<option value="Grenada">Grenada</option>
											<option value="Guadeloupe">Guadeloupe</option>
											<option value="Guam">Guam</option>
											<option value="Guatemala">Guatemala</option>
											<option value="Guinea">Guinea</option>
											<option value="Guinea-bissau">Guinea-bissau</option>
											<option value="Guyana">Guyana</option>
											<option value="Haiti">Haiti</option>
											<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
											<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
											<option value="Honduras">Honduras</option>
											<option value="Hong Kong">Hong Kong</option>
											<option value="Hungary">Hungary</option>
											<option value="Iceland">Iceland</option>
											<option value="India">India</option>
											<option value="Indonesia">Indonesia</option>
											<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
											<option value="Iraq">Iraq</option>
											<option value="Ireland">Ireland</option>
											<option value="Israel">Israel</option>
											<option value="Italy">Italy</option>
											<option value="Jamaica">Jamaica</option>
											<option value="Japan">Japan</option>
											<option value="Jordan">Jordan</option>
											<option value="Kazakhstan">Kazakhstan</option>
											<option value="Kenya">Kenya</option>
											<option value="Kiribati">Kiribati</option>
											<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
											<option value="Korea, Republic of">Korea, Republic of</option>
											<option value="Kuwait">Kuwait</option>
											<option value="Kyrgyzstan">Kyrgyzstan</option>
											<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
											<option value="Latvia">Latvia</option>
											<option value="Lebanon">Lebanon</option>
											<option value="Lesotho">Lesotho</option>
											<option value="Liberia">Liberia</option>
											<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
											<option value="Liechtenstein">Liechtenstein</option>
											<option value="Lithuania">Lithuania</option>
											<option value="Luxembourg">Luxembourg</option>
											<option value="Macao">Macao</option>
											<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
											<option value="Madagascar">Madagascar</option>
											<option value="Malawi">Malawi</option>
											<option value="Malaysia">Malaysia</option>
											<option value="Maldives">Maldives</option>
											<option value="Mali">Mali</option>
											<option value="Malta">Malta</option>
											<option value="Marshall Islands">Marshall Islands</option>
											<option value="Martinique">Martinique</option>
											<option value="Mauritania">Mauritania</option>
											<option value="Mauritius">Mauritius</option>
											<option value="Mayotte">Mayotte</option>
											<option value="Mexico">Mexico</option>
											<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
											<option value="Moldova, Republic of">Moldova, Republic of</option>
											<option value="Monaco">Monaco</option>
											<option value="Mongolia">Mongolia</option>
											<option value="Montenegro">Montenegro</option>
											<option value="Montserrat">Montserrat</option>
											<option value="Morocco">Morocco</option>
											<option value="Mozambique">Mozambique</option>
											<option value="Myanmar">Myanmar</option>
											<option value="Namibia">Namibia</option>
											<option value="Nauru">Nauru</option>
											<option value="Nepal">Nepal</option>
											<option value="Netherlands">Netherlands</option>
											<option value="Netherlands Antilles">Netherlands Antilles</option>
											<option value="New Caledonia">New Caledonia</option>
											<option value="New Zealand">New Zealand</option>
											<option value="Nicaragua">Nicaragua</option>
											<option value="Niger">Niger</option>
											<option value="Nigeria">Nigeria</option>
											<option value="Niue">Niue</option>
											<option value="Norfolk Island">Norfolk Island</option>
											<option value="Northern Mariana Islands">Northern Mariana Islands</option>
											<option value="Norway">Norway</option>
											<option value="Oman">Oman</option>
											<option value="Pakistan">Pakistan</option>
											<option value="Palau">Palau</option>
											<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
											<option value="Panama">Panama</option>
											<option value="Papua New Guinea">Papua New Guinea</option>
											<option value="Paraguay">Paraguay</option>
											<option value="Peru">Peru</option>
											<option value="Philippines">Philippines</option>
											<option value="Pitcairn">Pitcairn</option>
											<option value="Poland">Poland</option>
											<option value="Portugal">Portugal</option>
											<option value="Puerto Rico">Puerto Rico</option>
											<option value="Qatar">Qatar</option>
											<option value="Reunion">Reunion</option>
											<option value="Romania">Romania</option>
											<option value="Russian Federation">Russian Federation</option>
											<option value="Rwanda">Rwanda</option>
											<option value="Saint Helena">Saint Helena</option>
											<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
											<option value="Saint Lucia">Saint Lucia</option>
											<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
											<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
											<option value="Samoa">Samoa</option>
											<option value="San Marino">San Marino</option>
											<option value="Sao Tome and Principe">Sao Tome and Principe</option>
											<option value="Saudi Arabia">Saudi Arabia</option>
											<option value="Senegal">Senegal</option>
											<option value="Serbia">Serbia</option>
											<option value="Seychelles">Seychelles</option>
											<option value="Sierra Leone">Sierra Leone</option>
											<option value="Singapore">Singapore</option>
											<option value="Slovakia">Slovakia</option>
											<option value="Slovenia">Slovenia</option>
											<option value="Solomon Islands">Solomon Islands</option>
											<option value="Somalia">Somalia</option>
											<option value="South Africa">South Africa</option>
											<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
											<option value="South Sudan">South Sudan</option>
											<option value="Spain">Spain</option>
											<option value="Sri Lanka">Sri Lanka</option>
											<option value="Sudan">Sudan</option>
											<option value="Suriname">Suriname</option>
											<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
											<option value="Swaziland">Swaziland</option>
											<option value="Sweden">Sweden</option>
											<option value="Switzerland">Switzerland</option>
											<option value="Syrian Arab Republic">Syrian Arab Republic</option>
											<option value="Taiwan, Republic of China">Taiwan, Republic of China</option>
											<option value="Tajikistan">Tajikistan</option>
											<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
											<option value="Thailand">Thailand</option>
											<option value="Timor-leste">Timor-leste</option>
											<option value="Togo">Togo</option>
											<option value="Tokelau">Tokelau</option>
											<option value="Tonga">Tonga</option>
											<option value="Trinidad and Tobago">Trinidad and Tobago</option>
											<option value="Tunisia">Tunisia</option>
											<option value="Turkey">Turkey</option>
											<option value="Turkmenistan">Turkmenistan</option>
											<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
											<option value="Tuvalu">Tuvalu</option>
											<option value="Uganda">Uganda</option>
											<option value="Ukraine">Ukraine</option>
											<option value="United Arab Emirates">United Arab Emirates</option>
											<option value="United Kingdom">United Kingdom</option>
											<option value="United States">United States</option>
											<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
											<option value="Uruguay">Uruguay</option>
											<option value="Uzbekistan">Uzbekistan</option>
											<option value="Vanuatu">Vanuatu</option>
											<option value="Venezuela">Venezuela</option>
											<option value="Viet Nam">Viet Nam</option>
											<option value="Virgin Islands, British">Virgin Islands, British</option>
											<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
											<option value="Wallis and Futuna">Wallis and Futuna</option>
											<option value="Western Sahara">Western Sahara</option>
											<option value="Yemen">Yemen</option>
											<option value="Zambia">Zambia</option>
											<option value="Zimbabwe">Zimbabwe</option>
										</select>
									</form>
								</div>
								<div class="box-footer">
									<button type="submit" class="btn btn-primary">
										<i class="icon-ok"></i>
										Submit
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<!--<div class="span3">
						<div class="box">
						<div class="box-content">

						</div>
						</div>
						</div>-->

						<div class="span16">
							<div class="row">
								<!-- <div class="span8">

									<div class="box">
										<div class="box-header">
											<i class="icon-bar-chart"></i>
											<h5>Charts</h5>
										</div>
										<div class="box-content">
											<div id="piechart">
												<p>
													Place piechart here
												</p>
											</div>
										</div>
									</div>
								</div> -->
								<div class="span8">
									<div class="box pattern pattern-sandstone">
										<div class="box-header">
											<i class="icon-bar-chart"></i>
											<h5> Statistics </h5>
										</div>
										<div class="box-content  box-table">
											<br />
											<div id="chart1"></div>											
										</div>
									</div>
								</div>
								
								<div class="span8">
									<div class="box pattern pattern-sandstone">
										<div class="box-header">
											<i class="icon-table"></i>
											<h5> Table </h5>
										</div>
										<div class="box-content box-table">
											<table id="sample-table" class="table table-hover table-bordered tablesorter">
												<thead>
													<tr>
														<th>Version</th>
														<th>Browser</th>
														<th class="td-actions"></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>7.0</td>
														<td>Internet
														Explorer</td>
														<td class="td-actions"><a href="javascript:;" class="btn btn-small btn-info"> <i class="btn-icon-only icon-ok"></i> </a><a href="javascript:;" class="btn btn-small btn-danger"> <i class="btn-icon-only icon-remove"></i> </a></td>
													</tr>
													<tr>
														<td>4.0</td>
														<td>Firefox</td>
														<td class="td-actions"><a href="javascript:;" class="btn btn-small btn-info"> <i class="btn-icon-only icon-ok"></i> </a><a href="javascript:;" class="btn btn-small btn-danger"> <i class="btn-icon-only icon-remove"></i> </a></td>
													</tr>
													<tr>
														<td>Latest</td>
														<td>Safari</td>
														<td class="td-actions"><a href="javascript:;" class="btn btn-small btn-info"> <i class="btn-icon-only icon-ok"></i> </a><a href="javascript:;" class="btn btn-small btn-danger"> <i class="btn-icon-only icon-remove"></i> </a></td>
													</tr>
													<tr>
														<td>Latest</td>
														<td>Chrome</td>
														<td class="td-actions"><a href="javascript:;" class="btn btn-small btn-info"> <i class="btn-icon-only icon-ok"></i> </a><a href="javascript:;" class="btn btn-small btn-danger"> <i class="btn-icon-only icon-remove"></i> </a></td>
													</tr>
													<tr>
														<td>11</td>
														<td> Opera</td>
														<td class="td-actions"><a href="javascript:;" class="btn btn-small btn-info"> <i class="btn-icon-only icon-ok"></i> </a><a href="javascript:;" class="btn btn-small btn-danger"> <i class="btn-icon-only icon-remove"></i> </a></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span8">
									<div class="box">
										<div class="box-header">
											<i class="icon-folder-open"></i>
											<h5>Content</h5>
										</div>
										<div class="box-content">
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
												incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
												exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
											</p>
											<p>
												Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
												fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa
												qui officia deserunt mollit anim id est laborum.
											</p>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
												incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
												exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
											</p>
										</div>
									</div>
								</div>
								
								
								<div class="span8">
									<div class="box pattern pattern-sandstone">
										<div class="box-header">
											<i class="icon-bar-chart"></i>
											<h5> More Statistics </h5>
										</div>
										<div class="box-content  box-table">
											<br />
											<div id="chart3" ></div>											
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					</div>

					<div class="row">
						<div class="span8">
							<div class="box">
								<div class="box-header">
									<i class="icon-sign-blank"></i>
									<h5>Sample Box</h5>
									<button class="btn btn-box-right" data-toggle="collapse" data-target=".box-hide-me">
										<i class="icon-reorder"></i>
									</button>
								</div>
								<div class="box-hide-me box-content collapse out">
									<legend class="lead">
										Box Code
									</legend>
									<code style="background: none; border: none;"><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"box"</span><span class="nt">&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"box-header"</span><span class="nt">&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"icon-sign-blank"</span><span class="nt">&gt;&lt;/i&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;h5&gt;</span>Sample Box<span class="nt">&lt;/h5&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;button</span> <span class="na">class=</span><span class="s">"btn btn-box-right"</span> <span class="na">data-toggle=</span><span class="s">"collapse"</span> <span class="na">data-target=</span><span class="s">".box-hide-me"</span><span class="nt">&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"icon-reorder"</span><span class="nt">&gt;&lt;/i&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;/button&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;/div&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"box-hide-me box-content collapse out"</span><span class="nt">&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;legend</span> <span class="na">class=</span><span class="s">"lead"</span><span class="nt">&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Box Code
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;/legend&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;/div&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"box-footer"</span><span class="nt">&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;h5&gt;</span>Sample Footer<span class="nt">&lt;/h5&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nt">&lt;/div&gt;</span>
										<br/>
										<span class="nt">&lt;/div&gt;</span>
									</code>
								</div>
								<div class="box-footer">
									<h5>Sample Footer</h5>
								</div>
							</div>
						</div>
						
						<div class="span8">
							<div class="box">
								<div class="box-header">
									<i class="icon-sign-blank"></i>
									<h5>Map of Australia</h5>
								</div>
								<div class="box-content box-table">
									<div id="map-container"><div id="map"> </div></div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span16">
							<div class="well well-small well-shadow">
								<legend class="lead">
									Shadow Well<code>
										class="well well-small well-shadow"</code>
								</legend>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span16">
							<div class="box">
								<div class="box-content">
									<legend class="lead">
										Box Component
									</legend>
									<code style="background: none;border: none;"><span class="nt">&lt;div</span>
										<span class="na">class=</span><span>"box"</span><span>&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>&lt;div</span> <span>class=</span><span>"box-content"</span><span>&gt;</span>
										<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>&lt;/div&gt;</span>
										<br/>
										<span>&lt;/div&gt;</span>
									</code>
									<br/>
								</div>
							</div>
						</div>
					</div>
				</section>

			</div>
		</div>

		<div id="spinner" class="spinner" style="display:none;">
			Loading&hellip;
		</div>

		<footer class="application-footer">
			<div class="container">
				<p>
					Focus Shopfit
				</p>
				<div class="disclaimer">
					<p>
						All right reserved.
					</p>
					<p>
						Copyright © 2014
					</p>
				</div>
			</div>
		</footer>

		<script src="js/bootstrap/bootstrap-transition.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-alert.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-modal.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-dropdown.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-scrollspy.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-tab.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-tooltip.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-popover.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-button.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-collapse.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-carousel.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-typeahead.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-affix.js" type="text/javascript" ></script>
		<script src="js/bootstrap/bootstrap-datepicker.js" type="text/javascript" ></script>
		<script src="js/jquery/jquery-tablesorter.js" type="text/javascript" ></script>
		<script src="js/jquery/jquery-chosen.js" type="text/javascript" ></script>
		<script src="js/jquery/virtual-tour.js" type="text/javascript" ></script>
		<script type="text/javascript">
			$(function() {
				$('#sample-table').tablesorter();
				$('#datepicker').datepicker();
				$(".chosen").chosen();
			});
		</script>
		
		
		<script>
		var chart1 = c3.generate({
			bindto : '#chart1',
        data: {
          columns: [
            ['data1', 30, 20, 50, 40, 60, 50],
            ['data2', 200, 130, 90, 240, 130, 220],
            ['data3', 300, 200, 160, 400, 250, 250],
            ['data4', 200, 130, 90, 240, 130, 220],
            ['data5', 130, 120, 150, 140, 160, 150],
            ['data6', 90, 70, 20, 50, 60, 120],
          ],
          types: {
            data1: 'bar',
            data2: 'bar',
            data3: 'spline',
            data4: 'line',
            data5: 'bar',
            data6: 'area'
          },
          groups: [
            ['data1','data2']
          ]
        },
        axis: {
          x: {
            type: 'categorized'
          }
        }
    });
    

			
 var chart3 = c3.generate({
				bindto : '#chart3',
        data: {
          xs: {
            setosa: 'setosa_x',
            versicolor: 'versicolor_x',
            virginica: 'virginica_x'
          },
          columns: [
            ["setosa_x", 3.5, 3.0, 3.2, 3.1, 3.6, 3.9, 3.4, 3.4, 2.9, 3.1, 3.7, 3.4, 3.0, 3.0, 4.0, 4.4, 3.9, 3.5, 3.8, 3.8, 3.4, 3.7, 3.6, 3.3, 3.4, 3.0, 3.4, 3.5, 3.4, 3.2, 3.1, 3.4, 4.1, 4.2, 3.1, 3.2, 3.5, 3.6, 3.0, 3.4, 3.5, 2.3, 3.2, 3.5, 3.8, 3.0, 3.8, 3.2, 3.7, 3.3],
            ["versicolor_x", 3.2, 3.2, 3.1, 2.3, 2.8, 2.8, 3.3, 2.4, 2.9, 2.7, 2.0, 3.0, 2.2, 2.9, 2.9, 3.1, 3.0, 2.7, 2.2, 2.5, 3.2, 2.8, 2.5, 2.8, 2.9, 3.0, 2.8, 3.0, 2.9, 2.6, 2.4, 2.4, 2.7, 2.7, 3.0, 3.4, 3.1, 2.3, 3.0, 2.5, 2.6, 3.0, 2.6, 2.3, 2.7, 3.0, 2.9, 2.9, 2.5, 2.8],
            ["virginica_x", 3.3, 2.7, 3.0, 2.9, 3.0, 3.0, 2.5, 2.9, 2.5, 3.6, 3.2, 2.7, 3.0, 2.5, 2.8, 3.2, 3.0, 3.8, 2.6, 2.2, 3.2, 2.8, 2.8, 2.7, 3.3, 3.2, 2.8, 3.0, 2.8, 3.0, 2.8, 3.8, 2.8, 2.8, 2.6, 3.0, 3.4, 3.1, 3.0, 3.1, 3.1, 3.1, 2.7, 3.2, 3.3, 3.0, 2.5, 3.0, 3.4, 3.0],
            ["setosa", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
            ["versicolor", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
            ["virginica", 2.5, 1.9, 2.1, 1.8, 2.2, 2.1, 1.7, 1.8, 1.8, 2.5, 2.0, 1.9, 2.1, 2.0, 2.4, 2.3, 1.8, 2.2, 2.3, 1.5, 2.3, 2.0, 2.0, 1.8, 2.1, 1.8, 1.8, 1.8, 2.1, 1.6, 1.9, 2.0, 2.2, 1.5, 1.4, 2.3, 2.4, 1.8, 1.8, 2.1, 2.4, 2.3, 1.9, 2.3, 2.5, 2.3, 1.9, 2.0, 2.3, 1.8],
//            ["setosa", 30],
//            ["versicolor", 40],
//            ["virginica", 50],
          ],
          type : 'donut',
//          type : 'pie',
        },
        axis: {
          x: {
            label: 'Sepal.Width'
          },
          y: {
            label: 'Petal.Width'
          }
        },
        donut: {
          label: {
//            format: function (d, ratio) { return ""; }
          },
          title: "Iris Petal Width",
          onmouseover: function (d, i) { console.log(d, i); },
          onmouseout: function (d, i) { console.log(d, i); },
          onclick: function (d, i) { console.log(d, i); },
        }
      });

      setTimeout(function () {
        chart.load({
          columns: [
            ['data1', 30, 20, 50, 40, 60, 50],
          ]
        });
      }, 1000);

      setTimeout(function () {
        chart.unload('virginica');
      }, 2000);
		</script>
		
		
		
		
		
	</body>
</html>