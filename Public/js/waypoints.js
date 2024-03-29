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

        let calcTotalTime = function (totalDistance) {
                let totalTime = totalDistance / document.getElementById('speeddrone').value;
                return totalTime.toFixed(2);
            },

            fillMetric = function () {

                if (wayPoints.markers.length > 1) {
                    dataBaseAction('getoptimalway', []);
                } else {
                    document.getElementById('totaldistance').innerText = 'Total distance: - ';
                    document.getElementById('totaltime').innerText = 'Total time: - ';
                }
            },

            addMarkerGeoCoder = function (place) {
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
            },

            reFillMap = function () {
                for (let i = 0; i < wayPoints.markers.length; i++) {
                    wayPoints.markers[i].setMap(map);
                    wayPoints.markers[i].addListener('click', function () {
                        infowindowContent.children['place-name'].textContent = wayPoints.addresses[i];
                        infowindow.open(map, wayPoints.markers[i]);
                        currentPlaceSelected = Number(i);
                    });
                    wayPoints.markers[i].label = {text: (i + 1).toString(), color: "white"};
                }
            },

            delWayPoint = function (id) {
                for (let i = 0; i < wayPoints.markers.length; i++) {
                    wayPoints.markers[i].setMap(null);
                }
                wayPoints.markers.splice(Number(id), 1);
                wayPoints.addresses.splice(Number(id), 1);
                reFillMap();
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

        document.getElementById('closeaddaddress').addEventListener('click', function () {
            document.getElementById('newaddress').style.display = 'none';
            document.getElementById('addressname').value = '';
        });
        /** end dialog */


        document.getElementById('showlistpoint').addEventListener('click', function () {
            document.getElementById('map_container').style.display = 'none';
            document.getElementById('list_container').style.display = 'contents';
            currentPlaceSelected = -1;
            let itemTPL =
                "<div name='item' data-id='NUMBER'>\n" +
                "   <img src=\"/Public/svg/pin.png\">\n" +
                "   <h4>NUMBER</h4>\n" +
                "   <h5>ADDRESS</h5>\n" +
                "</div>\n",
                innerHTML = '',
                count = wayPoints.markers.length;

            for (let i = 0; i < count; i++) {
                tmpStr = String(itemTPL);
                tmpStr = tmpStr.replace(/NUMBER/g, i + 1);
                tmpStr = tmpStr.replace('ADDRESS', wayPoints.addresses[i]);
                innerHTML += tmpStr;
            }
            let newDiv = document.createElement('div');
            newDiv.innerHTML = innerHTML;
            document.getElementById('list_container').innerHTML = '';
            document.getElementById('list_container').appendChild(newDiv);
            document.getElementsByName('item').forEach(function (elem) {
                elem.addEventListener('click', function () {
                    if (this.hasAttribute('selected')) {
                        this.toggleAttribute('selected', false);
                    } else {
                        document.getElementsByName('item').forEach(function (el) {
                            el.toggleAttribute('selected', false);
                        });
                        this.toggleAttribute('selected', true);
                    }
                });
            });
            document.getElementsByClassName('title-name').item(0).textContent = 'Waypoints list';
            document.getElementById('arrow-back').style.display = 'block';
            document.getElementById('arrow-back').addEventListener('click', function () {
                this.style.display = 'none';
                document.getElementById('list_container').style.display = 'none';
                document.getElementById('map_container').style.display = 'block';
                document.getElementById('list_container').innerHTML = '';
                document.getElementsByClassName('title-name').item(0).textContent = 'Demo';
            })
        });

        document.getElementById('deletewaypoint').addEventListener('click', function () {
            if (currentPlaceSelected != -1) {
                dataBaseAction('deletewaypointdbbyid', ['id=' + wayPoints.recId[currentPlaceSelected]]);
                delWayPoint(currentPlaceSelected);
                currentPlaceSelected = -1;
                fillMetric();
                return true;
            }
            document.getElementsByName('item').forEach(function (elem) {
                if (elem.hasAttribute('selected')) {
                    let id = Number(elem.getAttribute('data-id')) - 1;
                    dataBaseAction('deletewaypointdbbyid', ['id=' + wayPoints.recId[id]]);
                    delWayPoint(id);
                    elem.remove();
                    fillMetric();
                    document.getElementById('arrow-back').click();
                }
            });
        });

        document.getElementById('deletewaypoints').addEventListener('click', function () {
            document.getElementById('list_container').innerHTML = '';
            deleteWayPointsFromMap();
            fillMetric();
            document.getElementById('arrow-back').click();
            dataBaseAction('deleteallwaypointdb', []);
        });

        function deleteWayPointsFromMap() {
            for (let i = 0; i < wayPoints.markers.length; i++) {
                wayPoints.markers[i].setMap(null);
            }
            wayPoints.markers.length = 0;
            wayPoints.addresses.length = 0;
            wayPoints.recId.length = 0;
        }

        function getPlaceByWayPoint(pointsArray) {
            var place = {};
            if (pointsArray.length > 0) {
                //Like in task #5: ... The geo coding should be done on the server side.
                //Geo coding on local server from set of early added but deleted points.
                place = {
                    "geometry":
                        {
                            "location":
                                {
                                    "lat": function () {
                                        return Number(pointsArray[0].Lat)
                                    },
                                    "lng": function () {
                                        return Number(pointsArray[0].Lng)
                                    },
                                }
                        },
                    "formatted_address": pointsArray[0].Address,
                    "place_id": pointsArray[0].PlaceId,
                    "rec_id": pointsArray[0].Id
                };
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
                            fillMetric();
                            break;

                        case 'getallwaypoints':
                            let points = response,
                                countPoints = points.length;
                            var placei = {};
                            for (let i = 0; i < countPoints; i++) {
                                placei = getPlaceByWayPoint([points[i]]);
                                addMarkerGeoCoder(placei);
                            }
                            fillMetric();
                            break;

                        case 'getpointstatistic':
                            wayPoints.needSynchronize = (wayPoints.markers.length !== Number(response[0]['Counter']));
                            console.log(wayPoints.markers.length, Number(response[0]['Counter']));
                            console.log('needSynchronize: ' + wayPoints.needSynchronize);
                            break;

                        case 'getoptimalway':
                           let totalDistance = response;
                            document.getElementById('totaldistance').innerText = 'Total distance: ' +
                                totalDistance.toFixed(2) + ' km';
                            let totalTime = calcTotalTime(totalDistance),
                                hours = ~~totalTime,
                                minutes = (totalTime - hours) * 60;
                            document.getElementById('totaltime').innerText = 'Total time: ' + hours +
                                ' h ' + Math.round(minutes) + ' min ';
                           break;
                    }
                }
            };
            xhr.send();
        };

        dataBaseAction('getallwaypoints', []);
        setInterval(function () {
            dataBaseAction('getpointstatistic', []);
            if (wayPoints.needSynchronize === true) {
                wayPoints.needSynchronize = false;
                deleteWayPointsFromMap();
                dataBaseAction('getallwaypoints', []);
                wayPoints.needSynchronize = false;
            }
        }, 3000);
    };

    initMap();
}