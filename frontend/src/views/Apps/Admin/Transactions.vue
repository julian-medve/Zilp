<template>
  <b-row>
    <b-col sm="12">
      <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
          <h4 class="card-title mb-2">All Transactions</h4>
        </div>
      </div>
    </b-col>
    <b-col>
      <div class="iq-card" v-for="(data,index) in transactions" :key="index">
        <div class="iq-card-body">
          <ul class="notification-list m-0 p-0">
            <li class="d-flex align-items-center" >
              <div class="user-img img-fluid"><img :src="checkUser(data, 'image')" alt="story-img" class="rounded-circle avatar-40"></div>
              <div class="media-support-info ml-3">
                <h6>{{ checkUser(data, 'name')}} <span style="float:right;">{{ data.createdAt }}</span></h6>
                <p class="mb-0"> {{convertWithDollar(data.amount)}} - {{ data.description }}</p>
              </div>
              <!-- <div class="d-flex align-items-center">
                <a href="#" class="mr-3 iq-notify iq-bg-primary rounded">
                  <i :class="data.icon"></i></a>
                <div class="iq-card-header-toolbar d-flex align-items-center">
                     <b-dropdown id="dropdownMenuButton40" right variant="none" menu-class="p-0">
                      <template v-slot:button-content>
                        <b-link href="#" class="dropdown-toggle text-primary"><i class="ml-3 ri-more-2-line"></i></b-link>
                      </template>
                      <b-dropdown-item @click="updateFriendRequest(data.id, 'accept')" v-if="data.type.indexOf('FriendRequest') != 0"><i class="fa fa-check mr-2"></i>Accept</b-dropdown-item>
                      <b-dropdown-item @click="updateFriendRequest(data.id, 'decline')" v-if="data.type.indexOf('FriendRequest') != 0"><i class="fa fa-check mr-2"></i>Decline</b-dropdown-item>
                    </b-dropdown>
                </div>
              </div> -->
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
  name: 'Transactions',
  components: {
  },
  created() {
  },
  mounted () {
    socialvue.index();
    this.getAllTransactions();
    console.log("global.users : ", global.users);
  },
  data () {
    return {
      transactions: [],
    }
  },


  methods: {
    getAllTransactions(){
      var self = this;
      axios.get(this.$apiAddress + '/admin/get-transactions?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.transactions = response.data.payload;
        console.log("self.transactions : ", self.transactions);
      }).catch(function(error){

      });
    },
  }
}
</script>
