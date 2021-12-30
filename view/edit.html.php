<?php
/**
 * The edit view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: edit.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.pm
 */
?>

<!-- Load Leaflet from CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@3.0.2/dist/esri-leaflet.js"
    integrity="sha512-myckXhaJsP7Q7MZva03Tfme/MSF5a6HC2xryjAM4FxPLHGqlh5VALCbywHnzs2uPoF/4G/QVXyYDDSkp5nPfig=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.0.0/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@3.0.0/dist/esri-leaflet-geocoder.js"
    integrity="sha512-vZbMwAf1/rpBExyV27ey3zAEwxelsO4Nf+mfT7s6VTJPUbYmD2KSuTRXTxOFhIYqhajaBU+X5PuFK1QJ1U9Myg=="
    crossorigin=""></script>

<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('noProject', ($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') ? true : false);?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <span class='label label-id'><?php echo $product->id;?></span>
        <?php echo html::a($this->createLink('product', 'view', 'product=' . $product->id), $product->name, '', "title='$product->name'");?>
        <small><?php echo $lang->arrow . ' ' . $lang->product->edit;?></small>
      </h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->product->name;?></th>
            <td class='w-p40-f'><?php echo html::input('name', $product->name, "class='form-control' required");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->code;?></th>
            <td><?php echo html::input('code', $product->code, "class='form-control' required");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->line;?></th>
            <td><?php echo html::select('line', $lines, $product->line, "class='form-control chosen'");?></td>
            <td><?php if(!$lines) common::printLink('tree', 'browse', 'rootID=&view=line', $lang->tree->manageLine);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->PO;?></th>
            <td><?php echo html::select('PO', $poUsers, $product->PO, "class='form-control chosen'");?></td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->QD;?></th>
            <td><?php echo html::select('QD', $qdUsers, $product->QD, "class='form-control chosen'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->RD;?></th>
            <td><?php echo html::select('RD', $rdUsers, $product->RD, "class='form-control chosen'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->location;?></th> 
            <td><?php echo html::input('buildingLat', $product->buildingLat , "class='form-control' placeholder='Latitude (e.g. 22.123456)' step='0.000001' " );?></div></td>
            <td><?php echo html::input('buildingLng', $product->buildingLng , "class='form-control' placeholder='Longitude (e.g. 114.123456)' step='0.000001' " );?></div></td>
          </tr>
          <tr> 
            <th><?php echo $lang->product->map;?></th> 
            <td colspan="3"><div id="mapid"></div></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->type;?></th>
            <td><?php echo html::select('type', $lang->product->typeList, $product->type, "class='form-control'");?></td><td></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->status;?></th>
            <td><?php echo html::select('status', $lang->product->statusList, $product->status, "class='form-control'");?></td><td></td>
          </tr>  
          <?php $this->printExtendFields($product, 'table');?>
          <tr>
            <th><?php echo $lang->product->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($product->desc), "rows='8' class='form-control'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->acl;?></th>
            <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->product->aclList, $product->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
          </tr>  
          <tr id='whitelistBox' <?php if($product->acl != 'custom') echo "class='hidden'";?>>
            <th><?php echo $lang->product->whitelist;?></th>
            <td colspan='2'><?php echo html::checkbox('whitelist', $groups, $product->whitelist);?></td>
          </tr>  
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
// resize map
const mapdiv = document.getElementById('mapid');
var margin;
if (document.all) {
  margin = parseInt(document.body.currentStyle.marginTop, 10) + parseInt(document.body.currentStyle.marginBottom, 10);
} else {
  margin = parseInt(document.defaultView.getComputedStyle(document.body, '').getPropertyValue('margin-top')) + parseInt(document.defaultView.getComputedStyle(document.body, '').getPropertyValue('margin-bottom'));
}
mapdiv.style.height = (window.innerHeight - margin) + 'px';

var apikey = 'c5bb2421a812463bb11d9bb2807338bd' //api.hkmapservice.gov.hk  key given by landsd

var buildingLat = <?php echo json_encode($product->buildingLat);?>; //get building location from database
var buildingLng = <?php echo json_encode($product->buildingLng);?>;
console.log(buildingLat);
console.log(buildingLng);

/////
var map = L.map('mapid').setView([buildingLat, buildingLng], 11);

// search bar
L.esri.Geocoding.Suggest.prototype.params.key = apikey;
L.esri.Geocoding.Geocode.prototype.params.key = apikey;
L.esri.Service.prototype.metadata = function(callback, context) {
  return this._request('get', '', {
    key: apikey
  }, callback, context);
}

L.tileLayer('https://api.hkmapservice.gov.hk/osm/xyz/basemap/WGS84/tile/{z}/{x}/{y}.png?key=' + apikey, {  attribution: "<a href='https://api.portal.hkmapservice.gov.hk/disclaimer' target='_blank'>&copy; Map from Lands Department <img src='https://api.hkmapservice.gov.hk/mapapi/landsdlogo.jpg' style='width:25px;height:25px'/></a>",
  maxZoom: 19,
}).addTo(map);

L.tileLayer('https://api.hkmapservice.gov.hk/osm/xyz/label-tc/WGS84/tile/{z}/{x}/{y}.png?key=' + apikey, {
  minZoom: 10,
  maxZoom: 19,
}).addTo(map);

var geocodeAddress = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/loc/address',
  label: 'Address Point',
  maxResults: 15
});

var geocodeBuilding = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib1000/buildings/building',
  label: 'Building',
  maxResults: 15
});
var geocodeBuildingLic = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ic1000/buildinglicence',
  label: 'Building Licence',
  maxResults: 15
});
var geocodeGeocomm = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/loc/geocomm',
  label: 'Geo Community',
  maxResults: 15
});
var geocodePlacePoint = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib5000/poi/placepoint',
  label: 'Place',
  maxResults: 15
});
var geocodePoiPoint = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib5000/poi/poipoint',
  label: 'POI',
  maxResults: 15
});


var geocodeSite = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib1000/buildings/site',
  label: 'Site',
  maxResults: 15
});

var geocodeSubSite = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib1000/buildings/subsite',
  label: 'SubSite',
  maxResults: 15
});

var geocodeLot = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ic1000/lot',
  label: 'LOT',
  maxResults: 15
});

var geocodeTenancyPoly = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc//ic1000/tenancypoly',
  label: 'LOT',
  maxResults: 15
});

var geocodeGLA = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ic1000/gla',
  label: 'GLA',
  maxResults: 15
});

var geocodeVGS = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ls/vacantgovsite',
  label: 'VGS',
  maxResults: 15
});
 
var geocodeStInt = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/loc/streetintersection',
  label: 'Streets Intersection',
  maxResults: 15
});

var geocodeStreets = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib1000/transportation/streetcentrelines',
  label: 'Streets',
  maxResults: 15
});

var geocodeLocalControl = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/sc/localcontrol',
  label: 'Local Control',
  maxResults: 15
});

var geocodeHControl = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/sc/GeodeticHControl',
  label: 'GeodeticHControl',
  maxResults: 15
});

var geocodeVControl = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/sc/GeodeticVControl',
  label: 'GeodeticVControl',
  maxResults: 15
});

var geocodeUtility = L.esri.Geocoding.geocodeServiceProvider({
  url: 'https://api.hkmapservice.gov.hk/ags/gc/ib1000/utilities/utilitypoint',
  label: 'Utility Point',
  maxResults: 50
});

// get searchControl
var searchControl = L.esri.Geocoding.geosearch({
    collapseAfterResult: true,
    allowMultipleResults: true,
    providers: [geocodeAddress, geocodeBuilding, geocodeBuildingLic, geocodeGeocomm, geocodePlacePoint, geocodePoiPoint, geocodeSite, geocodeSubSite, geocodeStInt, geocodeStreets, geocodeLot, geocodeTenancyPoly, geocodeGLA, geocodeVGS, geocodeUtility],
    useMapBounds: false,
    zoomToResult: true
  }).addTo(map);


// show the result from searching bar
var results = L.layerGroup().addTo(map);
searchControl.on('results', function(data) {
  results.clearLayers();

// show the red anchor on map
  var redIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    //shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });
 /*  L.marker(data.results[i].latlng, {
      icon: redIcon
    }).bindPopup(data.results[i].properties.Match_addr + '\n' + data.results[i].properties.Descr) */


  for (var i = data.results.length - 1; i >= 0; i--) {
    var resultsM = L.marker(data.results[i].latlng, {
      icon: redIcon
    }).bindPopup(data.results[i].properties.Match_addr + '\n' + data.results[i].properties.Descr)
    results.addLayer(resultsM);
  }
});




var flagIcon = L.icon({
	//iconUrl: 'iconsdb.com/icons/preview/blue/marker-xxl.png',
  //shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
	
	iconSize: [60, 100],
	shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

// setup the layout of markers
var marker = L.marker([buildingLat, buildingLng], {
	//icon: flagIcon,
    draggable:true,
    autoPan: true,
    autoPanPadding: [200, 200],
    autoPanSpeed: 25
});
marker.addTo(map);
marker.bindPopup(marker.getLatLng().toString()).openPopup();



var geojsonFeature = {
    "type": "Feature",
    "properties": {
        "name": "Coors Field",
        "amenity": "Baseball Stadium",
        "popupContent": "This is where the Rockies play!"
    },
    "geometry": {
        "type": "Point",
        "coordinates": [22.28434, 114.13743]
    }
};

var mystyle = {
	"color":  "#ff7800",
	"weight": 5,
	"opacity": 0.65
};
	

L.geoJSON(geojsonFeature, {style: mystyle }).addTo(map);


function onMapClick(e) {
        
    if(marker.getLatLng()=='LatLng(22.325697, 114.20369)'){
      //marker = L.marker(e.latlng/*, {icon: flagIcon}*/, {draggable:true})
      marker.setLatLng(e.latlng);
      marker.addTo(map);
      marker.bindPopup(e.latlng.toString()).openPopup();
      var x = marker.getLatLng();
      document.getElementById("buildingLat").value = x.lat.toFixed(6); //fixed lat to 6 d.p.
      document.getElementById("buildingLng").value = x.lng.toFixed(6); //fixed lng to 6 d.p.
    }else{
      if(marker['addTomap']==null){     //if marker not exist-> add it on map
        marker.addTo(map);
      }
      marker.setLatLng(e.latlng);
      marker.bindPopup(e.latlng.toString()).openPopup();
      var x = marker.getLatLng();
      document.getElementById("buildingLat").value = x.lat.toFixed(6);
      document.getElementById("buildingLng").value = x.lng.toFixed(6);
    }
}

//listener
map.on('click', onMapClick);
//resultsM.on('dbclick', function(e){
  //console.log();
//})
marker.on('mouseup', function(e){
  marker.setLatLng(e.latlng);
  marker.bindPopup(marker.getLatLng().toString()).openPopup();
  var x = marker.getLatLng();
  document.getElementById("buildingLat").value = x.lat.toFixed(6);
  document.getElementById("buildingLng").value = x.lng.toFixed(6);
})

/*To onchange user inputed latlng to the map, add pointer to the map */
function syncLoc(e){
  //var latlng = L.latLng(e);
  var lat = document.getElementById("buildingLat").value;
  var lng = document.getElementById("buildingLng").value;
  if(lat!==''&&lng!==''){
    var latlng = L.latLng(lat, lng);
    marker.setLatLng(latlng);
    marker.bindPopup(marker.getLatLng().toString()).openPopup();
    if(marker['addTomap']==null){
        marker.addTo(map);
      }
  }
  //marker.setLatLng(latlng);
  //marker.bindPopup(latlng.toString()).openPopup();
}



/* Set location to number input*/
document.getElementById("buildingLat").type="number";
document.getElementById("buildingLng").type="number";
/* Get input form id to onchange inputed latlng to map*/
document.getElementById("buildingLat").onchange = function() {syncLoc()};
document.getElementById("buildingLng").onchange = function() {syncLoc()};
</script>

<?php include '../../common/view/footer.html.php';?>
