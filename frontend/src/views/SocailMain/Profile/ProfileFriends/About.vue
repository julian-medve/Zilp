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
                <h6>Full name</h6>
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
                <b-button v-b-modal.modal-7 variant="primary" class="mr-1"><i class="ri-edit-line mr-2"></i>Edit Contact Info</b-button>
              </div>

              
              <b-modal id="modal-7" centered title="Contact Info" ok-title="Save Changes" cancel-title="Close">
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
              <VerifyFile />
              
              <h4 class="mt-3 mb-3">Upload document</h4>
              <hr>
              <ul class="suggestions-lists m-0 p-0">
                <li class="d-flex mb-4 align-items-center">
                  <div class="media-support-info ml-3">
                    <h6>Please submit driver, parking spot, or car licence document to verify.</h6>
                  </div>
                </li>
              </ul>
              <b-form>
                <div class="m-3">
                <template v-for="(item,index) in custom">
                  <b-form-radio inline v-model="stateActive[item[Object.keys(item)[0]]]" :name="item.name" :key="index" :value="item.value" :disabled="item.disabled">{{ item.label }}</b-form-radio>
                </template>
                </div>
                <b-form-group label-for="customFile" >
                  <b-form-file placeholder="Choose a file" id="customFile" ></b-form-file>
                </b-form-group>
                <b-button type="submit" variant="primary">Submit</b-button>
              </b-form>
          </tab-content-item>
          <tab-content-item :active="false" id="financial-info" aria-labelled-by="pills-financial-info">
            <div class="row col-md-12 ">
              <div class="d-flex justify-content-center text-center"><h3>Balance : $2000</h3></div>
            </div>
            
            <h5>Withdraw will be done by </h5><hr>
            <paypal-form />
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
                    <paypal-form />
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
<script>
import { socialvue } from '../../../../config/pluginInit'
import VerifyFile from './VerifyFile'
import CreditCardForm from "@/components/Payment/CreditCardForm"
import PaypalForm from "@/components/Payment/PaypalForm"
import AmazonForm from "@/components/Payment/AmazonForm"

export default {
  name: 'About',
  mounted () {
    socialvue.index()
  },
  components: {
    VerifyFile,
    CreditCardForm,
    PaypalForm,
    AmazonForm
  },
  data () {
 
    return {
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
      user : global.user,
      payment_options: [
        { value: 'default', text: 'Select Payment' },
        { value: 'credit', text: 'Credit Card' },
        { value: 'paypal', text: 'Paypal' },
        { value: 'amazon', text: 'Amazon Pay' }
      ],
      selected3 : 'default'
    }
  },
  uploadFile(){

  }
}
</script>
