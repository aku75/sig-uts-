var map = L.map('map').setView([-7.5, 109.3], 10);

// Basemap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap'
}).addTo(map);

// Load polygon GeoJSON batas kecamatan
$.getJSON("data/kecamatan_banyumas.geojson", function(data) {
  L.geoJSON(data, {
    style: {
      color: "#ff7800",
      weight: 2,
      opacity: 0.6
    }
  }).addTo(map);
});

// Load marker fasilitas kesehatan
$.getJSON("data.php", function(data){
  data.forEach(faskes => {
    L.marker([faskes.lat, faskes.lng])
      .addTo(map)
      .bindPopup(`<b>${faskes.nama}</b><br>${faskes.jenis}`);
  });
});

// Fungsi hitung jarak antar dua titik (contoh dua titik statis)
var pointA = L.latLng(-7.5, 109.3);
var pointB = L.latLng(-7.6, 109.4);
var distance = pointA.distanceTo(pointB) / 1000;
console.log("Jarak: " + distance.toFixed(2) + " km");
