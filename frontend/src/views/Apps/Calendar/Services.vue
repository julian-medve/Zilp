  <template>
  <b-row>
    <b-col sm="12" class="m-3">
      <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
             <h4 class="card-title mb-2">Available drivers
              </h4>
          </div>
          <div class="iq-header-title d-flex justify-content-right mr-3">
            <button class="btn btn-primary" @click="showMyServices()">My Services</button>
          </div>
      </div>
    </b-col>
    <b-col sm="12">
      <div class="iq-card" v-for="(data,index) in scheduleList" :key="index">
        <div class="iq-card-body">
          <ul class="notification-list m-0 p-0">
            <li class="d-flex align-items-center" >
              <div class="user-img img-fluid"><img :src="checkUser(data, 'image')" alt="story-img" class="rounded-circle avatar-40"></div>
              <div class="media-support-info ml-3">
                <h6>{{ checkUser(data, 'name')}} <span style="float:right;">{{ data.start }} ~ {{ data.end}}</span></h6>
                <p class="mb-0">{{ data.location }} <span style="float:right;"><button class="btn btn-primary btn-sm" @click="chatAndBook(data.userId)">Chat and Book</button></span></p>
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
import Event from '@/Model/Event'

export default {
  name: 'Services',
  created() {
    this.getAllDriverServices();
  },
  mounted () {
    socialvue.index()
  },
  data () {
    return {
      seen: true,
      scheduleList: []
    }
  },

  methods: {
    getAllDriverServices(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/driver-service/get-all-driver-services?token=' + localStorage.getItem("api_token"))
      .then(response => {
        self.scheduleList = [];
        console.log("response ", response.data.payload);
        // Replace 'title' content with user name and plate number
        Array.prototype.forEach.call(response.data.payload, item => {
          var tempTitle = '';
          Array.prototype.forEach.call(global.users, user => {
            if(user.id == item.user_id)
              tempTitle = user.name + ' ( ' + user.plateNumber + ' ) ';
          });
          
          self.scheduleList.push(new Event({
            'id': item.id, 
            'calendarId': '1', 
            'title' : tempTitle,
            'body': item.title, 
            'start' : item.start_date,
            'end' : item.end_date,
            'category' : 'task',
            'color': '#ffffff', 
            'bgColor': '#00ff00', 
            'state' : item.state, 
            'location' : item.location,
            'userId' : item.userId
          }));
        });
      }).catch(error => {
        console.log(error);
      });
    },

    chatAndBook(userId){
      var self = this;
      axios.post(this.$apiAddress + '/x-user/driver-service/book-service?token=' + localStorage.getItem("api_token"), {
        userId : userId
      }).then(response => {
        console.log("Chat response : ", response);
        self.$router.push({name: 'app.chat'});
      }).catch(error => {
        console.log("Chat error : ", error);
      });
    },

    showMyServices(){
      this.$router.push({name: 'app.calendar'});
    }
  }
}
</script>
