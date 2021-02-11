<template>
    <div class="row">
      <div  class="col-md-12 d-flex justify-content-center">
        <div class="iq-search-bar d-flex justify-content-center col-md-6 m-4">
          <form action="#" class="searchbox">
              <input type="text" v-model="plateNumber" class="text search-input" style="background:#50b5ff; color:white;" placeholder="Type licence plate number to search friend..." @keyup="searchFriend">
              <a class="search-link" style="color:white;"><i class="ri-search-line"></i></a>
          </form>
        </div>
      </div>
      
      <div v-for="(item,index) in friends" :key="index" class="col-md-6">
        <iq-card body-class="profile-page p-0">
          <template v-slot:body>
            <div class="profile-header-image">
              <div class="cover-container">
                <img src="../../../assets/images/page-img/profile-bg5.jpg" alt="profile-bg" class="rounded img-fluid w-100">
              </div>
              <div class="profile-info p-4">
                <div class="user-detail">
                  <div class="d-flex flex-wrap justify-content-between align-items-start">
                    <div class="profile-detail d-flex">
                      <div class="profile-img pr-4">
                        <img :src="item.image" alt="profile-img" class="avatar-130 img-fluid" />
                      </div>
                      <div class="user-data-block">
                        <h4 class="">{{item.name}}</h4>
                        <h5 class="">{{item.plateNumber}}</h5>
                        <h6>{{item.email}}</h6>
                        <p>{{item.phone}}</p>
                      </div>
                    </div>
                    <button class="btn btn-warning" v-if="item.following">Following</button>
                    <button class="btn btn-primary" @click="invite(item)" v-else>Invite</button>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </iq-card>
      </div>

    </div>
</template>
<script>
import { socialvue } from '../../../config/pluginInit'
import axios from 'axios'

export default {
  name: 'FriendList',
  created (){
    this.getFriends();
  },
  mounted () {
    socialvue.index()
  },
  data () {
    return {
      friends: [],
      plateNumber:'',
    }
  },
  methods: {
    searchFriend(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/find-someone?token=' + localStorage.getItem("api_token"),
      {
        params : {
          plateNumber : self.plateNumber,
        }
      })
      .then(response => {
        if(response.data.payload.length != 0){
          self.addUserData(response.data.payload, false);
        }          
      }).catch(error => {
        console.log(error);
      });
    },

    getFriends(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/friends/list?token=' + localStorage.getItem("api_token"))
      .then(response => {
        if(response.data.payload.length != 0){
          self.addUserData(response.data.payload, true);
        }
      }).catch(error => {
        console.log(error);
      });
    },

    addUserData(responseData, following){
      var self = this;
      self.friends = [];
      responseData.forEach((friend) => {
        global.users.forEach((user) => {
          if(user.id != global.current_user.id && user.id == friend.id){
            user.following = following;
            self.friends.unshift(user);
          }
        })
      });
    },

    invite(user){
      var self = this;
      axios.post(this.$apiAddress + '/x-user/friend/add?token=' + localStorage.getItem("api_token"), {
        userId : user.id
      })
      .then(response => {
        console.log(response);
      }).catch(error => {
        console.log(error);
      });
    }
  }
}

</script>
