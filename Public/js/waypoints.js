function mainApp() {
    const RIGA = {
        lat: 56.95,
        lng: 24.05
    };
    var map,
        wayPoints = {
            markers: [],
            addresses: [],
            recId: [],
            needSynchronize: false
        },
        currentPlaceSelected = -1;

    initMap = function () {
        let coordinate = RIGA;
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: coordinate,
            mapTypeId: 'terrain',
            disableDefaultUI: true,
        });

        let initMapTypeControl = function () {
            var mapTypeControlDiv = document.querySelector('.maptype-control'),
                mapTypes = ['hybrid', 'roadmap'];

            document.getElementById('layout').addEventListener('click', function () {
                map.setMapTypeId(mapTypes[0]);
                mapTypes = mapTypes.reverse();
                this.setAttribute('title', mapTypes[0]);
            });

            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(
                mapTypeControlDiv);
        };
        initMapTypeControl(map);

        let newaddress = document.getElementById('addressname'),
            autocomplete = new google.maps.places.Autocomplete(newaddress);
        autocomplete.bindTo('bounds', map);
        autocomplete.setFields(['place_id', 'geometry', 'name', 'formatted_address']);

        let infowindow = new google.maps.InfoWindow(),
            infowindowContent = document.getElementById('infowindow-content');

        infowindow.setContent(infowindowContent);

        let addMarkerGeoCoder = function (place) {
            let num = wayPoints.markers.length;
            wayPoints.markers[num] = new google.maps.Marker({
                position: {lat: Number(place.geometry.location.lat()), lng: Number(place.geometry.location.lng())},
                map: map,
                label: {text: (num + 1).toString(), color: "white"},
            });
            wayPoints.markers[num].setPlace({
                placeId: place.place_id,
                location: {lat: Number(place.geometry.location.lat()), lng: Number(place.geometry.location.lng())}
            });
            wayPoints.markers[num].setVisible(true);
            wayPoints.addresses[num] = place.formatted_address;
            wayPoints.recId[num] = place.rec_id;
            wayPoints.markers[num].addListener('click', function () {
                infowindowContent.children['place-name'].textContent = wayPoints.addresses[num];
                infowindow.open(map, wayPoints.markers[num]);
                currentPlaceSelected = Number(num);
            });
        };

        /** The dialog of adding a new address of way point **/
        document.getElementById('addwaypoint').addEventListener('click', function () {
            document.getElementById('newaddress').style.display = 'block';
            document.getElementById('addressname').focus();
        });
        document.getElementById('okaddaddress').addEventListener("click", function () {
            dataBaseAction('getwaypointbyaddress',
                ['adr=' + document.getElementById('addressname').value]);
        });
        /** end dialog */

        function getPlaceByWayPoint(pointsArray) {
            var place = {};
            if (pointsArray.length > 0) {
                //TODO:
                //Like in task #5: ... The geo coding should be done on the server side.
                //Geo coding on local server from set of early added but deleted points.
            } else {
                // Primary geo coding possible on remote Google server, only.
                place = autocomplete.getPlace();
            }
            return place;
        }

        dataBaseAction = function (action, args) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', document.location.href +
                '/?action=' + action + '&async=true&' +
                args.join('&'), true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState != 4) return;
                if (xhr.status != 200) {
                    alert(xhr.status + ': ' + xhr.statusText);
                } else {
                    try {
                        var response = JSON.parse(xhr.responseText);
                    } catch (e) {
                        alert("Bad response in action: " + action + ': ' + e.message);
                    }
                    //TODO: make review to promise
                    switch (action) {
                        case 'getwaypointbyaddress':
                            let place = getPlaceByWayPoint(response);
                            document.getElementById('addressname').value = '';
                            document.getElementById('newaddress').style.display = 'none';
                            infowindow.close();
                            addMarkerGeoCoder(place);
                            dataBaseAction('addwaypoint',
                                ['lat=' + (place.geometry.location.lat()).toFixed(6),
                                    'lng=' + (place.geometry.location.lng()).toFixed(6),
                                    'adr=' + place.formatted_address,
                                    'placeid=' + place.place_id]);
                            map.setCenter({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
                            break;
                    }
                }
            };
            xhr.send();
        };
    };
    initMap();
}