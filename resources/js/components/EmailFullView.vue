<template>
  <div id="EmailViewFull_comp" ref="EmailViewFull_comp" :start="start">
    <header-template ref="header_comp" class="border-b-2"/>
    <email-view-component ref="emailView_comp" id="emailView_comp"/>
  </div>
</template>
<script>
import Vue from "vue";

const header_template = Vue.component("header-template", require("./subcomponents/HeaderTemplate.vue").default);
const email_view_component = Vue.component("email-view-component", require("./subcomponents/EmailViewTemplate.vue").default);

export default Vue.extend({
  name: "EmailFullView",

  components:{
    header_template,
    email_view_component,
  },

  computed:{
    start(){
      
    },

    selected_email_rowData:{
      get(){
        console.log(this.$store.state.selected_email_rowData);
        return this.$store.state.selected_email_rowData;
      },set(new_data){
        return this.$store.dispatch('set_selected_email_rowData', new_data);
      }
    },

    routes(){
      return this.$store.state.routes;
    },

    selected_email_html_body(){
      return this.$store.state.selected_email_html_body;
    }
  },

  mounted(){
    this.$refs.header_comp.loading = false;
    this.$refs.header_comp.items_selected = true;
    this.$refs.header_comp.in_inbox = false;
    this.$refs.emailView_comp.in_inbox = false;
    this.$refs.header_comp.read_tgl_button_tt_content = !this.selected_email_rowData.read ? "Mark as read" : "Mark as unread";
    this.$refs.header_comp.read_tgl_button_icon = !this.selected_email_rowData.read ? "fas fa-envelope-open-text" : "fas fa-envelope";

  }
})
</script>
