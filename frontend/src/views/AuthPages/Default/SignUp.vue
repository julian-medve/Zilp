<template>
  <div>
    <h1 class="mb-0">Sign Up</h1>
    <p>Enter your email address, phone number and password to access admin panel.</p>
    <form class="mt-4">
      <div class="form-group">
        <!-- <label for="exampleInputEmail2">Email address</label> -->
        <input type="email" class="form-control mb-0" id="exampleInputEmail2" placeholder="Email Address" v-model="email">
      </div>
      <div class="form-group">
        <input type="text" class="form-control mb-0" id="exampleInputPlate" placeholder="Plate Number" v-model="plateNumber">
      </div>
      <div class="form-group">
        <input type="text" class="form-control mb-0" id="exampleInputFullname" placeholder="Full Name" v-model="fullname">
      </div>
      <div class="form-group">
        <div class="row pl-2 pr-2">
          <input type="text" class="form-control mb-0 col-sm-4" id="exampleCountryCode" placeholder="Country Code" v-model="countryCode">  
          <input type="phone" class="form-control mb-0 col-sm-8" id="exampleInputPhone" placeholder="Phone Number" v-model="phone">
        </div>
      </div>
      <div class="form-group">
        <input type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password" v-model="password">
      </div>
      <div class="d-inline-block w-100">
        <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
          <input type="checkbox" class="custom-control-input" id="customCheck1" v-model="acceptPrivacy">
          <label class="custom-control-label" for="customCheck1">I accept <a href="#">Terms and Conditions</a></label>
        </div>
        <button type="button" class="btn btn-primary float-right" @click="register()">Sign Up</button>
      </div>
      <div class="sign-info">
        <span class="dark-color d-inline-block line-height-2">Already Have Account ? <router-link :to="{ name: 'auth1.signin'}">Log In</router-link></span>
        <ul class="iq-social-media">
          <!-- <li><a href="#"><i class="ri-facebook-box-line"></i></a></li> -->
          <!-- <li><a href="#"><i class="ri-google-line"></i></a></li> -->
          <!-- <li><a href="#"><i class="ri-instagram-line"></i></a></li> -->
        </ul>
      </div>
    </form>
  </div>
</template>
<script>
import axios from 'axios'

export default {
  name: 'SignUp',
  data: () => (
    {
      plateNumber:'', 
      email:'',
      fullname:'',
      password:'',
      phone:'',
      countryCode:'',

      message:'',
      showMessage:false,
      acceptPrivacy:false
    }
  ),
  methods: {
    register() {
      var self = this;
      var tempFullName = self.fullname.split(' ');
      
      var message = "";
      if(tempFullName.length < 2){
        message = "Please enter firstname and lastname.";
        alert(message);
        return;
      }

      if(!self.acceptPrivacy){
        message = "Please accept temrs and conditions."
        alert(message);
        return;
      }

      var firstName = tempFullName[0];
      var lastName = tempFullName[1];

      axios.post(  this.$apiAddress + '/user/signup', {
        firstName: firstName,
        lastName: lastName,
        email: self.email,
        phone: self.phone,
        countryCode: self.countryCode,
        plateNumber: self.plateNumber,
        password: self.password

      }).then(function (response) {
        self.name = '';
        self.email = '';
        self.password = '';

        console.log(response);
        self.$router.push({ path: '/auth/signin' });
      })
      .catch(function (error) {
        console.log(error);
      });

    }
  }
}
</script>
