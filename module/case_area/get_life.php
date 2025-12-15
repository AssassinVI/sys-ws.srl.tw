<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>獲取生活機能資料</title>
    <style>
        #list{
          display: flex;
          flex-wrap: wrap;
          gap: 2%;
          .item{
            flex: 0 1 32%;
            
          }
          .img_box{
            width: 100%;
            height: 150px;
            img{
                width: 100% !important;
                height: 100% !important;
                object-fit: cover;
            }
          }
        }
        #submit_btn{
          padding: 8px 15px;
          display: inline-block;
          background-color: #19649a;
          color: #fff;
          text-decoration: none;
          margin-bottom: 9px;
        }
        
    </style>
</head>
<body>
    <div class="submit_div">
      <a id="submit_btn" href="javascript:;">儲存資料</a>
    </div>
    <div id="map"></div>
    <div id="list"></div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmcZ9YTd68k4QYur5nowITqcI_kGZO5Ks&libraries=places&v=beta&loading=async&callback=initMap"
      async defer></script>
  <script>

    const nowUrl=new URL(window.location.href);
    const lat=parseFloat(nowUrl.searchParams.get('lat'));
    const lng=parseFloat(nowUrl.searchParams.get('lng'));
    const type=nowUrl.searchParams.get('type');
    const radius=parseFloat(nowUrl.searchParams.get('radius')) || 1000;
    const Tb_index=nowUrl.searchParams.get('Tb_index');
    let includedTypes=[];
    
    //-- 食衣住行 --
    switch (type) {
        case 'food':
          includedTypes=[ "restaurant"];
        break;
        case 'doctor':
          includedTypes=[ "hospital", "doctor", "pharmacy"];
        break;
        case 'lodging':
          includedTypes=[ "hotel", "lodging", "motel"];
        break;
        case 'traffic':
          includedTypes=[ "transit_depot", "train_station", "subway_station", "light_rail_station", "airport"];
        break;
        case 'school':
          includedTypes=[ "school", "library", "primary_school", "secondary_school", "university"];
        break;
        case 'fun':
          includedTypes=[ "shopping_mall", "department_store", "aquarium", "concert_hall", "cultural_center"];
        break;
        case 'park':
          includedTypes=[ "park", "national_park", "motel"];
        break;
        case 'bus_station':
          includedTypes=[ "bus_station", "bus_stop"];
        break;
        case 'convenience_store':
          includedTypes=[ "convenience_store", "market"];
        break;
        case 'cafe':
          includedTypes=[ "cafe", "cat_cafe", "dog_cafe"];
        break;
        case 'bank':
          includedTypes=["bank"];
        break;
        case 'gas_station':
          includedTypes=["gas_station"];
        break;
        case 'atm':
          includedTypes=["atm"];
        break;
    }

    const center = { lat: lat, lng: lng };
    let places_arr=[];

      async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { Place } = await google.maps.importLibrary("places");
        // const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        const map = new Map(document.getElementById("map"), {
          center,
          zoom: 15,
        });

        const request = {
          locationRestriction: {
            center,
            radius: radius,
          },
          includedTypes: includedTypes,
          maxResultCount: 12,
          language: "zh-TW",
          region: "TW",
          fields: ["displayName", "location", "formattedAddress", "rating", "userRatingCount", "photos", "internationalPhoneNumber"],
        };

        const { places } = await Place.searchNearby(request);
        renderResults(places, map);
        places_arr=places;
      }

      function renderResults(places, map) {
        const list = document.getElementById("list");
        list.innerHTML = "";

        places.forEach(place => {
          if (!place.location) return;

          // 如果有照片
          let photoUrl=''; 
          if (place.photos && place.photos.length > 0){
             photoUrl = place.photos[0].getURI({ maxWidth: 400 });
          }

          const el = document.createElement("div");
          el.className = "item";
          el.innerHTML = `
            <div class="img_box"><img src="${photoUrl}" style="width:200px;height:auto;"></div>
            <div><strong>${place.displayName ?? "未命名"}</strong></div>
            <div> ★ ${place.rating ?? "—"} (${place.userRatingCount ?? 0}則)</div>
            <div>${place.formattedAddress ?? ""}</div>
            <div>${place.internationalPhoneNumber ?? "無電話資訊"}</div>
          `;
          list.appendChild(el);
        });
      }

      window.initMap = initMap;


      $('#submit_btn').click(function (e) { 
        e.preventDefault();
        if(confirm("確定要儲存這些生活機能資料嗎？")){
          //  console.log(places_arr);
           let places_img=[];
           places_arr.forEach(place => {
             places_img.push(place.photos && place.photos.length > 0 ? place.photos[0].getURI({ maxWidth: 400 }) : '');
           });

          const data={
            type:'save_life',
            life_type:type,
            Tb_index: Tb_index,
            places:places_arr,
            places_img: places_img
          };
          //送出ajax
          $.ajax({
            type: "POST",
            url: "save_life.php",
            data: JSON.stringify(data),
            dataType: "json",
            success: function (response) {
              console.log(response);
              if(response.success){
                alert("資料儲存成功");
                //父視窗重新整理
                window.opener.location.reload();
                //關閉視窗
                window.close();
              }
              else{
                alert("資料儲存失敗");
              }
            }
          });
        }
      });
  </script>
</body>
</html>