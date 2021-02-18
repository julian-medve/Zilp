<template>
  <div>
    <my-map />
  </div>
</template>
<script>
import { mapSettings } from '@/FackApi/api/mapSettings'
import GoogleMapLoader from './GoogleMapLoader.vue'
import GoogleMapsApiLoader from 'google-maps-api-loader'
import MyMap from "./MyApp";

export default {
  props: {
    mapConfig: Object,
    apiKey: String,
  },

  components :{
    MyMap
  },

  data() {
    return {
      google: null,
      map: null
    }
  },

}
</script>