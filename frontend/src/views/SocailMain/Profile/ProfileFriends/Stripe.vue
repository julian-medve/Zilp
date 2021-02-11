<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <div id="card-element" style="border:1px black solid; padding:10px; border-radius:5px;"></div>
      </div>
      <div class="col-md-6">
        <button class="btn btn-primary" id="add-card-button" v-on:click="submitPaymentMethod()">
            Pay with Card
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: 'Stripe',

  props:['depositAmount'],

  data() {
    return {
      stripeAPIToken:
        "pk_test_51I4DakGicXp0OLcaP0os27g9eTWGI4p1nJ7CgSkDmXCgDOr7Mt6KRH3tesFORFyk9oE2RoaMuBqg2hGJjtqHFzHt00CkbSzu0V",
      stripe: "",
      elements: "",
      card: "",
      intentToken: "",

      name: global.current_user.name,
      addPaymentStatus: 0,
      addPaymentStatusError: "",
      paymentMethods: [],
      paymentMethodsLoadStatus: 0,
      paymentMethodSelected: {}
    };
  },

  mounted() {
    this.includeStripe(
      "js.stripe.com/v3/",
      function () {
        this.configureStripe();
      }.bind(this)
    );

    this.loadIntent();
    // this.loadPaymentMethods();
  },

  methods: {
    /*
        Includes Stripe.js dynamically
    */
    includeStripe(URL, callback) {
      let documentTag = document,
        tag = "script",
        object = documentTag.createElement(tag),
        scriptTag = documentTag.getElementsByTagName(tag)[0];
      object.src = "//" + URL;
      if (callback) {
        object.addEventListener(
          "load",
          function (e) {
            callback(null, e);
          },
          false
        );
      }
      scriptTag.parentNode.insertBefore(object, scriptTag);
    },

    configureStripe() {
      this.stripe = Stripe(this.stripeAPIToken);

      this.elements = this.stripe.elements();
      this.card = this.elements.create("card");

      this.card.mount("#card-element");
    },

    loadIntent() {
      axios.get(this.$apiAddress + '/x-user/billing/stripe/setup-intent?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
          this.intentToken = response.data;
        }.bind(this)
      ).catch(function(error){
          console.log("Load intent response : ", error);
      });
    },

    // Charge Amount by Stripe
    savePaymentMethod( method ){
        axios.post(this.$apiAddress + '/x-user/billing/stripe/save-payment-method?token=' + localStorage.getItem("api_token"),{
            payment_method: method,
            amount: this.depositAmount
        }).then( function(response){
            console.log("Save payment method : ", response);
            global.current_user.balance = parseInt(global.current_user.balance) + parseInt(this.depositAmount);
        }.bind(this));
    },

    submitPaymentMethod() {
      this.addPaymentStatus = 1;
      this.stripe
      .confirmCardSetup(this.intentToken.client_secret, {
        payment_method: {
          card: this.card,
          billing_details: {
            name: this.name,
          },
        },
      })
      .then(
        function (result) {
          if (result.error) {
            this.addPaymentStatus = 3;
            this.addPaymentStatusError = result.error.message;
          } else {
            this.savePaymentMethod(result.setupIntent.payment_method);
            this.addPaymentStatus = 2;
            this.card.clear();
            this.name = "";
          }
        }.bind(this)
      );
    },

    loadPaymentMethods(){
        axios.get('/api/v1/user/payment-methods')
        .then( function( response ){
            this.paymentMethods = response.data;
            this.paymentMethodsLoadStatus = 2;
        }.bind(this));
    },

    removePaymentMethod( paymentID ){
        axios.post('/api/v1/user/remove-payment', {
            id: paymentID
        }).then( function( response ){
            this.loadPaymentMethods();
        }.bind(this));
    }
  },
  
};
</script>