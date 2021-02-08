<template>
    <div class="row">
      <div class="col-md-2">
        <tab-nav :pills="true" id="tab" class="nav nav-pills basic-info-items list-inline d-block p-0 m-0" >
          <tab-nav-items :active="true" id="pills-contact-info" href="#contact-info" ariaControls="pills-contact-info" role="tab" :ariaSelected="true" title="Contact" />
          <tab-nav-items :active="false" id="pills-verified-info" href="#verified-info" ariaControls="pills-verified-info" role="tab" :ariaSelected="true" title="Licence" />
          <tab-nav-items :active="false" id="pills-financial-info" href="#financial-info" ariaControls="pills-financial-info" role="tab" :ariaSelected="true" title="Payment" />
          <!-- <tab-nav-items :active="false" id="pills-work-info" href="#work-info" ariaControls="pills-work-info" role="tab" :ariaSelected="true" title="Work and Education" />
          <tab-nav-items :active="false" id="pills-address-info" href="#address-info" ariaControls="pills-address-info" role="tab" :ariaSelected="true" title="Places You've Lived" />
          <tab-nav-items :active="false" id="pills-about-info" href="#about-info" ariaControls="pills-about-info" role="tab" :ariaSelected="true" title="Details About You" /> -->
        </tab-nav>
      </div>
      <div class="col-md-10">
        <div class="tab-content">
          <tab-content-item :active="true" id="contact-info" aria-labelled-by="pills-contact-info">
            <h4>Contact Information</h4>
            <hr>
            <div class="row">
              
              <div class="col-3">
                <h6>Full Name</h6>
              </div>
              <div class="col-9">
                <p class="mb-2">{{ user.name }}</p>
              </div>
      
              <div class="col-3">
                <h6>Email</h6>
              </div>
              <div class="col-9">
                <p class="mb-2">{{ user.email }}</p>
              </div>

              <div class="col-3">
                <h6>Mobile</h6>
              </div>
              <div class="col-9">
                <p class="mb-2">{{ user.phone}}</p>
              </div>

              <div class="text-center col-12 m-3">
                <b-button v-b-modal.modal-7 variant="primary" class="mr-1">Edit Contact Info</b-button>
              </div>
              
              <b-modal id="modal-7" centered title="Contact Info" ok-title="Save Changes" cancel-title="Close" @ok="updateProfileInfo">
                <div class="form-group">
                <b-form>

                  <b-form-group
                    class="row"
                    label-cols-sm="3"
                    label="Fullname"
                    label-for="fullname"
                  >
                    <b-form-input id="fullname" placeholder="Enter Full name" v-model="user.name"></b-form-input>
                  </b-form-group>

                  <b-form-group
                    class="row"
                    label-cols-sm="3"
                    label="Email"
                    label-for="email"
                  >
                    <b-form-input id="email" placeholder="Enter Email address" v-model="user.email"></b-form-input>
                  </b-form-group>

                  <b-form-group
                    class="row"
                    label-cols-sm="3"
                    label="Phone Number"
                    label-for="phone"
                  >
                    <b-form-input id="phone" placeholder="Enter Phone number" v-model="user.phone"></b-form-input>
                  </b-form-group>

                </b-form>
              </div>
              </b-modal>
            </div>
            
          </tab-content-item>
          <tab-content-item :active="false" id="verified-info" aria-labelled-by="pills-verified-info">
              <h4>Licence Information</h4>
                <b-card-body class="iq-card-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">State</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in documents" :key="index">
                      <td>
                        {{item.createdAt}}
                      </td>
                      <td>
                        <span class="badge badge-secondary ml-3 text-white" v-if="item.status === 'unchecked'">{{ item.status }}</span>
                        <span class="badge badge-primary ml-3 text-white" v-if="item.status === 'accepted'">{{ item.status }}</span>
                        <span class="badge badge-danger ml-3 text-white" v-if="item.status === 'rejected'">{{ item.status }}</span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                </b-card-body>
                
              <div class="text-center col-12 m-3">
                <b-button v-b-modal.modal-licence variant="primary" class="mr-1">Upload documents</b-button>
              </div>
              
              <b-modal id="modal-licence" centered title="Upload Licence" ok-title="Upload" cancel-title="Close" @ok="uploadLicenceFiles">
                <ul class="suggestions-lists m-0 p-0">
                  <li class="d-flex align-items-center">
                    <div class="media-support-info ml-3">
                      Please submit necessary images to get verified.
                    </div>
                  </li>
                </ul>
                <div class="form-group">
                  <b-form>
                   <div class="row d-flex justify-content-center">
                      <div class="m-0">
                        <!-- <template v-for="(item,index) in custom">
                          <b-form-radio inline v-model="stateActive[item[Object.keys(item)[0]]]" :name="item.name" :key="index" :value="item.value" :disabled="item.disabled">{{ item.label }}</b-form-radio>
                        </template> -->
                        <b-form-group label-for="driverLicense" class="mt-3">
                          <b-form-file placeholder="Driver Licence" id="driverLicense" ref="driverLicense" @change="handleLicence"></b-form-file>
                        </b-form-group>
                        <b-form-group label-for="vehicleRegistration" class="mt-3">
                          <b-form-file placeholder="Vehicle Registration" id="vehicleRegistration" ref="vehicleRegistration" @change="handleVehicle"></b-form-file>
                        </b-form-group>
                        <b-form-group label-for="insuranceCard" class="mt-3">
                          <b-form-file placeholder="Insurance Card" id="insuranceCard" ref="insuranceCard" @change="handleInsurance"></b-form-file>
                        </b-form-group>
                        <b-form-group label-for="vehiclePicture" class="mt-3">
                          <b-form-file placeholder="Vehicle Picture" id="vehiclePicture" ref="vehiclePicture" @change="handleVehiclePicture"></b-form-file>
                        </b-form-group>
                        <b-form-group label-for="driverHeadshot" class="mt-3">
                          <b-form-file placeholder="Driver Headshot" id="driverHeadshot" ref="driverHeadshot" @change="handleHeadshot"></b-form-file>
                        </b-form-group>
                      </div>
                    </div>
                  </b-form>
                </div>
              </b-modal>
          </tab-content-item>
          <tab-content-item :active="false" id="financial-info" aria-labelled-by="pills-financial-info">
            <div class="row col-md-12 ">
              <div class="d-flex justify-content-center text-center"><h3>Balance : ${{user.balance}}</h3></div>
            </div>
            
            <h5>Amount will be withdrawen by </h5><hr>
            <paypal-form />

            <div id="smart-button-container">
              <div style="text-align: center;">
                <div id="paypal-button-container"></div>
              </div>
            </div>
            <!-- <ul class="suggestions-lists m-0 p-0">
              <li class="d-flex mb-4 align-items-center">
                <b-form-group>
                  <b-form-select plain v-model="selected3" :options="payment_options">
                    <template v-slot:first>
                      <b-form-select-option :value="null">Select payment</b-form-select-option>
                    </template>
                  </b-form-select>
                </b-form-group>
              </li>
              <li class="d-flex mb-4 align-items-center" v-for="(item,index) in friend" :key="index">
                <div class="user-img img-fluid"><img :src="item.img" alt="story-img" class="rounded-circle avatar-40"></div>
                <div class="media-support-info ml-3">
                  <h6>{{item.name}}</h6>
                  <p class="mb-0">{{item.realtion}}</p>
                </div>
                <div class="edit-relation"><a href="#"><i class="ri-edit-line mr-2"></i>Edit</a></div>
              </li>
            </ul> -->
              <h5>Deposit will be done by </h5>
              <tab-nav :tabs="true" id="myTab-1">
                <tab-nav-items :active="true" id="credit-tab" ariaControls="credit" role="tab" :ariaSelected="true" title="Credit Card" />
                <tab-nav-items :active="false" id="paypal-tab" ariaControls="paypal" role="tab" :ariaSelected="false" title="Paypal" />
                <tab-nav-items :active="false" id="amazon-tab" ariaControls="amazon" role="tab" :ariaSelected="false" title="Amazon" />
              </tab-nav>
              <tab-content id="myTabContent">
                <tab-content-item :active="true" id="credit" aria-labelled-by="credit-tab">
                  <credit-card-form />  
                </tab-content-item>
                <tab-content-item :active="false" id="paypal" aria-labelled-by="paypal-tab">
                  <div class="text-center col-12 m-3">
                      <PayPal
                        amount="10.00"
                        currency="USD"
                        :client="credentials"
                        env="sandbox">
                      </PayPal>
                  </div>
                </tab-content-item>
                <tab-content-item :active="false" id="amazon" aria-labelled-by="amazon-tab">
                  <amazon-form />
                </tab-content-item>
              </tab-content>
          </tab-content-item>
        </div>
      </div>
    
    </div>
</template>

<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD" data-sdk-integration-source="button-factory"></script>
<script>
  function initPayPalButton() {
    paypal.Buttons({
      style: {
        shape: 'rect',
        color: 'gold',
        layout: 'vertical',
        label: 'paypal',
        
      },

      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{"amount":{"currency_code":"USD","value":1}}]
        });
      },

      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          alert('Transaction completed by ' + details.payer.name.given_name + '!');
        });
      },

      onError: function(err) {
        console.log(err);
      }
    }).render('#paypal-button-container');
  }
  initPayPalButton();
</script>

<script>
import { socialvue } from '../../../../config/pluginInit'
import VerifyFile from './VerifyFile'
import CreditCardForm from "@/components/Payment/CreditCardForm"
import PaypalForm from "@/components/Payment/PaypalForm"
import AmazonForm from "@/components/Payment/AmazonForm"
import axios from 'axios'
import PayPal from 'vue-paypal-checkout'

export default {
  name: 'About',
  created(){
    this.getProfileInfo();
    this.getLicenceFiles();
  },
  mounted () {
    socialvue.index();
  },
  components: {
    CreditCardForm,
    PaypalForm,
    AmazonForm,
    PayPal
  },
  data: () => ( {
      friend: [
        {
          img: require('../../../../assets/images/user/01.jpg'),
          name: 'Paul Molive',
          realtion: 'Brothe'
        },
        {
          img: require('../../../../assets/images/user/02.jpg'),
          name: 'Anna Mull',
          realtion: 'Sister'
        },
        {
          img: require('../../../../assets/images/user/03.jpg'),
          name: 'Paige Turner',
          realtion: 'Cousin'
        }
      ],
      work: [
        {
          img: require('../../../../assets/images/user/01.jpg'),
          name: 'Themeforest',
          des: 'Web Designer'
        },
        {
          img: require('../../../../assets/images/user/02.jpg'),
          name: 'iqonicdesign',
          des: 'Web Developer'
        },
        {
          img: require('../../../../assets/images/user/03.jpg'),
          name: 'W3school',
          des: 'Designer'
        }
      ],
      stateActive: {
        single: 'active',
        disable: 'active',
        number: 'one',
        colorDisabled: 'five',
        color: 'danger'
      },
      custom: [
        {
          name: 'licence',
          label: 'Driver',
          value: 'driver',
          disabled: false
        },
        {
          name: 'licence',
          label: 'Parking Spot',
          value: 'parking_spot',
          disabled: false
        },
        {
          name: 'licence',
          label: 'Vehicle',
          value: 'vehicle',
          disabled: false
        },
      ],
      documents:[],
      user: global.current_user,
      driverLicense: '',
      vehicleRegistration: '',
      insuranceCard:'',
      vehiclePicture:'',
      driverHeadshot:'',

      payment_options: [
        { value: 'default', text: 'Select Payment' },
        { value: 'credit', text: 'Credit Card' },
        { value: 'paypal', text: 'Paypal' },
        { value: 'amazon', text: 'Amazon Pay' }
      ],
      selected3 : 'default',

      credentials: {
        sandbox: 'ASvpnmDeg8bJcPcdiA0gspZ-awQamNDPpZvThPlAF11tVV7CzTzD70PTYj-f2U0tGfg-Ibx_9cCVWe-y',
        production: '<production client id>'
      }
    }
  ),

  methods: {
    getProfileInfo(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/profile/info?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        
        self.user.name        = response.data.payload.firstName + ' ' + response.data.payload.lastName;
        self.user.plateNumber = response.data.payload.plateNumber;
        self.user.guestPlate  = response.data.payload.guestPlate;
        self.user.verifiedDriver = response.data.payload.verifiedDriver;
        self.user.balance     = response.data.payload.balance;
        self.user.email       = response.data.payload.email;
        self.user.phone       = response.data.payload.phone;
      }).catch(function (error) {
        console.log(error);
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
      });
    },

    updateProfileInfo(){
      var self = this;
      var firstName = this.user.name.split(' ')[0];
      var lastName = this.user.name.split(' ')[1];
      this.user.firstName = firstName;
      this.user.lastName = lastName;

      axios.post(this.$apiAddress + '/x-user/edit-profile/account-info?token=' + localStorage.getItem("api_token"),
      {
        firstName : firstName,
        lastName  : lastName,
        email     : self.user.email,
        phone     : self.user.phone
      })
      .then(function (response) {
        console.log(response);
      }).catch(function (error) {
        console.log(error);
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
      });
    },

    uploadLicenceFiles(){
      var self = this;
      let formData = new FormData();
      formData.append('driverLicense', this.driverLicense);
      formData.append('vehicleRegistration', this.vehicleRegistration);
      formData.append('insuranceCard', this.insuranceCard);
      formData.append('vehiclePicture', this.vehiclePicture);
      formData.append('driverHeadshot', this.driverHeadshot);

      axios.post(  this.$apiAddress + '/x-user/edit-profile/submit-driver-documentations?token=' + localStorage.getItem("api_token"), 
        formData, 
        { 
          headers: { 'Content-Type': 'multipart/form-data' } 
        }
      )
      .then(function (response) {
        console.log(response);
      }).catch(function (error) {
        console.log("Error : " + error);
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
      });
    },

    handleLicence(event){
      this.driverLicense = event.target.files[0];
    },
    handleVehicle(event){
      this.vehicleRegistration = event.target.files[0];
    },
    handleInsurance(event){
      this.insuranceCard = event.target.files[0];
    },
    handleVehiclePicture(event){
      this.vehiclePicture = event.target.files[0];
    },
    handleHeadshot(event){
      this.driverHeadshot = event.target.files[0];
    },

    getLicenceFiles(){
      var self = this;
      axios.get(this.$apiAddress + '/x-user/edit-profile/get-driver-documentations?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.documents = response.data.payload;
      }).catch(function (error) {
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
        console.log("Error : " + error);
      });
    }
  }
}
</script>
