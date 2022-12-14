<?php
session_start();

if ($_SESSION["loggedin"] == true){
  if ($_SESSION['accessLevel'] != 'Admin' && $_SESSION['accessLevel'] != 'Gamekeeper') {
    header("Location: TreasureHunt.php");
    exit;
  }
}
else{
  header("Location: index.php");
  exit;
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>The Hunt</title>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="favicon.png" />

  <link rel="stylesheet" href="websiteStyling.css">

  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


  <!-- Slick CSS -->
  <link rel="stylesheet" type="text/css" href="slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />


  <!-- icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>
  <!-- Top nav bar -->
  <div id="topNavBar">
    <ul class="z-depth-1">
      <!-- Setting Button -->
      <li style="float: right;"><a href="javascript: settingsPage();" data-target="settingsPage"
          class="sidenav-trigger"><i class="material-icons">settings</i></a></li>

      <!-- Help Button -->
      <li style="float: left;"><a href="javascript: helpPage();" id="helpButton" data-target="helpPage"
          class="sidenav-trigger"><i class="material-icons">help</i></a></li>
    </ul>
  </div>

  <!-- Settings side menu -->
  <ul id="settingsPage" class="sidenav fixed right-aligned">
    <div>
      <a href="#!" class="sidenav-close"><i class="material-icons md-36">close</i></a>
    </div>

    <li>
      <div class="user-view">
        <h2>Settings</h2>
      </div>
    </li>

    <!-- Night Mode option in settings    -->
    <div class="switch">
		<li><a style="display: inline-block" href="javascript:checkTime(); tickBox();"><i class="material-icons">wb_sunny</i>Night mode</a>
			<label>
				<input id="checkBoxNightMode" onchange="checkTime()" type="checkbox">
				<span style="float: right; margin: 17px;" class="lever"></span>
			</label>
		</li>
    </div>

	<!-- Animations option in settings -->
    <div class="switch">
		<li><a style="display: inline-block" href="javascript:toggleMarkerAnimations(); tickBox2();"><i class="material-icons">directions_run</i>Animations</a>
			<label>
				<input id="checkBoxAnimations" onchange="toggleMarkerAnimations()" type="checkbox" checked = "true">
				<span style="float: right; margin: 17px;" class="lever"></span>
			</label>
		</li>
    </div>

	<!-- Marker names option in settings    -->
    <div class="switch">
		<li><a style="display: inline-block" href="javascript:toggleMarkerNames(); tickBox3();"><i class="material-icons">pin_drop</i>Pin names</a>
			<label>
				<input id="checkBoxMarkerNames" onchange="toggleMarkerNames()" type="checkbox">
				<span style="float: right; margin: 17px;" class="lever"></span>
			</label>
		</li>
    </div>

	<!-- Help/hints for how to use the app (not used yet - remove later if not used at all)    -->
    <div class="switch">
		<li><a style="display: inline-block" href="javascript:toggleHints(); tickBox4();"><i class="material-icons">info</i>Hints</a>
			<label>
				<input id="checkBoxHints" onchange="toggleHints()" type="checkbox" checked = "true">
				<span style="float: right; margin: 17px;" class="lever"></span>
			</label>
		</li>
    </div>

	<!-- Marker opacity    -->
    <div class="switch">
		<li><a style="display: inline-block" href="javascript:toggleMarkerOpacity(); tickBox5();"><i class="material-icons">person_pin_circle</i>Transparent pins</a>
			<label>
				<input id="checkBoxMarkerOpacity" onchange="toggleMarkerOpacity()" type="checkbox">
				<span style="float: right; margin: 17px;" class="lever"></span>
			</label>
		</li>
    </div>

    <li>
      <div class="divider"></div>
    </li>
    <li><a class="subheader">Account & Other</a></li>
    <li><a href="logout.php"><i class="material-icons">directions_run</i>Logout</a></li>
    <li><a href="tel:01392723999"><i class="material-icons">phone</i>Non-Critical Estate Patrol</a></li>
    <li><a onclick="bottomNavGoTo(3)"><i class="material-icons">contact_support</i>FAQ</a></li>

	<li>
      <div class="divider"></div>
    </li>
    <li><a class="subheader">Gamekeeper Tools</a></li>

    <li><a onclick="saveRoute()">Save Route</a></li>

    <?php if ($_SESSION['accessLevel'] == 'Admin') { ?>
      <li><a href="adminPage.php">Admin Page</a></li>
    <?php } ?>
    <li><a href="TreasureHunt.php">Return to TreasureHunt</a></li>

  </ul>

  <br>
  <!-- <br>
   <div> this somehow fixes weird bug where map dissapears lol?? -->
  <div>
    <h1>
    </h1>
  </div>


  <div class="carousel-pages">
    <div class="carousel-page">
      <!-- Page 1: Google Maps -->
      <div class="map-container">
        <div id="googleMap" class="map"></div>
      </div>
    </div>
    <div class="carousel-page">
      <!-- Page 2: QR Scanner -->
      <div id="loadingMessage">???? Unable to access video stream (please make sure you have a webcam enabled)</div>
      <div class="canvas-container">
        <canvas id="canvas" hidden=""></canvas>
      </div>
      <div id="output" hidden="">
        <div id="debugMessage">test</div>
        <div id="outputMessage">No QR code detected.</div>
        <div hidden=""><b>Data:</b> <span id="outputData"></span></div>
      </div>
    </div>
    <div class="carousel-page">
      <!-- Page 3: Leaderboard -->
      <div class="container">
        <div class="score-section">
          <h6>Score: <span id="your-score">0</span></h6>
        </div>
        <table  class ="centered" width="450" >
          <!-- Table created to store data -->
          <thead>
            <tr>
              <th>Team</th>
              <th>Points</th>
            </tr>
          </thead>
          <tbody id="mytable">
          </tbody>
        </table>
      </div>

    </div>
    <div class="carousel-page">
      <!-- Page 3: FAQ -->
        <div class="container">
        <p>Frequently Asked Questions</p>
        <ul class="collapsible">
            <li>
              <div class="collapsible-header"><i class="material-icons">camera_enhance</i>How do I scan a QR code?
                <i class="caret material-icons">keyboard_arrow_down</i></div>
              <div class="collapsible-body">
                <span>To scan a QR code and verify that you
                 have been to the location please click the icon in the shape of a circle at the centre of the
                  bottom bar and use the pop-up camera to scan the QR code.
                </span>
              </div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons">place</i>How do I know I am getting close to the waypoint?
                <i class="caret material-icons">keyboard_arrow_down</i></div>
              <div class="collapsible-body">
                <span>If you are struggling or think
                 you are lost we have made it so your current location is displayed
                  as a blue marker in the map to help you navigate. Updating in real time
                  it should allow you to see where you are at any given time.
                  </span>
              </div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons">whatshot</i>How can I check how I am doing against other teams?
                 <i class="caret material-icons">keyboard_arrow_down</i></div>
              <div class="collapsible-body">
                <span>By clicking on the icon in the bottom right of the page
                the Leaderboard will be able to show how you're doing against other teams.
                </span>
              </div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons">place</i>My current location isnt showing?
                <i class="caret material-icons">keyboard_arrow_down</i></div>
              <div class="collapsible-body">
                <span>Please go to your browser settings and ensure
                you've given us permission to use your location. If you don't recieve the option to give location
                when entering the site please try reload the page or seek help.
                </span>
              </div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons">place</i>Need some help with finding the treasure?
                <i class="caret material-icons">keyboard_arrow_down</i></div>
              <div class="collapsible-body">
                <span>To get a clue, please click the treasure chest marking the treasure and click the
                  green "show clue" button to get a clue to help you find the place. Please note
                  that this could cost you quite a few points.
                </span>
              </div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons">place</i>An error on QR scanner page?
                <i class="caret material-icons">keyboard_arrow_down</i></div>
              <div class="collapsible-body">
                <span>If you get the message "Unable to access video stream" on the camera,
                 please make sure you have camera access enabled for your browser and for our website.
                </span>
              </div>
            </li>
          </ul>

      </div>
    </div>
  </div>

  <!-- Bottom Nav Bar -->
  <footer class="page-footer">
    <div class="container">
      <div class="row">
        <div class="bottom-nav">
          <div class="col s12" style="padding-left:0px!important;padding-right:0px!important;">
            <ul class="tabs tabs-fixed-width transparent white-text">
              <li class="tab col s3 white-text"><a href="javascript: bottomNavGoTo(0);" class="active black-text"><i
                    class="material-icons">explore</i></a></li>
              <li class="tab col s3"><a href="javascript: bottomNavGoTo(1);" class="black-text"><i
                    class="material-icons" style="font-size:50px;">adjust</i></a></li>
              <li class="tab col s3"><a href="javascript: bottomNavGoTo(2);" class="black-text"><i
                    class="material-icons">account_circle</i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- <button type="button" class="btn btn-primary" onclick="checkTime()">Change Colour Mode</button> -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="slick/slick.js"></script>

  <script src="map_themes.js"></script>
  <script src="gamekeeper_map.js"></script>
  <script src="script.js"></script>
  <script src="clues_script.js"></script>
  <script src="score_script.js"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1BcEMRCURawddT4GEKPVl_NXxRwPyRrQ&callback=myMap">
  </script>
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="jsQR.js"></script>
  <script src="camera.js"></script>

</body>

</html>
