  <template>
  <b-row>
    <b-col sm="12">
      <div class="iq-card-header d-flex justify-content-between">
          <div class="iq-header-title">
             <h4 class="card-title mb-2">Notification</h4>
          </div>
      </div>
    </b-col>
    <b-col sm="12">
      <div class="iq-card" v-for="(data,index) in notifications" :key="index">
        <div class="iq-card-body">
          <ul class="notification-list m-0 p-0">
            <li class="d-flex align-items-center" >
              <div class="user-img img-fluid"><img :src="checkUser(data, 'image')" alt="story-img" class="rounded-circle avatar-40"></div>
              <div class="media-support-info ml-3">
                <h6>{{ checkUser(data, 'name')}} <!--span style="float:right;">{{ data.timeAgo }}</span--></h6>
                <p class="mb-0">{{ data.text }}</p>
              </div>
              <div class="d-flex align-items-center">
                <a href="#" class="mr-3 iq-notify iq-bg-primary rounded">
                  <i :class="data.icon"></i></a>
                <div class="iq-card-header-toolbar d-flex align-items-center">
                     <b-dropdown id="dropdownMenuButton40" right variant="none" menu-class="p-0">
                      <template v-slot:button-content>
                        <b-link href="#" class="dropdown-toggle text-primary"><i class="ml-3 ri-more-2-line"></i></b-link>
                      </template>
                      <b-dropdown-item @click="acceptFriendRequest(data, 'accept')" v-if="data.type.indexOf('FriendRequest') != 0"><i class="fa fa-check mr-2"></i>Accept</b-dropdown-item>
                      <b-dropdown-item @click="acceptFriendRequest(data, 'decline')" v-if="data.type.indexOf('FriendRequest') != 0"><i class="fa fa-check mr-2"></i>Decline</b-dropdown-item>
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
import Notification from '@/Model/Notification'

export default {
  name: 'Notification',
  created() {
    this.getNotifications();
  },
  mounted () {
    socialvue.index()
  },
  data () {
    return {
      seen: true,
      notifications: []
    }
  },

  methods: {
    getNotifications(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/notifications?token=' + localStorage.getItem("api_token"))
      .then(response => {
        console.log(response);
        if(response.data.payload.length != 0){
          response.data.payload.forEach(event => {
            var content = '';
            if(event.type.indexOf('FriendRequest') != -1)
              content = ' requested to be your friend.';
            
            var newNotification = new Notification({ 
              text: content, 
              userId: event.sender_id, 
              me: false, 
              timeAgo: event.timeAgo, 
              created_at: event.created_at 
            });

            self.notifications.unshift(newNotification);
          }); 
        }
      }).catch(error => {
        console.log(error);
      });
    },

    acceptFriendRequest(notification, action){
      var self = this;
      axios.post(this.$apiAddress + '/x-user/friend/update-friend-request?token=' + localStorage.getItem("api_token"), {
        notificationId : notification.id,
        action : action,
      }).then(response => {
        console.log("accept friend request response : ", response);

      }).catch(error => {
        console.log("accept friend request error : ", error);
      });
    }
  }
}
</script>
