<template>
  <b-row>
    <b-col sm="12">
      <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
          <h4 class="card-title mb-2">All cashouts</h4>
        </div>
      </div>
    </b-col>
    <b-col>
      <div class="iq-card" v-for="(data,index) in cashouts" :key="index">
        <div class="iq-card-body">
          <ul class="notification-list m-0 p-0">
            <li class="d-flex align-items-center" >
              <div class="user-img img-fluid"><img :src="checkUser(data, 'image')" alt="story-img" class="rounded-circle avatar-40"></div>
              <div class="media-support-info ml-3">
                <h6>{{ checkUser(data, 'name')}} - <b>Balance : {{ convertWithDollar(checkUser(data, 'balance'))}}</b><span style="float:right;">{{ data.createdAt }}</span></h6>
                <p class="mb-0"> <b>{{convertWithDollar(data.amount)}}</b> - will be withdrawn.
                <span class="badge badge-warning" style="float:right;" v-if="data.status=='unchecked'">{{data.status}}</span>
                <span class="badge badge-success" style="float:right;" v-if="data.status=='accepted'">{{data.status}}</span>
                <span class="badge badge-danger" style="float:right;" v-if="data.status=='rejected'">{{data.status}}</span>
                </p>
              </div>
              <div class="d-flex align-items-center">
                <a href="#" class="mr-3 iq-notify iq-bg-primary rounded">
                  <i :class="data.icon"></i></a>
                <div class="iq-card-header-toolbar d-flex align-items-center">
                     <b-dropdown id="dropdownMenuButton40" right variant="none" menu-class="p-0">
                      <template v-slot:button-content>
                        <b-link href="#" class="dropdown-toggle text-primary"><i class="ml-3 ri-more-2-line"></i></b-link>
                      </template>
                      <b-dropdown-item @click="updateWithdrawRequest(data.id, 'accept')"><i class="fa fa-check mr-2"></i>Accept</b-dropdown-item>
                      <b-dropdown-item @click="updateWithdrawRequest(data.id, 'decline')"><i class="fa fa-check mr-2"></i>Decline</b-dropdown-item>
                    </b-dropdown>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </b-col>
  </b-row>
</template>

<script>
import { socialvue } from '../../../config/pluginInit'
import axios from 'axios'

export default {
  name: 'Cashout',
  components: {
  },
  created() {
  },
  mounted () {
    socialvue.index();
    this.getAllCashouts();
    console.log("global.users : ", global.users);
  },
  data () {
    return {
      cashouts: [],
    }
  },


  methods: {
    getAllCashouts(){
      var self = this;
      axios.get(this.$apiAddress + '/admin/get-cashouts?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.cashouts = response.data.payload;
        console.log("self.cashouts : ", self.cashouts);
      }).catch(function(error){

      });
    },

    updateWithdrawRequest(cashoutId, action){
      var self = this;
      axios.post(this.$apiAddress + '/admin/update-cashout-status?token=' + localStorage.getItem("api_token"),{
        cashoutId : cashoutId,
        action : action
      })
      .then(function (response) {
        console.log("response : ", response);
        self.cashouts.forEach((element) => {
          if(element.id == cashoutId)
            element.status = response.data.payload.status;
        });
      }).catch(function(error){
        console.log("error : ", error);
      });
    }
  }
}
</script>
