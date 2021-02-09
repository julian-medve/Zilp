<template>
    <div class="row">
      <div class="col-md-3">
        <tab-nav :pills="true" id="tab" class="nav nav-pills basic-info-items list-inline d-block p-0 m-0" >
          <tab-nav-items :active="true" id="pills-contact-info" href="#contact-info" ariaControls="pills-contact-info" role="tab" :ariaSelected="true" title="Contact" />
          <tab-nav-items :active="false" id="pills-verified-info" href="#verified-info" ariaControls="pills-verified-info" role="tab" :ariaSelected="true" title="Licence" />
          <tab-nav-items :active="false" id="pills-deposit-info" href="#deposit-info" ariaControls="pills-deposit-info" role="tab" :ariaSelected="true" title="Deposit" />
          <tab-nav-items :active="false" id="pills-withdraw-info" href="#withdraw-info" ariaControls="pills-withdraw-info" role="tab" :ariaSelected="true" title="Withdraw" />
        </tab-nav>
      </div>
      <div class="col-md-9 p-3">
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
          <tab-content-item :active="false" id="deposit-info" aria-labelled-by="pills-deposit-info">
            <Deposit />
          </tab-content-item>
                    
          <tab-content-item :active="false" id="withdraw-info" aria-labelled-by="pills-withdraw-info">
            <div class="row col-md-12 p-3">
              <Withdraw />
            </div> 
          </tab-content-item>
        </div>
      </div>
    
    </div>
</template>

<script>
import { socialvue } from '../../../../config/pluginInit'
import VerifyFile from './VerifyFile'
import axios from 'axios'
import Deposit from './Deposit'
import Withdraw from './Withdraw'

export default {
  components: { Deposit, Withdraw },
  name: 'About',
  created(){
    this.getProfileInfo();
    this.getLicenceFiles();
  },
  mounted () {
    socialvue.index();
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
      selected3 : 'default',
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
