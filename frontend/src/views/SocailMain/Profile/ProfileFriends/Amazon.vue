<template>
  <div>
    <div class="row">
      <div class="col-md-6">
        <div id="AmazonPayButton">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

window.onAmazonLoginReady = function () {
  alert("onAmazonLoginReady");
  amazon.Login.setClientId('amzn1.application-oa2-client.ec19611c335849e6957859a132880a1f');
  amazon.Login.setUseCookie(true);
};


export default {
  name: 'Amazon',

  props:['depositAmount'],

  data() {
    return {
    };
  },

  mounted: function() {
    // this.includeAmazon(
    //   "static-eu.payments-amazon.com/OffAmazonPayments/uk/sandbox/js/Widgets.js",
    //   function () {
    //     this.configureAmazon();
    //   }.bind(this)
    // );

    this.configureAmazon();
  },

  methods: {
    includeAmazon(URL, callback) {
      let documentTag = document,
      tag = "script",
      object = documentTag.createElement(tag),
      scriptTag = documentTag.getElementsByTagName(tag)[0];
      object.src = "//" + URL;
      var self = this;
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

    configureAmazon() {
      console.log("amazon : ", amazon);

      amazon.Login.setClientId('amzn1.application-oa2-client.ec19611c335849e6957859a132880a1f');
      amazon.Login.setUseCookie(true);

      var authRequest;
      OffAmazonPayments.Button("AmazonPayButton", "A25NVYELWVORGI", {
          type:  'PwA', 
          color: 'Gold', 
          size:  'medium', 
          language: 'en-US',

          authorization: function () {
              var loginOptions = { scope: "payments:widget payments:shipping_address", popup: true, language:'en-US' };
              authRequest = amazon.Login.authorize(loginOptions, "https://amzn.github.io/login-and-pay-with-amazon-sdk-samples/set.html");
          },
          onError: function (error) {
              console.log("Amazon Auth Error : ", error);
          }
      });
    },
  }
};
</script>