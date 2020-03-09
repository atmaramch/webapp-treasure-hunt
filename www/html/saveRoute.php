<?php
require("connection.php");

$waypointsArray = []; //TODO pass in data from inputted array

//grabs the routeID of the last entry in the database, puts in newRouteID
$newRouteID;
$newWaypointID;
$lastRoute = $conn->query("SELECT * FROM routes ORDER by routeID DESC LIMIT 1");
if ($lastRoute->num_rows == 1) {
    // output data of each row
    while($row = $lastRoute->fetch_assoc()) {
        $newRouteID = $row["routeID"] + 1;
    }
}

//grabs last waypointID and stores it in newRouteID
$lastWaypoint = $conn->query("SELECT * FROM waypoints ORDER by waypointID DESC LIMIT 1");
if ($lastWaypoint->num_rows == 1) {
    // output data of each row
    while($row = $lastWaypoint->fetch_assoc()) {
        $newWaypointID = $row["waypointID"] + 1;
    }
}
//TODO delete
echo $newRouteID." ";
echo $newWaypointID." ";

//TEMPLATE routeName and gamekeeperID need to be fetched from form that submits route
$newRouteSQL = "INSERT INTO routes VALUES ($newRouteID,'routeName','gamekeeperID')";

//TEMPLATE loads values from input array into string for SQL statement
$VALUES = "";
for ($i=0; $i<count($waypointsArray); $i++){
  $VALUES.="($newWaypointID,'locationFromPassedArray',$newRouteID,'prize lol',$i+1),";
}

//TEMPLATE loads found values into array
$waypointSQL = "INSERT INTO waypoints VALUES".$VALUES;

//TEMPLATE, decomment when implemented fully
// $conn->query($waypointSQL);

//TODO PHP for adding clues for each waypoint, will require another passed array
?>