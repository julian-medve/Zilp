<template>
    <b-row>
      <b-col lg="3">
        <template>
          <iq-card>
            <template v-slot:body>
              <div class="iq-todo-page">
                <b-form class="position-relative">
                  <b-form-group class="mb-0" label-for="search">
                    <b-form-input class="todo-search" placeholder="Search" v-model="activity_search" />
                    <b-link href="#" class="search-link"><i class="ri-search-line" /></b-link>
                  </b-form-group>
                </b-form>
                <div class="add-new-project mt-3 mb-3">
                  <b-link href="#" class="d-block new-project" v-b-modal.add_activity size="lg"><i class="ri-add-line mr-2"/>Add Activity</b-link>
                  <b-modal id="add_activity" centered title="Add Activity">
                    <p class="my-2">
                      <b-form-input name="project_name" v-model="new_activity_name" placeholder="Avtivity Name" />
                    </p>
                    <template v-slot:modal-footer>
                      <b-button variant="none" class="iq-bg-primary"  @click="$bvModal.hide('add_activity')">Cancel</b-button>
                      <b-button variant="primary" @click="addActivity">Save</b-button>
                    </template>
                  </b-modal>
                </div>
                <ul class="todo-task-list p-0 m-0">
                  <li v-for="(item,index) in filteredActivityList" :key="index" @click="selectActivity(item)" :class="`${item.id === selectedActivity.id ? 'active' : ''}`">
                    <b-link href="#"><i class="ri-stack-fill mr-2"></i>{{ item.title }}</b-link>
                    <!-- <ul :id="'todo-task'+index" class="sub-task mt-2 p-0" :class="`${item.id === selectedProject.id ? 'show' : ''}`">
                      <li v-for="(category, index1) in categoryList" :key="index1" @click="selectCategory(category)" :class="`${category.id === selectedCategory.id ? 'active' : ''}`">
                        <b-link :href="category.href"><i class="ri-checkbox-blank-circle-fill" :class="'text-'+category.color" /> {{ category.name }}</b-link>
                      </li>
                    </ul>  -->
                  </li>
                </ul>
              </div>
            </template>
          </iq-card>
        </template>
      </b-col>
      <b-col lg="9">
        <template>
          <b-row>
            <b-col sm="12">
              <iq-card>
                <template v-slot:body>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="todo-date d-flex mr-3">
                      <i :class="`ri-calendar-2-line mr-2`"></i>
                      <span>{{ selectedActivity.title }}</span>
                    </div>
                    <div class="todo-notification d-flex align-items-center">
                      <b-button variant=" iq-bg-primary iq-waves-effect" v-b-modal.add_task size="lg">Add Task</b-button>
                        <template>
                          <b-modal id="add_task" centered title="Add Task">
                            <b-form>
                              <b-form-group
                                class="form-group"
                                label="Task Title"
                                label-for="task_tile"
                              >
                                <b-form-input name="task_title" v-model="selectedTask.title" id="task_tile" placeholder=""></b-form-input>
                              </b-form-group>
                              <b-form-group
                                class="form-group"
                                label="Assigned For"
                                label-for="user_id"
                              >
                                <v-select v-model="selectedTask.user_id" name="user_id" id="user_id" :options="userList" />
                              </b-form-group>
                              <!-- <b-form-group
                                class="form-group"
                                label="Category"
                                label-for="category_id"
                              >
                                <v-select v-model="selectedTask.category_id" name="user_id" id="category_id" :options="categoryList" />
                              </b-form-group> -->
                              <b-form-group
                                class="form-group"
                                label="Priority"
                                label-for="priority"
                              >
                                <b-form-radio inline v-model="selectedTask.status" name="priority" :value="item" v-for="(item,index) in statusList" :key="index">{{ item }}</b-form-radio>
                              </b-form-group>
                            </b-form>
                            <template v-slot:modal-footer>
                              <b-button variant="none" class="iq-bg-primary"  @click="$bvModal.hide('add_task')">Cancel</b-button>
                              <b-button variant="primary" @click="addTask">Save</b-button>
                            </template>
                          </b-modal>
                        </template>
                    </div>
                  </div>
                </template>
              </iq-card>
            </b-col>
            <b-col md="12">
              <iq-card body-class="p-0" v-if="taskList.length > 0">
                <template v-slot:body>
                  <ul class="todo-task-lists m-0 p-0">
                      <template v-for="(item,index) in taskList">
                        <li class="d-flex align-items-center p-3" v-if="item.activity_id === selectedActivity.id" :key="index">
                          <div class="user-img img-fluid">
                            <img :src="checkUser(item.assigned_for,'image')" alt="story-img" class="rounded-circle avatar-40">
                          </div>
                          <div class="media-support-info ml-3">
                            <h6 class="d-inline-block">
                              <del v-if="item.task_status">
                                {{ item.title }} for {{ selectedActivity.title }}
                              </del>
                              <template v-else>
                                {{ item.title }} for {{ selectedActivity.title }}
                              </template>
                            </h6>
                            <span class="badge badge-danger ml-3 text-white" v-if="item.status === 'Expiring'">{{ item.status }}</span>
                            <span class="badge badge-primary ml-3 text-white" v-if="item.status === 'Complete'">{{ item.status }}</span>
                            <span class="badge badge-info ml-3 text-white" v-if="item.status === 'Urgent'">{{ item.status }}</span>
                            <p class="mb-0">by {{ checkUser(item.assigned_for,'name') }} ({{ checkUser(item.assigned_for, 'plateNumber')}}) </p>
                          </div>
                          <div class="iq-card-header-toolbar d-flex align-items-center">
                            <b-button class="btn mr-3 btn-sm" v-b-modal.modal-payment variant="danger" @click="payActivity(item)" size="xs" v-if="item.status === 'Complete'">Pay</b-button>
                            <!-- <b-modal v-if="item.status === 'Complete'" id="modal-payment" centered title="Payment Confirmation" ok-title="Pay" cancel-title="Cancel">
                              <div class="form-group">
                              <b-form>
                                Are you sure to pay $50 to the driver?
                              </b-form>
                            </div>
                            </b-modal> -->

                            <!-- <div class="custom-control custom-checkbox">  
                              <input type="checkbox" name="todo-check" class="custom-control-input" @change="updateStatue(item)" :id="'check' + index" :checked="item.task_status">
                              <label class="custom-control-label" :for="'check' + index"></label>
                            </div> -->
                          </div>
                        </li>
                      </template>
                  </ul>
                </template>
              </iq-card>
              <template v-else>
                <div class="text-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="138"
                    height="138"
                    class="hits-empty-state-image"
                  >
                    <defs>
                      <linearGradient id="c" x1="50%" x2="50%" y1="100%" y2="0%">
                        <stop offset="0%" stop-color="#F5F5FA" />
                        <stop offset="100%" stop-color="#FFF" />
                      </linearGradient>
                      <path
                        id="b"
                        d="M68.71 114.25a45.54 45.54 0 1 1 0-91.08 45.54 45.54 0 0 1 0 91.08z"
                      />
                      <filter
                        id="a"
                        width="140.6%"
                        height="140.6%"
                        x="-20.3%"
                        y="-15.9%"
                        filterUnits="objectBoundingBox"
                      >
                        <feOffset dy="4" in="SourceAlpha" result="shadowOffsetOuter1" />
                        <feGaussianBlur
                          in="shadowOffsetOuter1"
                          result="shadowBlurOuter1"
                          stdDeviation="5.5"
                        />
                        <feColorMatrix
                          in="shadowBlurOuter1"
                          result="shadowMatrixOuter1"
                          values="0 0 0 0 0.145098039 0 0 0 0 0.17254902 0 0 0 0 0.380392157 0 0 0 0.15 0"
                        />
                        <feOffset dy="2" in="SourceAlpha" result="shadowOffsetOuter2" />
                        <feGaussianBlur
                          in="shadowOffsetOuter2"
                          result="shadowBlurOuter2"
                          stdDeviation="1.5"
                        />
                        <feColorMatrix
                          in="shadowBlurOuter2"
                          result="shadowMatrixOuter2"
                          values="0 0 0 0 0.364705882 0 0 0 0 0.392156863 0 0 0 0 0.580392157 0 0 0 0.2 0"
                        />
                        <feMerge>
                          <feMergeNode in="shadowMatrixOuter1" />
                          <feMergeNode in="shadowMatrixOuter2" />
                        </feMerge>
                      </filter>
                    </defs>
                    <g fill="none" fill-rule="evenodd">
                      <circle
                        cx="68.85"
                        cy="68.85"
                        r="68.85"
                        fill="#5468FF"
                        opacity=".07"
                      />
                      <circle
                        cx="68.85"
                        cy="68.85"
                        r="52.95"
                        fill="#5468FF"
                        opacity=".08"
                      />
                      <use fill="#000" filter="url(#a)" xlink:href="#b" />
                      <use fill="url(#c)" xlink:href="#b" />
                      <path
                        d="M76.01 75.44c5-5 5.03-13.06.07-18.01a12.73 12.73 0 0 0-18 .07c-5 4.99-5.03 13.05-.07 18a12.73 12.73 0 0 0 18-.06zm2.5 2.5a16.28 16.28 0 0 1-23.02.09A16.29 16.29 0 0 1 55.57 55a16.28 16.28 0 0 1 23.03-.1 16.28 16.28 0 0 1-.08 23.04zm1.08-1.08l-2.15 2.16 8.6 8.6 2.16-2.15-8.6-8.6z"
                        fill="#5369FF"
                      />
                    </g>
                  </svg>
                  <p class="mt-2">Sorry, we can't find any matches to your query!</p>
                  <p>Please try another query.</p>
                </div>
              </template>
            </b-col>
            <!-- <div class="col-md-4">
                <div class="iq-card">
                  <div class="iq-card-body">
                      <div class="iq-todo-right">
                        <form class="position-relative">
                            <div class="form-group mb-0">
                              <input type="text" class="form-control todo-search" id="exampleInputEmail001" placeholder="Search">
                              <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                            </div>
                        </form>
                        <div class="iq-todo-friendlist mt-3">
                            <ul class="suggestions-lists m-0 p-0">
                              <li class="d-flex mb-4 align-items-center" v-for="(item,index) in friendList" :key=index>
                                  <div class="user-img img-fluid"><img :src="item.img" alt="story-img" class="rounded-circle avatar-40"></div>
                                  <div class="media-support-info ml-3">
                                    <h6>{{item.name}}</h6>
                                    <p class="mb-0">{{item.work}}</p>
                                  </div>
                                  <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <div class="dropdown">
                                        <span class="dropdown-toggle text-primary" id="dropdownMenuButton41" data-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                        </span>
                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                          <a class="dropdown-item" href="#"><i class="ri-user-unfollow-line mr-2"></i>Unfollow</a>
                                          <a class="dropdown-item" href="#"><i class="ri-close-circle-line mr-2"></i>Unfriend</a>
                                          <a class="dropdown-item" href="#"><i class="ri-lock-line mr-2"></i>block</a>
                                        </div>
                                    </div>
                                  </div>
                              </li>
                            </ul>
                            <a href="javascript:void();" class="btn btn-primary d-block"><i class="ri-add-line"></i> Load More</a>
                        </div>
                      </div>
                  </div>
                </div>
            </div> -->
          </b-row>
        </template>
      </b-col>
    </b-row>
</template>
<script>

import { socialvue } from '../../../config/pluginInit'
import axios from 'axios'

export default {
  name: 'TodoListing',

  data() {
    return {
      activityList : [],
      taskList : [],
      activity_search : '',
      new_activity_name : '',
      selectedActivity : '',
      selectedTask: '',
      userList : [],
      statusList: ['Expiring', 'Complete', 'Urgent'],
    };
  },
  mounted () {
    socialvue.index()
    this.getActivityList();
    this.setUserList();
    this.selectedTask = this.defaultTask();
  },
  
  computed: {
    filteredActivityList () {
      if(!this.activityList)
        return [];

      return this.activityList.filter(item => {
        return item.title.toLowerCase().includes(this.activity_search.toLowerCase())
      })
    },
  },

  methods : {
    getActivityList(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/activity/all?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.activityList = response.data.payload;
        if(self.activityList.length != 0)
          self.selectedActivity = self.activityList[0];
          self.getTaskList();
      }).catch(function (error) {
          console.log(error);
          // self.$router.push({ path: 'login' });
      });
    },

    getTaskList(){
      var self = this;
      if(!this.selectedActivity)
        return;
      axios.get(this.$apiAddress + '/x-user/task/all?token=' + localStorage.getItem("api_token"), {
        params : {
          activity_id : self.selectedActivity.id
        }
      }).then(function (response) {
        self.taskList = response.data.payload;
      }).catch(function (error) {
          console.log(error);
          // self.$router.push({ path: 'login' });
      });
    },

    selectActivity (activity) {
      this.selectedActivity = activity;
      this.activity_search = ''
      self.getTaskList();
    },

    setUserList(){
      var self = this;
      this.userList = [];
      Array.prototype.forEach.call(global.users, (user) => {
        if(user.id != global.current_user.id)
          self.userList.push(user.name);
      });
    },

    defaultTask () {
      return {
        id : -1,
        activity_id: this.selectedActivity.id,
        title: '',
        user_id: 0,
        assigned_for: 0,
        status: 'Urgent',
      }
    },

    addTask(){
      var self = this;
      var assigned_for = 0;
      Array.prototype.forEach.call(global.users, (user) => {
        if(user.name == self.selectedTask.user_id)
          assigned_for = user.id;
      });
      
      axios.post(this.$apiAddress + '/x-user/task/add?token=' + localStorage.getItem("api_token"),{
        title         : self.selectedTask.title,
        activity_id   : self.selectedActivity.id,
        creator_id    : global.current_user.id, 
        status        : self.selectedTask.status,
        assigned_for  : assigned_for
      }).then(function (response) {
        self.taskList.push(response.data.payload);
      }).catch(function (error) {
          console.log(error);
          // self.$router.push({ path: 'login' });
      });
      this.$bvModal.hide('add_task')
    },

    checkUser (item, type) {
      let user = global.users.find(user => user.id === item)
      let final
      if (user !== undefined) {
        switch (type) {
          case 'name':
            final = user.name
            break
          case 'image':
            final = user.image
            break
          case 'plateNumber':
            final = user.plateNumber
            break
        }
        return final
      }
      return false
    },

    addActivity(){
      var self = this;
      axios.post(this.$apiAddress + '/x-user/activity/add?token=' + localStorage.getItem("api_token"), {
        title : self.new_activity_name
      }).then(function (response) {
        self.activityList.push(response.data.payload);
      })
      .catch(function (error) {
        console.log(error);
      });
      self.$bvModal.hide('add_activity');
    },

    payActivity(activity){
      this.$router.push({ path: '/profile' });
    }
  }
}
</script>
<style>
</style>
