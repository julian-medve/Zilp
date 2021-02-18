import Vue from 'vue'
import 'mutationobserver-shim'
import './Utils/fliter'
import App from './App.vue'
import router from './router'
import store from './store'
import Raphael from 'raphael/raphael'
import './plugins'
import './registerServiceWorker'
import i18n from './i18n'
import User from '@/Model/User'
import { required } from 'vee-validate/dist/rules'


global.Raphael = Raphael
global.users = [];
global.current_user = new User(
  { 
    id: 1,
    name: 'Ashkan Kardan', 
    role: 'Full stack web developer', 
    isPrivate: true, 
    image: '', 
    isActive: true,

    email: 'ashkankardan14@gmail.com',
    phone: '(001) 4544 565 333',
    balance: 1000
  }
)

Vue.prototype.$current_user = new User(
  { 
    id: 1,
    name: 'Ashkan Kardan', 
    role: 'Full stack web developer', 
    isPrivate: true, 
    image: '', 
    isActive: true,

    email: 'ashkankardan14@gmail.com',
    phone: '(001) 4544 565 333',
    balance: 1000
  }
)

// Define global functions
Vue.mixin({
  methods: {
    toUTCDate(isoDate){
      var date = new Date(isoDate);
      var year = date.getFullYear();
      var month = date.getMonth()+1;
      var hour = date.getHours();
      var minute = date.getMinutes();
      var second = date.getSeconds();
      var dt = date.getDate();

      if (dt < 10) {
        dt = '0' + dt;
      }
      if (month < 10) {
        month = '0' + month;
      }
      
      return (year+'-' + month + '-'+dt + ' ' + hour + ':' + minute + ':' + second);
    },

    // Check user in global users and return type value in user
    checkUser (item, type) {
      var user;
      if(global.users.length == 0)
        return '';
        
      Array.prototype.forEach.call(global.users, element => {
        if(element.id === item.userId){
          user = element;
        }
      });
  
      let final
      if (user !== undefined) {
        switch (type) {
          case 'name':
            final = user.name + ' (' +  user.plateNumber + ') '
            break
          case 'image':
            final = user.image
            break
          case 'email':
            final = user.email
            break
          case 'phone':
            final = user.phone
            break
          case 'balance':
            final = user.balance
            break
        }
        return final
      }
      // return require('../../../assets/images/user/user-05.jpg')
    },

    convertWithDollar(num){
      var dollars = num / 100;
      dollars = dollars.toLocaleString("en-US", {style:"currency", currency:"USD"}); 
      return dollars;
    },

    balanceWithDollar(){
      var num = global.current_user.balance;
      var dollars = num / 100;
      dollars = dollars.toLocaleString("en-US", {style:"currency", currency:"USD"}); 
      return dollars;
    }
  }
});

Vue.prototype.$apiAddress = 'http://localhost:8000/api/v1'
// Vue.prototype.$apiAddress = 'http://78.140.220.40:8000/api/v1'

Vue.config.productionTip = false

let vm = new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')

window.vm = vm

