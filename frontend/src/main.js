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


global.Raphael = Raphael
global.user = new User(
  { 
    id: 1, 
    name: 'Ashkan Kardan', 
    role: 'Full stack web developer', 
    isPrivate: true, 
    image: '../../assets/images/user/user-01.jpg', 
    isActive: true,

    email: 'ashkankardan14@gmail.com',
    phone: '(001) 4544 565 333',
    balance: 1000
  }
)

Vue.config.productionTip = false

let vm = new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')

window.vm = vm
