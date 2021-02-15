<template>
    <b-row>
      <b-col>
        <iq-card body-class="chat-page p-0">
          <template v-slot:body>
            <div class="chat-data-block">
              <b-row>
                <b-col lg="3" class="chat-data-left scroller">
                  <div class="chat-search pt-3 pl-3">
                    <!-- <ToggleButton toggleShow="#user-detail-popup">
                      <template v-slot:media>
                        <img :src="user.image" alt="chat-user" class="avatar-60 ">
                      </template>
                      <template v-slot:body>
                        <h5 class="mb-0">{{ user.name }}</h5>
                        <p class="m-0">{{ user.role }}</p>
                      </template>
                    </ToggleButton>
                    <ToggleContent id="user-detail-popup" body-class="text-left">
                      <template v-slot:media>
                        <img :src="user.image" alt="avatar">
                      </template>
                      <template v-slot:title>
                        <div class="user-name mt-4"><h4>{{ user.name }}</h4></div>
                          <div class="user-desc"><p>{{ user.role }}</p></div>
                      </template>
                      <template v-slot:body>
                        <h5 class="mt-4 mb-4">About</h5>
                          <p>It is long established fact that a reader will be distracted bt the reddable.</p>
                          <h5 class="mt-3 mb-3">Status</h5>
                          <ul class="user-status p-0">
                            <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-success pr-1"></i><span>Online</span></li>
                            <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-warning pr-1"></i><span>Away</span></li>
                            <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-danger pr-1"></i><span>Do Not Disturb</span></li>
                            <li class="mb-1"><i class="ri-checkbox-blank-circle-fill text-light pr-1"></i><span>Offline</span></li>
                          </ul>
                      </template>
                    </ToggleContent> -->
                    <div class="chat-searchbar mt-4">
                      <div class="form-group chat-search-data m-0">
                        <input type="text" class="form-control round" id="chat-search" placeholder="Search" v-model="search">
                        <i class="ri-search-line" />
                      </div>
                    </div>
                  </div>
                  <div class="chat-sidebar-channel scroller mt-4 pl-3">
                    <h5>Private Channel</h5>
                    <tab-nav :pills="true" :vertical="true" class="iq-chat-ui" id="chat-list-data">
                      <template v-for="(item,index) in privateList">
                        <tab-nav-items :key="index"
                                       :id="`v-pills-${item.id}`"
                                       href="#v-pills-home"
                                       :ariaControls="`v-pills-${item.id}`"
                                       role="tab">
                          <template v-slot:title>
                            <div  @click="chatUser(item.id)">
                              <ChatItem :item="item" />
                            </div>
                          </template>
                        </tab-nav-items>
                      </template>
                    </tab-nav>
                  </div>
                </b-col>

                <div class="col-lg-9 chat-data p-0 chat-data-right" :style="`background: url(${require('../../../assets/images/page-img/100.jpg')}) no-repeat scroll center center;background-size: cover;`">
                  <tab-content id="v-pills-tabContent">
                    <tab-content-item :active="true" id="v-pills-default" aria-labelled-by="v-pills-default">
                      <template>
                        <ToggleButton :mini-toggle="true" :close-button="false" toggleShow="#chat-user-detail-popup" mediaClass="chat-user-profile">
                          <template v-slot:media>
                          </template>
                          <template v-slot:body>
                          </template>
                        </ToggleButton>
                        <div class="chat-start">
                          <div class="chat-start">
                            <span class="iq-start-icon text-primary"><i class="ri-message-3-line"></i></span>
                            <button id="chat-start" class="btn bg-white mt-3">Start
                              Conversation!</button>
                          </div>
                        </div>
                      </template>
                    </tab-content-item>
                    <tab-content-item id="v-pills-home" aria-labelled-by="v-pills-default">
                      <template>
                        <div class="chat-head">
                          <header class="d-flex justify-content-between align-items-center bg-white pt-3 pr-3 pb-3">
                            <ToggleButton :mini-toggle="true" :close-button="false" toggleShow="#chat-user-detail-popup" mediaClass="chat-user-profile">
                              <template v-slot:media>
                                  <img :src="checkUser(chatClientId, 'image')" alt="avatar" class="avatar-50 ">
                                  <span class="avatar-status"><i class="ri-checkbox-blank-circle-fill text-success" /></span>
                              </template>
                              <template v-slot:body>
                                <h5 class="mb-0">{{ checkUser(chatClientId, 'name') }}</h5>
                              </template>
                            </ToggleButton>
                            <ToggleContent id="chat-user-detail-popup" bodyClass="chatuser-detail" center>
                              <template v-slot:media>
                                <img :src="checkUser(chatClientId,'image')" alt="avatar">
                              </template>
                              <template v-slot:title>
                                <div class="user-name mt-4"><h4>{{ checkUser(chatClientId, 'name') }}</h4></div>
                                <!-- <div class="user-desc"><p>Cape Town, RSA</p></div> -->
                              </template>
                              <template v-slot:body>
                                <div class="row">
                                  <div class="col-4 col-md-4 title">Full Name:</div>
                                  <div class="col-8 col-md-8 text-right">{{ checkUser(chatClientId, 'name') }}</div>
                                </div><hr>
                                <div class="row">
                                  <div class="col-4 col-md-4 title">Email :</div>
                                  <div class="col-8 col-md-8 text-right">{{ checkUser(chatClientId, 'email') }}</div>
                                </div><hr>
                                <div class="row">
                                  <div class="col-4 col-md-4 title">Phone :</div>
                                  <div class="col-8 col-md-8 text-right">{{ checkUser(chatClientId, 'phone') }}</div>
                                </div><hr>
                              </template>
                            </ToggleContent>
                            <!-- <div class="chat-header-icons d-flex">
                              <a class="iq-bg-primary iq-waves-effect mr-1 chat-icon-phone"><i class="ri-phone-line mr-0" /></a>
                              <a class="iq-bg-primary iq-waves-effect mr-1 chat-icon-video"><i class="ri-vidicon-line  mr-0" /></a>
                              <a class="iq-bg-primary iq-waves-effect mr-1 chat-icon-delete"><i class="ri-delete-bin-line  mr-0" /></a>
                              <b-dropdown id="dropdownMenuButton2" right variant="none iq-bg-primary iq-waves-effect remove-toggle">
                                <template v-slot:button-content>
                                  <i class="ri-more-2-line mr-0" />
                                </template>
                                <b-dropdown-item href="#"><i class="fa fa-thumb-tack mr-0" aria-hidden="true"></i> Pin to top</b-dropdown-item>
                                <b-dropdown-item href="#"><i class="fa fa-trash-o mr-0" aria-hidden="true"></i> Delete chat</b-dropdown-item>
                                <b-dropdown-item href="#"><i class="fa fa-ban mr-0" aria-hidden="true"></i> Block</b-dropdown-item>
                              </b-dropdown>
                            </div> -->
                          </header>
                        </div>
                        <div class="chat-content scroller">
                          <template v-for="(item,index) in messages">
                            <div class="chat" :key="index" v-if="item.me">
                              <div class="chat-user">
                                <a class="avatar m-0">
                                  <img :src="checkUser(item.userId, 'image')" alt="avatar" class="avatar-35 " />
                                </a>
                                <span class="chat-time mt-1">{{ item.time }}</span>
                              </div>
                              <div class="chat-detail">
                                <div class="chat-message">
                                  <p>{{ item.text }}</p>
                                </div>
                              </div>
                            </div>
                            <div class="chat chat-left" :key="index" v-else>
                              <div class="chat-user">
                                <a class="avatar m-0">
                                  <img :src="checkUser(item.userId,'image')" alt="avatar" class="avatar-35 " />
                                </a>
                                <span class="chat-time mt-1">{{ item.time }}</span>
                              </div>
                              <div class="chat-detail">
                                <div class="chat-message">
                                  <p>{{ item.text }}</p>
                                </div>
                              </div>
                            </div>
                          </template>
                        </div>
                        <div class="chat-footer p-3 bg-white">
                          <form class="d-flex align-items-center"  action="javascript:void(0);">
                            <!-- <div class="chat-attagement d-flex">
                              <a href="javascript:void(0)"><i class="fa fa-smile-o pr-3" aria-hidden="true"></i></a>
                              <a href="javascript:void(0)"><i class="fa fa-paperclip pr-3" aria-hidden="true"></i></a>
                            </div> -->
                            <input type="text" class="form-control mr-3" placeholder="Type your message" v-model="newMessage" @keyup.enter="sendMessage()">
                            <button type="button" @click="sendMessage" class="btn btn-primary d-flex align-items-center p-2"><i class="far fa-paper-plane"></i><span class="d-none d-lg-block ml-1">Send</span></button>
                          </form>
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
import ChatItem from '../../../components/Chat/ChatItem'
import ToggleButton from '../../../components/Chat/ToggleButton'
import ToggleContent from '../../../components/Chat/ToggleContent'
import { Users, MessagesUser1 } from '../../../FackApi/api/chat'
import axios from 'axios'
import Message from '../../../Model/Message'

export default {
  name: 'Index',
  components: { ChatItem, ToggleButton, ToggleContent },
  
  mounted () {
    socialvue.index();
    this.listenForChanges();
  },
  computed: {
    privateList () {
      var privateUsers = [];
      var self = this;
      Array.prototype.forEach.call(global.users, element => {
        global.users.forEach(function(user){
          if(element.id !== self.user.id && element.id == user.id){
            privateUsers.push(user);
          }
        })
      });
      return privateUsers;
    }
  },
  data () {
    return {
      search: '',
      user: global.current_user,
      usersList: [],
      newMessage: '',
      messages : [],
      chatClientId : 2,
    }
  },
  methods: {
    checkUser (item, type) {
      var user;
      Array.prototype.forEach.call(global.users, element => {
        if(element.id === item){
          user = element;
        }
      });
  
      let final
      if (user !== undefined) {
        switch (type) {
          case 'name':
            final = user.name
            break
          case 'image':
            final = user.image
            break
          case 'role':
            final = user.role
            break
          case 'email':
            final = user.email
            break
          case 'phone':
            final = user.phone
            break
        }
        return final
      }
      return require('../../../assets/images/user/user-05.jpg')
    },

    chatUser(userId){
      this.chatClientId = userId;
      var self = this;
      axios.get(this.$apiAddress + '/x-user/chat/getChat?token=' + localStorage.getItem("api_token"),
         {
            params: {
              userId: userId
            }
          }
      ).then(response => {
          self.messages = response.data.payload;
      }).catch(error => {
        console.log(error);
      });
    },

    sendMessage(){
      var self = this;
      axios.post(this.$apiAddress + '/x-user/chat/send-message?token=' + localStorage.getItem("api_token"),
      {
        userId : self.chatClientId,
        message : self.newMessage,
        messageType : 'TEXT'
      }).then(response => {
        response.data.payload.me = true;
        self.messages.push(response.data.payload);
      });
      this.newMessage = '';
    },

    listenForChanges() {
      var self = this;
      window.Echo.channel('App.User.' + global.current_user.id)
        .listen('.NewMessage', function(messageEvent){
          var msg = messageEvent.message;
          var message_content = msg.message_content;
          var newMessage = new Message({ text: message_content, userId: msg.from_user_id, me: false, timeAgo: 'just now', created_at : msg.created_at });
          console.log("messageEvent ", newMessage);
          self.messages.push(newMessage);
        });
    },
  }
}
</script>
<style>
  .remove-toggle::after{
    content: unset;
  }
</style>
