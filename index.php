<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kabupaten Banyumas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    #map { height: 90vh; }
    .custom-popup { font-size: 14px; }
    #form-container { padding: 10px; }
    input, button { margin: 5px; padding: 5px; }
  </style>
</head>
<body>
  <h2>Kabupaten Banyumas</h2>
  <div id="map"></div>
  <div id="form-container">
    <h3>Tambah Fasilitas Kesehatan</h3>
    <input type="text" id="nama" placeholder="Nama Fasilitas">
    <input type="text" id="lat" placeholder="Latitude">
    <input type="text" id="lon" placeholder="Longitude">
    <button onclick="tambahFasilitas()">Tambah</button>
    <h4>Hitung Jarak (klik dua titik marker)</h4>
    <p id="output-jarak"></p>
  </div>

  <script>
    const map = L.map('map').setView([-7.4256, 109.2396], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(map);

    // Simulasi batas kecamatan (GeoJSON dummy)
    const dummyPolygon = {
      "type": "FeatureCollection",
      "features": [
        {
          "type": "Feature",
          "properties": {"name": "Kecamatan A"},
          "geometry": {
            "type": "Polygon",
            "coordinates": [[[109.2,-7.4],[109.3,-7.4],[109.3,-7.5],[109.2,-7.5],[109.2,-7.4]]]
          }
        }
      ]
    };

    L.geoJSON(dummyPolygon, {
      style: {color: 'blue', weight: 1, fillOpacity: 0.1},
      onEachFeature: (feature, layer) => {
        layer.bindPopup(`<b>${feature.properties.name}</b>`);
      }
    }).addTo(map);

    // Data fasilitas (sebagai pengganti CRUD DB untuk demo)
    let fasilitas = [
      {nama: 'RSUD Banyumas', lat: -7.5145, lon: 109.2947},
      {nama: 'Puskesmas Purwokerto Timur', lat: -7.425, lon: 109.242},
      {nama: 'Klinik Sehat', lat: -7.445, lon: 109.262}
    ];

    let markerLayer = L.layerGroup().addTo(map);
    let jarakTitik = [];

    function renderMarkers() {
      markerLayer.clearLayers();
      fasilitas.forEach((f, i) => {
        const marker = L.marker([f.lat, f.lon]).addTo(markerLayer);
        marker.bindPopup(`<div class='custom-popup'><b>${f.nama}</b><br><button onclick="hapusFasilitas(${i})">Hapus</button></div>`);
        marker.on('click', () => handleClickMarker(f));
      });
    }

    function tambahFasilitas() {
      const nama = document.getElementById('nama').value;
      const lat = parseFloat(document.getElementById('lat').value);
      const lon = parseFloat(document.getElementById('lon').value);
      if (nama && !isNaN(lat) && !isNaN(lon)) {
        fasilitas.push({nama, lat, lon});
        renderMarkers();
        document.getElementById('nama').value = '';
        document.getElementById('lat').value = '';
        document.getElementById('lon').value = '';
      }
    }

    function hapusFasilitas(index) {
      fasilitas.splice(index, 1);
      renderMarkers();
    }

    function handleClickMarker(data) {
      jarakTitik.push(data);
      if (jarakTitik.length === 2) {
        const jarak = hitungJarak(
          jarakTitik[0].lat, jarakTitik[0].lon,
          jarakTitik[1].lat, jarakTitik[1].lon
        );
        document.getElementById('output-jarak').innerText = 
          `Jarak antara ${jarakTitik[0].nama} dan ${jarakTitik[1].nama}: ${jarak.toFixed(2)} km`;
        jarakTitik = [];
      }
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
      const R = 6371;
      const dLat = (lat2-lat1) * Math.PI/180;
      const dLon = (lon2-lon1) * Math.PI/180;
      const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      return R * c;
    }

    renderMarkers();
  </script>
</body>
</html>
