<template>
  <b-row>
    <b-col sm="12">
        <iq-card class="iq-card">
          <div class="iq-card-body profile-page p-0">
              <div class="profile-header">
                <div class="cover-container">
                    <img src="../../../assets/images/page-img/profile-bg1.jpg" alt="profile-bg" class="rounded img-fluid">
                    <ul class="header-nav d-flex flex-wrap justify-end p-0 m-0">
                      <!-- <li><span @click="changeAvatar"><a href="#"><i class="ri-pencil-line"></i></a></span></li> -->
                      <!-- <li><a href="javascript:void(0);"><i class="ri-settings-4-line"></i></a></li> -->
                    </ul>
                </div>
                <div class="user-detail text-center mb-3">
                    <div class="profile-img">
                      <img v-bind:src="user.image" id="user_avatar" alt="profile-img" class="avatar-130 img-fluid" v-on:click="clickAvatar"/>
                      <b-form-file style="display:none;" id="uploadAvatar" @change="uploadAvatar"></b-form-file>
                    </div>
                    <div class="profile-detail">
                      <h3 class="">{{ user.name }}</h3>
                    </div>
                </div>
                <!-- <div class="profile-info p-4 d-flex align-items-center justify-content-between position-relative">
                    <div class="social-links">
                      <ul class="social-data-block d-flex align-items-center justify-content-between list-inline p-0 m-0">
                          <li  v-for="(item,index) in social" :key="index" class="text-center pr-3">
                            <a href="javasctipt:void(0)"><img :src="item" class="img-fluid rounded" alt="facebook"></a>
                          </li>
                      </ul>
                    </div>
                    <div class="social-info">
                      <ul class="social-data-block d-flex align-items-center justify-content-between list-inline p-0 m-0">
                          <li v-for="(i,index) in soicalInfo" :key="index" class="text-center pl-3">
                            <h6>{{i.name}}</h6>
                            <p class="mb-0">{{i.value}}</p>
                          </li>
                      </ul>
                    </div>
                </div> -->
              </div>
          </div>
        </iq-card>
        <!-- <iq-card class="iq-card">
          <div class="iq-card-body p-0">
            <div class="user-tabing">
              <tab-nav :pills="true" id="pills-tab" class="nav nav-pills d-flex align-items-center justify-content-center profile-feed-items p-0 m-0">
                <tab-nav-items class="col-sm-6 p-0" :active="true" id="pills-about-tab" href="#profile-about" ariaControls="pills-about" role="tab" :ariaSelected="false" title="About" />
                <tab-nav-items class="col-sm-6 p-0" :active="false" id="pills-timeline-tab" href="#profile-timeline" ariaControls="pills-timeline" role="tab" :ariaSelected="true" title="Timeline" />
                <tab-nav-items class="col-sm-6 p-0" :active="false" id="pills-friend-tab" href="#profile-friends" ariaControls="pills-contact" role="tab" :ariaSelected="false" title="Friends" />
                <tab-nav-items class="col-sm-3 p-0" :active="false" id="pills-profile-tab" href="#profile-profile" ariaControls="pills-contact" role="tab" :ariaSelected="false" title="Photos" />
              </tab-nav>
            </div>
          </div>
        </iq-card> -->
    </b-col>
    <b-col sm="12">
      <div class="tab-content">
        <tab-content-item :active="true" id="profile-about" aria-labelled-by="pills-about-tab">
          <div class="iq-card">
            <div class="iq-card-body">
               <About />
            </div>
          </div>
        </tab-content-item>
        
        <!-- <tab-content-item :active="false" id="profile-friends" aria-labelled-by="pills-friend-tab">
          <div class="iq-card">
            <div class="iq-card-body">
              <h2>Friends</h2>
                <FriendTab />
            </div>
          </div>
        </tab-content-item> -->

        <!-- <tab-content-item :active="false" id="profile-profile" aria-labelled-by="pills-profile-tab">
          <iq-card>
            <template v-slot:body>
              <h2>Photos</h2>
              <tab-nav :pills="true" id="pills" class="nav nav-pills d-flex align-items-center justify-content-left friend-list-items p-0 mb-2">
                <tab-nav-items liClass=" " :active="true" id="pills-photo-you-tab" href="#photo-you" ariaControls="pills-photo-you" role="tab" :ariaSelected="true" title="Photo of you" />
                <tab-nav-items liClass=" " :active="false" id="pills-your-photo-tab" href="#your-photo" ariaControls="pills-your-photo" role="tab" :ariaSelected="false" title="your photo" />
              </tab-nav>
              <div class="tab-content">
                <tab-content-item :active="true" id="photo-you" aria-labelled-by="pills-photo-you-tab">
                  <b-row>
                    <b-col v-for="(item,index) in profileImages" :key="index" md="6" lg="3" class="mb-3">
                      <div class="user-images position-relative overflow-hidden">
                        <a href="#">
                          <img :src="item.img" class="img-fluid rounded" alt="Responsive image">
                        </a>
                        <div class="image-hover-data">
                          <div class="product-elements-icon">
                            <ul class="d-flex align-items-center m-0 p-0 list-inline">
                              <li v-for="(item,index1) in item.otherInfo" :key="index1">
                                <a href="#" class="pr-3 text-white"> {{item.value}} <i :class="item.class"></i> </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <a href="#" class="image-edit-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit or Remove"><i class="ri-edit-2-fill"></i></a>
                      </div>
                    </b-col>
                  </b-row>
                </tab-content-item>
                <tab-content-item :active="false" id="your-photo" aria-labelled-by="pills-your-photo-tab">
                  <ProfileImage />
                </tab-content-item>
              </div>
            </template>
          </iq-card>
        </tab-content-item> -->
      </div>
    </b-col>
  </b-row>
</template>
<script>
import { socialvue } from '../../../config/pluginInit'
import ProfileImage from './ProfileImage'
import FriendTab from './ProfileFriends/FriendTab'
import About from './ProfileFriends/About'
import Deposit from './ProfileFriends/Deposit'
import AddSocialPost from '../../Apps/Social/Components/AddSocialPost'
import Post from '../../../Model/Post'
import { Posts } from '../../../FackApi/api/SocialPost'
import SocialPost from '../../Apps/Social/Components/SocialPost'
import axios from 'axios'

export default {
  name: 'Profile',
  
  mounted () {
    socialvue.index();
    this.downloadProfileInfo();
  },
  components: {
    About,
  },
  data () {
    return {
      fileAvatar: '',
      post: new Post(),
      socialPosts: Posts,
      social: [
        require('../../../assets/images/icon/08.png'),
        require('../../../assets/images/icon/13.png')
      ],
      lifeEvent: [
        {
          img: require('../../../assets/images/page-img/07.jpg'),
          heading: 'Started New Job at Apple',
          date: 'January 24, 2019'
        },
        {
          img: require('../../../assets/images/page-img/08.jpg'),
          heading: 'Freelance Photographer',
          date: 'January 24, 2019'
        }
      ],
      friends: [
        {
          img: require('../../../assets/images/user/05.jpg'),
          name: 'Anna Rexia'
        },
        {
          img: require('../../../assets/images/user/06.jpg'),
          name: 'Tara Zona'
        }
      ],
      soicalInfo: [
        {
          name: 'Post',
          value: 690
        },
        {
          name: 'Followers',
          value: 90
        },
        {
          name: 'Following',
          value: 100
        }
      ],
      phtoes: [
        require('../../../assets/images/page-img/g1.jpg'),
        require('../../../assets/images/page-img/g8.jpg')
      ],

      profileImages: [
        {
          img: require('../../../assets/images/page-img/51.jpg'),
          otherInfo: [
            {
              class: 'ri-thumb-up-line',
              value: 10
            },
            {
              class: 'ri-chat-3-line',
              value: 2

            },
            {
              class: 'ri-share-forward-line',
              value: 1
            }
          ]
        },
        {
          img: require('../../../assets/images/page-img/52.jpg'),
          otherInfo: [
            {
              class: 'ri-thumb-up-line',
              value: 10
            },
            {
              class: 'ri-chat-3-line',
              value: 2

            },
            {
              class: 'ri-share-forward-line',
              value: 1
            }
          ]
        }  
      ],

      user : global.current_user
    }
  },
  methods: {
    addPost (post) {
      // this.socialPosts.unshift(post)
    },
    clickAvatar(){
      document.getElementById('uploadAvatar').click();
    },
    uploadAvatar(event){
      var self = this;
      let formData = new FormData();
      formData.append('imageFile', event.target.files[0]);

      axios.post(this.$apiAddress + '/x-user/edit-profile/change-profile-pic?token=' + localStorage.getItem("api_token"),
      formData, 
      { 
        headers: { 'Content-Type': 'multipart/form-data' } 
      })
      .then(function (response) {
        self.downloadAvatar();
      }).catch(function (error) {
        console.log(error);
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
      });
    },

    downloadAvatar(){
      axios.get(this.$apiAddress + '/x-user/profile/pic?token=' + localStorage.getItem("api_token"),{
            responseType: 'arraybuffer',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf'
            }
      })
      .then(function (response) {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        document.getElementById("user_avatar").src = url;
        global.current_user.image = url;
      }).catch(function (error) {
        console.log(error);
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
      });
    },

    downloadProfileInfo(){
      axios.get(this.$apiAddress + '/x-user/profile/info?token=' + localStorage.getItem("api_token"))
      .then(function(response){
      }).catch(function(e){
        console.log(e);
      });
    }
  }
}
</script>
