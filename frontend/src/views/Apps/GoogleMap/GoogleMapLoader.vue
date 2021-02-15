<template>
  <div>
    <div
      class="google-map"
      ref="googleMap"
    ></div>
    <template v-if="Boolean(this.google) && Boolean(this.map)">
      <slot
        :google="google"
        :map="map"
      />
    </template>

      <b-col lg="12">
        <b-card class="iq-card iq-card-block iq-card-stretch iq-card-height">
          <b-card-body class="iq-card-body p-0">
          <div id="weather-chart" style="height: 405px;"></div>
          </b-card-body>
        </b-card>
      </b-col>
  </div>
</template>

<script>
import GoogleMapsApiLoader from "google-maps-api-loader";
import * as am4core from '@amcharts/amcharts4/core'
import * as am4maps from '@amcharts/amcharts4/maps'
import am4themesAnimated from '@amcharts/amcharts4/themes/animated'
import am4geodataWorldLow from '@amcharts/amcharts4-geodata/worldLow'

export default {
  props: {
    mapConfig: Object,
    apiKey: String
  },

  data() {
    return {
      google: null,
      map: null
    };
  },

  async mounted() {
    const googleMapApi = await GoogleMapsApiLoader({
      apiKey: this.apiKey
    });
    this.google = googleMapApi;
    this.initializeMap();

    // Chart
    var chart = am4core.create('weather-chart', am4maps.MapChart)

    // eslint-disable-next-line camelcase
    chart.geodata = am4geodataWorldLow

    chart.projection = new am4maps.projections.Mercator()

    chart.homeZoomLevel = 6
    chart.homeGeoPoint = { longitude: 10, latitude: 51 }

    var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries())
    polygonSeries.exclude = ['AQ']
    polygonSeries.useGeodata = true
    polygonSeries.nonScalingStroke = true
    polygonSeries.strokeOpacity = 0.5

    var imageSeries = chart.series.push(new am4maps.MapImageSeries())
    var imageTemplate = imageSeries.mapImages.template
    imageTemplate.propertyFields.longitude = 'longitude'
    imageTemplate.propertyFields.latitude = 'latitude'
    imageTemplate.nonScaling = true

    var image = imageTemplate.createChild(am4core.Image)
    image.propertyFields.href = 'imageURL'
    image.width = 50
    image.height = 50
    image.horizontalCenter = 'middle'
    image.verticalCenter = 'middle'

    var label = imageTemplate.createChild(am4core.Label)
    label.text = '{label}'
    label.horizontalCenter = 'middle'
    label.verticalCenter = 'top'
    label.dy = 20
  },

  methods: {
    initializeMap() {
      const mapContainer = this.$refs.googleMap;
      this.map = new this.google.maps.Map(mapContainer, this.mapConfig);
    }
  }
};
</script>

<style scoped>
.google-map {
  width: 100%;
  min-height: 100%;
}
</style>
