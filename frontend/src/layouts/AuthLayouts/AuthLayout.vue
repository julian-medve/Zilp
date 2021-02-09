<template>
  <div>
    <!-- <Loader /> -->
    <section :style='inlineStyle'>
      
      <b-container class="container p-0">
        <div class="row no-gutters">
          <div class="col-md-6 text-center">
            <div class="sign-in-detail text-white" style="display: flex; align-items: center; flex-wrap: wrap;">
              <img src="../../assets/images/logo-white.png" class="img-fluid" alt="logo">            
            </div> 
          </div>
          <div class="col-sm-6 bg-white pt-5">
            <div class="sign-in-from">
              <router-view></router-view>
            </div>
          </div>
        </div>
      </b-container>
    </section>
  </div>
</template>
<script>
import logo from '../../assets/images/logo-white.png'
import bgImage from '../../assets/images/login/login-2.jpg'

export default {
  name: 'AuthLayout1',
  components: {
    // Loader
  },
  mounted () {
    // socialvue.index()
  },
  computed : {
    bgImage () {
      return require('@/assets/images/main-background.png')
    },
    inlineStyle () {
      return {
        backgroundImage: `url(${this.bgImage})` 
      }
    }
  },
  data () {
    return {
      slickOptions: {
        slidesToShow: 1,
        arrows: false
      },
      logo: logo,
      bgImageURL: bgImage,
    }
  }
}
</script>
