<template>
  <button v-on:click="toggle_sidebar = !toggle_sidebar">test</button>
  <div v-show="toggle_sidebar">
    <p>test</p>
  </div>
</template>
<script>
  export default {
    data(){
      return{
        toggle_sidebar: true
      }
    },
    mounted() {
      console.log("Webmail component mounted");
    }
  };
  
</script>