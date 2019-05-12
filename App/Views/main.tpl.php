<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/Public/css/style.css" type="text/css">
    <meta charset="UTF-8">
    <title><?= $title ?></title>
</head>
<body>
<div id="title" class="title-panel">
    <div id="arrow-back">←</div>
    <div class="title-name"><?= $title ?></div>
</div>
<div class="body-panel">
    <div class="waypoint-panel">
        <? require_once($subTpl) ?>
    </div>
    <div class="menu-items">
        <input type=button id="showlistpoint" value="Waypoints list">
        <input type=button id="addwaypoint" value="Add Waypoint">
        <input type=button id="deletewaypoint" value="Clear current waypoint">
        <input type=button id="deletewaypoints" value="Clear waypoints">
    </div>
    <div class="metric-items">
        <div>Speed of drone: <input type="text" id="speeddrone" value="40"> km/h</div>
        <div id="totaldistance">Total distance: </div>
        <div id="totaltime">Total time: </div>
    </div>
</div>
</body>
</html>