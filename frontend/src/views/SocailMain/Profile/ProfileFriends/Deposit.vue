<template>
  <div class="">
    <div class="row p-3">
      <div class="d-flex justify-content-center text-center">
        <h4>Balance : ${{user.balance}}</h4>
      </div>
    </div>
    <div class="row p-3">
      <div class="col-md-12 d-flex ">
        <div class="col-md-8 offset-2 input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend2">&#65504;</span>
          </div>
          <input type="number" class="form-control" v-model="deposit_amount" aria-describedby="inputGroupPrepend2">
        </div>
      </div>
    </div>

    <div class="iq-card-body">
        <ul class="nav nav-tabs" id="myTab-1" role="tablist">
          <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent-2">
          <div class="tab-pane fade show active pt-3" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row justify-content-center">
              <div class="col-md-6">
                <div ref="card" style="border-radius:10px; border:1px #D8D8D8 solid; padding:10px;"></div>
              </div>
              <div class="col-md-6">
                <button class="btn btn-primary btn-lg" @click="deposit()">Pay with Card</button>
              </div>
            </div>
          </div>
          <div class="tab-pane fade " id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="justify-content-center pt-3">
              <PayPal
                amount="deposit_amount"
                currency="USD"
                :client="credentials"
                env="sandbox"
                payment-completed="paypalDepositCompleted()"
                payment-cancelled="paypalDepositCancelled()">
              </PayPal>
            </div>
          </div>
          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row justify-content-center pt-3">
              <div id="LoginWithAmazon"></div>
            </div>
          </div>
        </div>
    </div>   
  </div>
</template>

<script>
import axios from 'axios'
import PayPal from 'vue-paypal-checkout'

// Stripe 
let stripe = Stripe(`pk_test_51I4DakGicXp0OLcaP0os27g9eTWGI4p1nJ7CgSkDmXCgDOr7Mt6KRH3tesFORFyk9oE2RoaMuBqg2hGJjtqHFzHt00CkbSzu0V`),
elements = stripe.elements(),
card = undefined;


// Amazon Pay
window.onAmazonPaymentsReady = function(){
  // render the button here
  var authRequest; 

  OffAmazonPayments.Button('LoginWithAmazon', 'SELLER-ID', {
    type:  'PwA', 
    color: 'Gold', 
    size:  'medium', 
    language: 'en-GB',

    authorization: function() { 
      loginOptions = {scope: 'SCOPES', popup: POPUP}; 
      authRequest = amazon.Login.authorize (loginOptions, 'REDIRECT_URL'); 
    } 
  });
}

let style = {
  base: {
    border: '1px solid #D8D8D8',
    borderRadius: '4px',
    color: "#000",
  },

  invalid: {
    // All of the error styles go inside of here.
  }
};

// Paypal
// const PayPalButton = paypal.Buttons.driver("vue", window.Vue);

export default {
  name: 'Deposit',
  components: {
    PayPal
  },  

  data(){
    return {
      user: global.current_user,
      deposit_amount : 100,
      credentials: {
        sandbox: 'AfZpa0mZQUHVdstVt-WSbZLCHbawyaX_KDCnx2WgFA2wKL1FOX3nBmJYaf1vQMNOndv5WgEbE6Xd1Qef',
        production: 'EE2UQO-DG8e_KJrJrMNtv6oBatrDjF99n9l1leXc5wi29cpHlB6LpM3LR6_-3rLHfWWCrajZAEQgi0lO'
      },

      base: {
        border: '1px solid #D8D8D8',
        borderRadius: '4px',
        color: "#000",
      },
    }
  },

  mounted: function () {
    // Stripe
    let style = {
      base: {
        border: '1px solid #000000',
        borderRadius: '4px',
        color: "#000",
      },

      invalid: {
        // All of the error styles go inside of here.
      }

    };
    card = elements.create('card', style);
    card.mount(this.$refs.card);

    amazon.Login.setClientId('CLIENT-ID');
  },

  methods : {
    deposit: function () {
      let self = this;
      stripe.createToken(card).then(function(result) {
        console.log("Stripe deposit : ", result)
        if (result.error) {
          self.hasCardErrors = true;
          self.$forceUpdate(); // Forcing the DOM to update so the Stripe Element can update.
          return;
        }else{
          self.stripeTokenHandler(result.token);
        }
      });
    },

    stripeTokenHandler(token) {
      var self = this;
      // Insert the token ID into the form so it gets submitted to the server
      axios.post(this.$apiAddress + '/x-user/billing/stripe-charge?token=' + localStorage.getItem("api_token"), {
        paymentId : token.id,
        amount : self.deposit_amount
      }).then(function (response) {
        console.log("responese : ", response);
      }).catch(function (error) {
        console.log(error);
        if(error.response.status == 401)
          self.$router.push({ path: '/auth/signin' });
      });
    },

    paypalDepositCompleted(response){
      console.log("Paypal desposit completed : ", response);
    },

    paypalDepositCancelled(response){
      console.log("Paypal desposit cancelled : ", response);
    }
  }
}
</script>
