<div id="map_container">
    <div id="newaddress">
        <label>Add waypoint</label>
        <input id="addressname"
               class="controls"
               type="text"
               placeholder="">
        <input type="button" class="cancelOK" id="okaddaddress" value="OK">
        <input type="button" class="cancelOK" id="closeaddaddress" value="Cancel">
    </div>
    <div id="map"></div>
    <div id="infowindow-content">
        <span id="place-name" class="title"></span>
    </div>
    <div class="layoutcontrol">
        <div class="controls maptype-control maptype-control-is-map">
            <img id="layout" src="/Public/svg/layout.svg" title="hybrid">
        </div>
    </div>
</div>
<div id="list_container">
</div>
<script src="/Public/js/waypoints.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?language=en&region=GB&key=AIzaSyD4Bsz2k7ONfw4lf8rTVMD_uCdFda5Yz78&libraries=places,geometry&callback=mainApp"
        async defer>
</script>