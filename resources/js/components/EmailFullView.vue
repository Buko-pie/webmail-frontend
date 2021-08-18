<template>
  <div>
    <p>test</p>
    <p>{{ routes }}</p>
  </div>
</template>
<script>
import Vue from "vue";

export default Vue.extend({
  name: "EmailFullView",
  computed:{
    routes(){
      return this.$store.state.routes;
    },

    selected_email_html_body(){
      return this.$store.state.selected_email_html_body;
    }
  }
})
</script>
