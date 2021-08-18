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

  props:{
    data: { type: Object, required: true }
  },

  components:{
    header_template,
    email_view_component,
  },

  computed:{
    start(){
      this.$store.dispatch("set_emailView_full", this);
      this.$store.dispatch("set_user_labels", this.data.labels)
      this.$store.dispatch("set_email_data", this.data.email_data);
      this.$store.dispatch("set_email_html_body", this.data.bodyHtml);

      if(this.data.attachments_files.length > 0){
        let files = [];

        this.data.attachments_files.forEach(file => {
          files.push({
            name: file,
            icon: fileIcons.getClassWithColor(file)
          })
        });

        this.$store.dispatch("set_email_attachments", files);
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
    this.$refs.header_comp.read_tgl_button_tt_content = !this.data.email_data.read ? "Mark as read" : "Mark as unread";
    this.$refs.header_comp.read_tgl_button_icon = !this.data.email_data.read ? "fas fa-envelope-open-text" : "fas fa-envelope";
    console.log(this.data);
  }
})
</script>
