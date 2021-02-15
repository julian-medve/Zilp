<template>
  <div class="">
    <div class="row">
      <div class="d-flex justify-content-center text-center">
        <h4>Balance : {{balanceWithDollar}}</h4>
      </div>
    </div>
    <div class="row">
      <div class="p-3 ">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend2">&#65504;</span>
          </div>
          <input type="number" class="form-control" v-model="withdraw_amount" aria-describedby="inputGroupPrepend2">
        </div>
        <br><br>
        <PayPal
          amount="withdraw_amount"
          currency="USD"
          :client="credentials"
          env="sandbox">
        </PayPal>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import PayPal from 'vue-paypal-checkout'

export default {
  name: 'Withdraw',
  components: {
    PayPal
  },  

  data(){
    return {
      user: global.current_user,
      credentials: {
        sandbox: 'Aa4F2Wi9UsQQ0hzL6Bn2_sd4rVJCWVLtOISGiTAQkccd-1pRY-GuP7vz34vivLfrf3qGaFr8_YqSx1LL',
        production: 'EE2UQO-DG8e_KJrJrMNtv6oBatrDjF99n9l1leXc5wi29cpHlB6LpM3LR6_-3rLHfWWCrajZAEQgi0lO'
      },
      withdraw_amount : 100,
    }
  },

  mounted: function () {
  },

  methods : {
    withdraw: function () {
      let self = this;
    },
  },

  computed:{
    balanceWithDollar(){
      var num = global.current_user.balance;
      var dollars = num / 100;
      dollars = dollars.toLocaleString("en-US", {style:"currency", currency:"USD"}); 
      return dollars;
    }
  },
}
</script>
