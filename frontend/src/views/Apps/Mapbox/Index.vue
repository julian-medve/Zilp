<template>
  <div id="mapContainer" class="basemap"></div>
</template>

<script>

import mapboxgl from "mapbox-gl";

export default {
  name: "BaseMap",
  data() {
    return {
      accessToken: 'pk.eyJ1IjoiZGFueWEtc2hhbHlnaW4iLCJhIjoiY2tsYXVyeTNwMWRnejJ3bnB1endjOHY5NSJ9.L1B9vcqyeJxrfUBk9r62XQ',
      currentLocation : {lat : 33.6846, lng : 117.8265},
    };
  },
  mounted() {
    this.getCurrentLocation();
  },

  methods : {
    getCurrentLocation(){
      var self = this;
      var options = {'enableHighAccuracy':true,'timeout':600,'maximumAge':0};
      this.$getLocation(options)
      .then(coordinates => {
        console.log(coordinates);
        self.currentLocation  = coordinates;
        self.setupMap();
      });
    },

    setupMap(){
      mapboxgl.accessToken = this.accessToken;

      var map = new mapboxgl.Map({
        container: "mapContainer",
        style: "mapbox://styles/mapbox/streets-v11",
        center: [this.currentLocation.lat, this.currentLocation.lng],
        zoom: 12,
        // maxBounds: [
        //   [103.6, 1.1704753],
        //   [104.1, 1.4754753],
        // ],
      });

      const nav = new mapboxgl.NavigationControl();
      map.addControl(nav, "top-right");

      const geolocate = new mapboxgl.GeolocateControl({
        positionOptions: {
          enableHighAccuracy: true
        },
        trackUserLocation: true
      });

      map.addControl(geolocate, "top-right")

      const marker = new mapboxgl.Marker()
        .setLngLat([this.currentLocation.lat, this.currentLocation.lng])
        .addTo(map);
    }
  }
};
</script>

<style lang="scss" scoped>
.basemap {
  width: 100%;
  height: 600px;
}
</style>