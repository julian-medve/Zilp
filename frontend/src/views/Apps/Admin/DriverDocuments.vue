<template>
  <b-row>
    <b-col sm="12">
      <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
          <h4 class="card-title mb-2">All Users</h4>
        </div>
      </div>
    </b-col>
    <b-col>
      <iq-card body-class="chat-page p-0">
        <template v-slot:body>
          <div class="chat-data-block">
            <b-row>
              <b-col lg="3" class="chat-data-left scroller">
                <div class="chat-sidebar-channel scroller mt-4 pl-3">
                  <tab-nav :pills="true" :vertical="true" class="iq-chat-ui" id="chat-list-data">
                    <template v-for="(item,index) in users">
                      <tab-nav-items :key="index"
                                      :id="`v-pills-${item.id}`"
                                      href="#v-pills-home"
                                      :ariaControls="`v-pills-${item.id}`"
                                      role="tab">
                        <template v-slot:title>
                          <div @click="showUserContent(index)">
                            <template>
                              <div class="d-flex align-items-center">
                                <div class="avatar mr-3">
                                  <img :src="item.image" :alt="item.image" class="avatar-50 ">
                                  <span class="avatar-status">
                                    <i class="ri-checkbox-blank-circle-fill" :class="item.isActive ? 'text-success' :'text-warning'"/>
                                  </span>
                                </div>
                                <div class="chat-sidebar-name">
                                  <h6 class="mb-0">{{ item.name }}</h6>
                                  <span>({{ item.plateNumber }})</span>
                                </div>
                              </div>
                            </template>
                          </div>
                        </template>
                      </tab-nav-items>
                    </template>
                  </tab-nav>
                </div>
              </b-col>

              <div class="col-lg-9 chat-data p-0 chat-data-right" :style="`background: url(${require('../../../assets/images/page-img/100.jpg')}) no-repeat scroll center center;background-size: cover;`">
                <tab-content id="v-pills-tabContent">
                  <tab-content-item id="v-pills-home" aria-labelled-by="v-pills-default">
                    <template>
                      <div class="chat-content scroller">
                        <div class="row d-flex justify-content-center" v-if="showDocumentStatus">
                          <div class="alert alert-warning" role="alert">
                            <div class="iq-alert-text">{{selectedUser.name}}'s documents are on {{documents.status}}.</div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-3">
                            <h6>Driver Licence</h6>
                          </div>
                          <div class="col-9">
                            <p class="mb-2"><img :src="driverImages['driverLicence']" class="img-fluid" id="image_driverLicence"></p>
                          </div>
                          <div class="col-3">
                            <h6>Vehicle Registration</h6>
                          </div>
                          <div class="col-9">
                            <p class="mb-2"><img v-bind:src="driverImages['vehicleRegistration']" class="img-fluid" id="image_vehicleRegistration"></p>
                          </div>
                          <div class="col-3">
                            <h6>Insurance Card</h6>
                          </div>
                          <div class="col-9">
                            <p class="mb-2"><img v-bind:src="driverImages['insuranceCard']" class="img-fluid" id="image_insuranceCard"></p>
                          </div>
                          <div class="col-3">
                            <h6>Vehicle Picture</h6>
                          </div>
                          <div class="col-9">
                            <p class="mb-2"><img v-bind:src="driverImages['vehiclePicture']" class="img-fluid" id="image_vehiclePicture"></p>
                          </div>
                          <div class="col-3">
                            <h6>Driver Headshot</h6>
                          </div>
                          <div class="col-9">
                            <p class="mb-2"><img v-bind:src="driverImages['driverHeadshot']" class="img-fluid" id="image_driverHeadshot"></p>
                          </div>
                        </div>

                        <div class="row d-flex justify-content-center" v-if="showVerifyButtons">
                            <button class="btn btn-primary m-3" @click="verifyDriver('accepted')">Verify driver</button>
                            <button class="btn btn-warning m-3" @click="verifyDriver('rejected')">Reject driver</button>
                        </div>
                      </div>
                    </template>
                  </tab-content-item>
                </tab-content>
              </div>
            </b-row>
          </div>
        </template>
      </iq-card>
    </b-col>
  </b-row>
</template>

<script>
import { socialvue } from '../../../config/pluginInit'
import axios from 'axios'

export default {
  name: 'Users',
  components: {
  },
  created() {
  },
  mounted () {
    socialvue.index();
    var self = this;

    global.users.forEach(element => {
      if(element.id != global.current_user.id)
          self.users.push(element);
    });
    console.log("self.users : ", self.users);
  },
  data () {
    return {
      seen: true,
      selectedUser : '',
      users : [],
      documents:[],
      driverImages : [],
      showVerifyButtons : false,
      showDocumentStatus : false,
    }
  },


  methods: {
    showUserContent(index){
      this.selectedUser = this.users[index];
      this.getLicenceFiles();
    },

    getLicenceFiles(){
      var self = this;
      axios.get(this.$apiAddress + '/admin/get-driver-documentations?token=' + localStorage.getItem("api_token"), {
        params : {
          userId : self.selectedUser.id
        }
      })
      .then(function (response) {
        self.documents = response.data.payload;
        console.log("self.documents : ", self.documents);
        if(self.documents){
          self.showDocumentStatus = true;
          self.downloadDocumentImage("driverLicence");
          self.downloadDocumentImage("vehicleRegistration");
          self.downloadDocumentImage("insuranceCard");
          self.downloadDocumentImage("vehiclePicture");
          self.downloadDocumentImage("driverHeadshot");
          if(self.documents.status.indexOf("unchecked") != -1){
            self.showVerifyButtons = true;
          }
        }else {
          self.emptyDocumentImage("driverLicence");
          self.emptyDocumentImage("vehicleRegistration");
          self.emptyDocumentImage("insuranceCard");
          self.emptyDocumentImage("vehiclePicture");
          self.emptyDocumentImage("driverHeadshot");

          self.showVerifyButtons = false;
          self.showDocumentStatus = false;
        }
      }).catch(function (error) {
        if(error.response && error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
        console.log("Error : " + error);
      });
    },

    downloadDocumentImage(filename){
      var self = this;
      axios.get(this.$apiAddress + '/driver-photos/' + self.documents[filename] + '?token=' + localStorage.getItem("api_token"),{
            responseType: 'arraybuffer',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf'
            }
      })
      .then(function (response) {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        document.getElementById("image_" + filename).src = url;
        self.driverImages[filename] = url;
      }).catch(function (error) {
        console.log(error);
      });
    },

    emptyDocumentImage(filename){
      // self.driverImages[filename] = '';
      document.getElementById("image_" + filename).src = '';
    },

    verifyDriver(action){
      let self = this;
      axios.post(  this.$apiAddress + '/admin/update-documentations?token=' + localStorage.getItem("api_token"), {
        documentId : self.documents.id,
        action : action,
        userId : self.selectedUser.id
      }).then(function (response) {
        console.log("Response in verify driver : ", response);
      })
      .catch(function (error) {
        console.log("Error in verify driver : ", error);
      });
    }
  }
}
</script>
