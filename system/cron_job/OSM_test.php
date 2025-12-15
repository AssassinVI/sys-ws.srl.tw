<!DOCTYPE HTML>
<html lang="zh-tw">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
      html, body {
        height: 100%;
        padding: 0;
        margin: 0;
      }
      #map {
        /* configure the size of the map */
        width: 90%;
        height: 90%;
        margin: auto;
      }

      /* 縣市界線CSS */
      .town_polygon{
        stroke:#fff0;
        fill: #fff0;
        transition: all 0.3s;
      }
      .town_polygon.active{
        stroke:#0f506d;
        fill: #ffcc1d7a;
      }

      /* 縣市座標CSS */
      .town_div{
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        background: none !important;
        border:none !important;
        width: auto !important;
        height: auto !important;
        pointer-events: none !important;
      }
      .town_icon{
        width: 80px;
        height: 80px;
        border:3px solid #046ea0;
        background-color: #046ea0;
        color: #fff;
        font-size: 17px;
        font-weight: 600;
        letter-spacing: 1px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 100%;
        
        transition: all 0.3s;
      }
      .town_icon.active{
        background-color: #fff;
        color:#046ea0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function () {

      //-- 建立地圖物件 --
      var map = L.map('map', {
        center: [24.990243245274094, 121.31490013945835],
        zoom: 15,
        zoomControl:false
      });

      // 加入 OpenStreetMap 地圖磚塊
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        minZoom: 13,
        attribution: '&copy; <a target="_blank" href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      // 顯示比例尺
      L.control.scale({imperial: false, metric: true}).addTo(map);

      // 顯示放大縮小
      L.control.zoom({
        position:'topright',
        zoomInTitle:'放大',
        zoomOutTitle:'縮小',
      }).addTo(map);


      // 自定義座標 icon
      const customIcon = L.icon({
            iconUrl: 'OMS_test/old_0_4.svg',
            iconSize: [45, 'auto'],
          });

      // 加入座標
      L.marker([24.990243245274094,121.31490013945835], {
        icon: customIcon
      }).bindPopup('公司位置').addTo(map);


      
        //-- 獲取全台鄉鎮市區界線 --
        fetch('OMS_test/TOWN_line_map.json')

        .then(function(response) {
          return response.json();
        })
        .then(function(myJson) {
          let town_arr=myJson.features;
          let new_arr=town_arr.filter(twon => twon.properties.COUNTYNAME=='桃園市');
          console.log(new_arr);

          //-- 界線經緯度陣列 --
          let polygon_latlng=[];
          new_arr.forEach(town => {
            //-- 經緯度反轉 --
            //console.log(town);
            let new_latlng=town.geometry.coordinates[0].map(latlng => {
              let lat=latlng[1];
              let lng=latlng[0];
              return [lat, lng];
            });
            let town_dt={
              latlng: new_latlng,
              county_name: town.properties.COUNTYNAME,
              town_name: town.properties.TOWNNAME
            };
            polygon_latlng.push(town_dt);
            //console.log(polygon_latlng);
          });

          return polygon_latlng;
        })
        .then(function (town_dt) {

           //-- 繪出多邊形 --
           let town_p_arr=[];
           town_dt.forEach(town => {
              let polygon = L.polygon(town.latlng, {
                              className: 'town_polygon'
                            }).addTo(map);

              town_p_arr.push(polygon);
                            
              
              //-- 多邊形中心 --
              let polygon_center=polygon.getCenter();
              let town_icon=L.divIcon({
                className: 'town_div',
                html: `<div class="town_icon"><span>${town.town_name}</span></div>`
              });
              let town_marker= L.marker([polygon_center.lat, polygon_center.lng], {icon: town_icon}).addTo(map);


              //console.log(town_marker);

              polygon.on('mouseover', function (e) {
                $(town_marker._icon).find('.town_icon').addClass('active');
                $(e.target._path).addClass('active');
                //console.log(e.target._path);
                //console.log(town.county_name, town.town_name);
              });
              polygon.on('mouseout', function (e) {
                $(town_marker._icon).find('.town_icon').removeClass('active');
                $(e.target._path).removeClass('active');
              });
           });
    
          
        });


      });


    </script>
  </body>
</html>
