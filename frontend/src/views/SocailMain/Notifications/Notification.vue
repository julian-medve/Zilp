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
      <div class="iq-card" v-for="(data,index) in notificationData" :key="index">
        <div class="iq-card-body">
          <ul class="notification-list m-0 p-0">
            <li class="d-flex align-items-center" >
              <div class="user-img img-fluid"><img :src="data.user.image" alt="story-img" class="rounded-circle avatar-40"></div>
              <div class="media-support-info ml-3">
                <h6>{{data.user.name}} ({{data.user.plateNumber}}) {{data.message}}</h6>
                <p class="mb-0">{{data.notification.created_at}}</p>
              </div>
              <div class="d-flex align-items-center">
                <a href="#" class="mr-3 iq-notify iq-bg-primary rounded">
                  <i :class="data.icon"></i></a>
                <div class="iq-card-header-toolbar d-flex align-items-center">
                     <b-dropdown id="dropdownMenuButton40" right variant="none" menu-class="p-0">
                      <template v-slot:button-content>
                        <b-link href="#" class="dropdown-toggle text-primary"><i class="ml-3 ri-more-2-line"></i></b-link>
                      </template>
                      <b-dropdown-item href="#"><i class="ri-eye-fill mr-2"></i>View</b-dropdown-item>
                      <b-dropdown-item href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</b-dropdown-item>
                      <b-dropdown-item href="#"><i class="ri-pencil-fill mr-2"></i>Edit</b-dropdown-item>
                      <b-dropdown-item href="#"><i class="ri-printer-fill mr-2"></i>Print</b-dropdown-item>
                      <b-dropdown-item href="#"><i class="ri-file-download-fill mr-2"></i>Download</b-dropdown-item>
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
      notificationData: []
    }
  },

  methods: {
    getNotifications(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/notifications?token=' + localStorage.getItem("api_token"))
      .then(response => {
        // console.log(response);
        if(response.data.payload.length != 0){
          var responseData = response.data.payload;
          console.log(responseData);
          
          Array.prototype.forEach.call(response.data.payload, notification => {
            Array.prototype.forEach.call(global.users, user => {
              if(user.id == notification.sender_id)
              {
                var notificationData = new Object();
                notificationData.notification = notification;
                notificationData.user = user;

                if(notification.type.indexOf('FriendRequest'))
                  notificationData.message = "requested to be your friend.";

                self.notificationData.push(notificationData);
              }
            });
          });
        }
      }).catch(error => {
        console.log(error);
      });
    }
  }
}
</script>
