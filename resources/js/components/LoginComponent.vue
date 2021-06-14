<template>
  <div class="p-5 m-auto shadow-black-lg" :class="[window === 0 ? 'login-box' : 'register-box']">
    <!-- <div class="mx-auto">
      <h1 class="text-gray-300">Webmail</h1>
    </div>-->
    <div class="relative">
      
      <!-- Login Form -->
      <div v-if="window === 0" class="relative h-full grid grid-rows-2">
        <div>
          <h1 class="text-xl font-semibold"><span class="font-black text-pink-600">|</span>Log in</h1>

          <ejs-textbox ref="email" id="email" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Email"></ejs-textbox>
          <ejs-textbox ref="password" id="password" type="password" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Password"></ejs-textbox>
        </div>
        <div class="flex">
          <div class="flex self-end w-full justify-between">
            <ejs-button @click.native="window = 1">Register</ejs-button>
            <ejs-button @click.native="login" isPrimary="true">LogIn</ejs-button>
          </div>
        </div>
      </div>
      <!-- Register Form -->
      <div v-if="window === 1" class="relative h-full grid grid-rows-2">
        <div>
          <h1 class="text-xl font-semibold"><span class="font-black text-pink-600">|</span>Register</h1>

          <div class="grid grid-cols-2 gap-4 pb-5">
            <ejs-textbox ref="first_name" id="first_name" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="First name"></ejs-textbox>
            <ejs-textbox ref="last_name" id="last_name" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Last name"></ejs-textbox>
            <div class="col-span-2">
              <ejs-textbox ref="email_address" id="email_address" type="email" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Email address"></ejs-textbox>
            </div>
          </div>
          

          <ejs-textbox ref="create_password" id="create_password" type="password" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Password"></ejs-textbox>
          <ejs-textbox ref="confirm_password" id="confirm_password" type="password" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Confirm password"></ejs-textbox>
        </div>
        <div class="flex">
          <div class="flex self-end w-full justify-between">
            <ejs-button @click.native="window = 0">Back</ejs-button>
            <ejs-button @click.native="newAccount" isPrimary="true">Register</ejs-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from "vue";

import { TextBoxPlugin } from '@syncfusion/ej2-vue-inputs';
import { FormValidator  } from '@syncfusion/ej2-vue-inputs';
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";

Vue.use(TextBoxPlugin);
Vue.use(ButtonPlugin);

function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

const capitalizeFirstLetter = ([ first, ...rest ], locale = navigator.language) => first.toLocaleUpperCase(locale) + rest.join('')

export default Vue.extend({
  name: "LoginComponent",
  props:{
    routes: { type: Object, required: true }
  },

  data(){
    return{
      window: 0
    }
  },

  mounted(){
  },

  methods:{
    newAccount(){
      if(!this.$refs.first_name.ej2Instances.value){
        alert("First name empty");
      }else if(!this.$refs.last_name.ej2Instances.value){
        alert("Last name empty");
      }else if(!this.$refs.email_address.ej2Instances.value){
        alert("Email address empty");
      }else if(!validateEmail(this.$refs.email_address.ej2Instances.value)){
        alert("Please enter a valid email");
      }else if(!this.$refs.create_password.ej2Instances.value){
        alert("Password empty");
      }else if(!this.$refs.confirm_password.ej2Instances.value){
        alert("Confirm password empty");
      }else if(this.$refs.create_password.ej2Instances.value !== this.$refs.confirm_password.ej2Instances.value){
        alert("two passwords do not match!");
      }else{
        let first_name = capitalizeFirstLetter(this.$refs.first_name.ej2Instances.value);
        let last_name = capitalizeFirstLetter(this.$refs.last_name.ej2Instances.value);
        let email_address = this.$refs.email_address.ej2Instances.value;
        let create_password = this.$refs.create_password.ej2Instances.value;

        axios({
          method: "POST",
          url: this.routes.register,
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer {{ csrf_token() }}"
          },
          params: {
            token: "{{ csrf_token() }}",
            first_name: first_name,
            last_name: last_name,
            email_address: email_address,
            create_password: create_password,
          }
        }).then(function (response) {
          console.log(response);
        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
        });
      }
    },

    login(){
      if(!this.$refs.email.ej2Instances.value){
        alert("Email address empty");
      }else if(!validateEmail(this.$refs.email.ej2Instances.value)){
        alert("Please enter a valid email");
      }else if(!this.$refs.password.ej2Instances.value){
        alert("Password cannot be empty");
      }else{
        let email = this.$refs.email.ej2Instances.value;
        let password = this.$refs.password.ej2Instances.value;

        axios({
          method: "POST",
          url: this.routes.logging_in,
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer {{ csrf_token() }}"
          },
          params: {
            token: "{{ csrf_token() }}",
            email: email,
            password: password,
          }
        }).then(function (response) {
          console.log(response);
          window.location.href = response.data;
        }).catch(error => {
          console.log(error);
          alert("Email or Password is Incorrect");
        });
      }
    }
  }
})
</script>
