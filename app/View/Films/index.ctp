
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
  
    <link rel="stylesheet" href="http://zerosixthree.se/labs/video-header/css/style.css">

    <script type="text/javascript">
      $(document).ready(function() {
          var markers = [];
          var map , gmarkers;
          var gmarkers = [];
          $('#filmsList').dataTable({
              /*"fixedHeader": true,*/
              "bProcessing": true,
              "bServerSide": true,
              "sAjaxSource": "<?php echo $this->Html->Url(array('controller' => 'Films', 'action' => 'ajaxData')); ?>",
              "columnDefs": [
                { className: "hide", "targets": [ -1, -2 ] }
              ]
          });

          $('#filmsList')
            .on( 'order.dt',  function () { removeMarkers(); drawMarkers(); drawMap();  } )
            .on( 'search.dt', function () { removeMarkers(); drawMarkers(); drawMap(); } )
            .on( 'page.dt',   function () { removeMarkers(); drawMarkers(); drawMap();  } )
            .dataTable();

          function drawMarkers(){
            var mapMarkers;
              markers = [];
              $('#filmsList > tbody  > tr').each(function() {
                var tdArr = [];
                tdArr.push($(this).find('td:eq(3)').html());
                tdArr.push(parseFloat($(this).find('td:eq(-2)').html()));
                tdArr.push(parseFloat($(this).find('td:eq(-1)').html()));
                markers.push(tdArr);

                console.log(markers);
              });
          }



          function drawMap(){
            for (i = 0; i < markers.length; i++) {  
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(markers[i][1], markers[i][2]),
                        title: markers[i][0],
                        map: map
                    });
                    gmarkers.push(marker);
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(markers[i][0]);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
          }

          function removeMarkers(){
    for(i=0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
}

          function clearMap(){
            console.log(markers);
            for (i = 0; i < markers.length; i++) {  
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(markers[i][1], markers[i][2]),
                        map: map
                    });
                    marker.setMap(null);
                    
                }
          }

          function initialize() {
            var mapProp = {
              center:new google.maps.LatLng(42.879094,-97.381205),
              zoom:4,
              mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
          }
          google.maps.event.addDomListener(window, 'load', initialize);

          var infowindow = new google.maps.InfoWindow(), marker, i;

            

       });
    </script>
    <style type="text/css">
      .hide
      {
          display: none;
      }
    </style>
</head>
<body>

  <div id="googleMap" style="width:auto;height:380px;"></div>

  <?php
    echo $this->Form->create('AjaxForm');
        echo $this->Form->hidden('value');
    echo $this->Form->end();
  ?>


  <div class="container">
    <h1>Films in San Fancisco</h1>
    <table id="filmsList" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Release Year</th>
                <th>Locations</th>
                <th>Fun Facts</th>
                <th>Production Company</th>
                <th>Distributor</th>
                <th>Director</th>
                <th>Writer</th>
                <th>Actor 1</th>
                <th>Actor 2</th>
                <th>Actor 3</th>
                <th class="hide">latitude</th>
                <th class="hide">longitude</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="dataTables_empty">Loading data from server...</td>
            </tr>
        </tbody>
    </table>
  </div>


<script src="http://zerosixthree.se/labs/video-header/js/modernizr.js"></script>
<script src="http://zerosixthree.se/labs/video-header/js/script.js"></script>


</body>
</html>