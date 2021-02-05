<template>
  <div>
    <h1 class="mb-0">Sign in</h1>
    <p>Enter your email / phone and password to access admin panel.</p>
    <form class="mt-4">
      <div class="form-group">
        <!-- <label for="exampleInputEmail1">Email address / Phone number</label> -->
        <input type="email" class="form-control mb-0" id="exampleInputEmail1" placeholder="Email or Phone number" v-model="loginId">
      </div>
      <div class="form-group">
        <!-- <label for="exampleInputPassword1">Password</label> -->
        <router-link :to="{ name: 'auth1.password-reset1' }" class="float-right">Forgot password?</router-link>
        <input type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password" v-model="password">
      </div>
      <div class="d-inline-block w-100">
        <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
          <input type="checkbox" class="custom-control-input" id="customCheck1">
          <label class="custom-control-label" for="customCheck1">Remember Me</label>
        </div>
        <button type="button" class="btn btn-primary float-right" @click="login()">Sign in</button>
      </div>
      <div class="sign-info">
        <span class="dark-color d-inline-block line-height-2">Don't have an account? <router-link :to="{ name: 'auth1.signup'}">Sign up</router-link></span>
        <ul class="iq-social-media">
          <li><a href="#"><i class="ri-facebook-box-line"></i></a></li>
          <li><a href="#"><i class="ri-google-line"></i></a></li>
        </ul>
      </div>
    </form>

  </div>
</template>

<script>
import axios from 'axios'
import User from '../../../Model/User'

export default {
  name: 'SignIn',
  data: () => (
    {
      loginId:'', 
      password:'',
      message:'',
      showMessage:false
    }
  ),
  created() {
  },

  methods: {
    login() {
      let self = this;
      axios.post(  this.$apiAddress + '/user/authenticate', {
        loginId: self.loginId,
        password: self.password,
      }).then(function (response) {
        self.loginId = '';
        self.password = '';
        
        localStorage.setItem("api_token", response.data.token);
        localStorage.setItem("user_id", response.data.userId);
        localStorage.setItem('roles', response.data.roles);
        
        global.current_user = new User({
          id        : response.data.userId,
          firstName : response.data.firstname,
          lastName  : response.data.lastname
        });

        self.downloadAvatar();
        self.getUsers();
      })
      .catch(function (error) {
        self.message = 'Incorrect E-mail or password';
        self.showMessage = true;
        console.log(error);
      });
    },

    downloadAvatar(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/profile/pic?token=' + localStorage.getItem("api_token"),{
            responseType: 'arraybuffer',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf'
            }
      })
      .then(function (response) {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        global.current_user.image = url;
        self.$router.push({name: 'social.list'});
      }).catch(function (error) {
        console.log(error);
      });
    },

    getUsers(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/chat/getUsers?token=' + localStorage.getItem("api_token"))
      .then(response => {
        global.users = response.data.payload;
        for(var index in global.users){
          self.downloadUserAvatar(global.users[index], index);
        }
      }).catch(error => {
        console.log(error);
      });
    },

    downloadUserAvatar(user, index){
      var self = this;
      axios.get(this.$apiAddress + '/user/' + user.id + '/profile-pic?token=' + localStorage.getItem("api_token"),{
            responseType: 'arraybuffer',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf'
            }
      })
      .then(function (response) {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        global.users[index].image = url;
      }).catch(function (error) {
        console.log(error);
      });
    },
  },
}
</script>
