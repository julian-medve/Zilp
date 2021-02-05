<template>
    <div class="row">
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
                    <button type="submit" class="btn btn-primary">Following</button>
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
      axios.post(this.$apiAddress + '/x-user/find-someone?token=' + localStorage.getItem("api_token"),
      {
        params : {
          plateNumber : plateNumber
        }
      })
      .then(response => {
        if(!response.data.payload)
          self.friends.push(response.data.payload);
      }).catch(error => {
        console.log(error);
      });
    },

    getFriends(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/friends/list?token=' + localStorage.getItem("api_token"))
      .then(response => {
        self.friends = response.data.payload;
        global.users.forEach((user) => {
          self.friends.forEach((friend, index) => {
            if(user.id == friend.id){
              self.friends[index] = user;
            }
          })
        });
      }).catch(error => {
        console.log(error);
      });
    }
  }
}

</script>
