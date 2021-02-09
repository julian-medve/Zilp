<template>
  <div class="">
    <div class="row">
      <div class="d-flex justify-content-center text-center">
        <h4>Balance : ${{user.balance}}</h4>
      </div>
    </div>
    <div class="row">
      <div class="p-3 ">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend2">$</span>
          </div>
          <input type="number" class="form-control" v-model="deposit_amount" aria-describedby="inputGroupPrepend2">
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
      credentials : '',
      withdraw_amount : 100,
    }
  },

  mounted: function () {
  },

  methods : {
    withdraw: function () {
      let self = this;
    },
  }
}
</script>
