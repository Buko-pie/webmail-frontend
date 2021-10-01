<template>
  <div>
    <div v-if="max_toggle" class="absolute centered-axis-xy h-full w-full bg-black bg-opacity-30" style="z-index: 9998;"></div>
    <div :start="start" class="overlay border-gray-300" :class="[ max_toggle ? 'centered-axis-xy' : 'absolute right-5 bottom-0 border' ]" :style="{width: w}">
      <div class="bg-gray-700 flex p-2">
        <p class="text-white font-semibold text-sm">{{ (email_subject === "") ? "New Message" : email_subject }}</p>
        
        <div class="ml-auto flex">
          <ejs-button 
            class="bg-gray-700 shadow-none hover:bg-gray-400 mini_btn"
            iconCss="text-white fas fa-window-minimize"
            @click.native="min_overlay"
          >
          </ejs-button>
          <ejs-button 
            class="bg-gray-700 shadow-none hover:bg-gray-400 mini_btn"
            iconCss="text-white text-sm far fa-window-maximize"
            @click.native="max_overlay"
          >
          </ejs-button>
          <ejs-button 
            class="bg-gray-700 shadow-none hover:bg-gray-400 mini_btn"
            iconCss="text-white text-sm far fa-window-close"
            @click.native="close_overlay"
          >
          </ejs-button>
        </div>
      </div>
      <div v-if="!min_toggle" class="bg-white" :style="{height: h}">
        <div class="flex px-2 items-center justify-items-center border-b border-gray-300">
          <a class="text-sm text-gray-500">To:</a>
          <vue-tags-input
            class="tags-no-broders"
            v-model="email_address_tag"
            ref="vue_tags_address"
            :tags="email_address_tags"
            :add-on-key="[13, 32]"
            :save-on-key="[13, 32]"
            :allowEditTags="true"
            placeholder=""
            @before-adding-tag="email_address_tags_add_class"
            @before-saving-tag="email_address_tags_edit"
            @tags-changed="email_address_tags_new"
            
          />
          <div class="ml-auto flex text-gray-500 font-bold">
            <p v-if="!add_cc" @click="add_cc = !add_cc; text_edit_h -= 39 ;" class="mr-3 cursor-pointer">Cc</p>
            <p v-if="!add_bcc" @click="add_bcc = !add_bcc; text_edit_h -= 39 ;" class="cursor-pointer">Bcc</p>
          </div>
        </div>
        <div v-if="add_cc" class="flex items-center border-b  border-gray-300 px-2">
          <a class="text-sm text-gray-500">Cc:</a>
          <vue-tags-input
            class="tags-no-broders"
            v-model="cc_address_tag"
            ref="cc_tags_address"
            :tags="cc_address_tags"
            :add-on-key="[13, 32]"
            :save-on-key="[13, 32]"
            :allowEditTags="true"
            placeholder=""
            @before-adding-tag="email_address_tags_add_class"
            @before-saving-tag="email_address_tags_edit"
            @tags-changed="cc_address_tags_new"
          />
        </div>
        <div v-if="add_bcc" class="flex items-center border-b border-gray-300 px-2">
          <a class="text-sm text-gray-500">Bcc:</a>
          <vue-tags-input
            class="tags-no-broders"
            v-model="bcc_address_tag"
            ref="bcc_tags_address"
            :tags="bcc_address_tags"
            :add-on-key="[13, 32]"
            :save-on-key="[13, 32]"
            :allowEditTags="true"
            placeholder=""
            @before-adding-tag="email_address_tags_add_class"
            @before-saving-tag="email_address_tags_edit"
            @tags-changed="bcc_address_tags_new"
          />
        </div>
        <div class="flex px-2 items-center justify-items-center border-b border-gray-300">
          <a class="text-sm text-gray-500">Subject:</a>
          <div class="p-2">
            <input type="text" class="focus:outline-none w-full" v-model="email_subject">
          </div>
        </div>
        <div class="flex flex-1" :style="{height: text_edit_h + 'px'}">
          <Vueditor class="w-full border-0 border-b border-gray-300" ref="vueditor_cont"></Vueditor>
        </div>
        <div v-if="show_attachment">
          <div class="border-b border-t w-full p-3 mt-5">
            <p class="text-base font-bold"><i class="fas fa-paperclip"></i> Attchments</p>
          </div>
          <ejs-uploader
            ref="ejs_uploader_reply"
            id="ejs_uploader_reply"
            name="UploadFiles"
            maxFileSize=26214400
            :asyncSettings="attachment_path"
            :uploading="attachmentUpload"
            :removing="removingAttachment"
            :sequentialUpload='true'
            :autoUpload='true'>
          </ejs-uploader>
        </div>
        <div class="flex w-full mt-3">
          <ejs-button @click.native="sendReply" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
          <ejs-button @click.native="show_attachment = !show_attachment; text_edit_h = show_attachment ? text_edit_h - 132 : text_edit_h + 132;"><i class="fas fa-paperclip"></i></ejs-button>
          <ejs-button @click.native="close_overlay" class="ml-auto bg-red-600 text-white"><i class="fas fa-trash"></i></ejs-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";

function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function validateEmails(emailArray){
  let results = emailArray.some(function(email, index){
    if(!validateEmail(email)){
      return true;
    }
  });

  return results;
}

export default Vue.extend({
  name: "Overlay",
  props:{
    index: { type: Number, required: true }
  },
  data(){
    return{
      w: 600,
      h: 568,
      text_edit_h: 440,
      routes: null,
      csrf_token: null,
      headers: null,

      type: "new_email",
      email_subject: "",

      show_attachment: false,
      min_toggle: false,
      max_toggle: false,
      email_address_tag: "",
      email_address_tags: [],
      email_addresses: null,

      add_cc: false,
      cc_address_tag: "",
      cc_address_tags: [],
      cc_addresses: null,
      add_bcc: false,
      bcc_address_tag: "",
      bcc_address_tags: [],
      bcc_addresses: null,

      attachment_path:{
        saveUrl: null,
        removeUrl: null
      },
    }
  },

  computed:{
    start(){
      this.routes = this.$store.state.routes;
      this.csrf_token = this.$store.state.csrf_token;
      this.user_email = this.$store.state.user_email;
      this.headers = {
        "Content-Type": "multipart/mixed",
        "Authorization": "Bearer " + this.csrf_token,
        "X-CSRF-TOKEN": this.csrf_token
      }

      this.attachment_path.saveUrl = this.routes.upload_attachment;
      this.attachment_path.removeUrl = this.routes.remove_attachment;
    },

     ref_sidebar(){
      return this.$store.state.sidebar;
    },
  },

  methods:{
    min_overlay(){
      this.min_toggle = !this.min_toggle;
      if(this.min_toggle){
        this.w = 260;
        this.h = 0;
      }else{
        this.w = 600;
        this.h = 568;
      }

      if(this.max_toggle){
        this.max_toggle = false;
      }
    },
    max_overlay(){
      this.max_toggle = !this.max_toggle;

      if(this.max_toggle){
        this.w = "80%";
        this.h = "80%";
      }else{
        this.w = 600;
        this.h = 568;
      }

      if(this.min_toggle){
        this.w = 600;
        this.h = 568;
        this.min_toggle = false;
      }
    },
    close_overlay(){
      this.ref_sidebar.overlays.pop();
    },

    ////////////////////////////////////
    email_address_tags_add_class(args){
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
      }else{
        args.tag.classes = "bg-red-500 rounded-full px-3 justify-center items-center";
      }
      
      args.addTag();
    },

    email_address_tags_edit(args){
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
      }else{
        args.tag.classes = "bg-red-500 rounded-full px-3 justify-center items-center";
      }
      
      args.saveTag();
    },

    email_address_tags_new(tags){
      let tags_text = [];

      tags.forEach(tag => {
        tags_text.push(tag.text);
      });

      this.email_addresses = tags_text;
    },

    cc_address_tags_new(tags){
      let tags_text = [];

      tags.forEach(tag => {
        tags_text.push(tag.text);
      });

      this.cc_addresses = tags_text;
    },

    bcc_address_tags_new(tags){
      let tags_text = [];

      tags.forEach(tag => {
        tags_text.push(tag.text);
      });

      this.bcc_addresses = tags_text;
    },

    attachmentUpload(args){
      args.currentRequest.setRequestHeader("Authorization", "Bearer " + this.csrf_token);
      args.currentRequest.setRequestHeader("X-CSRF-TOKEN", this.csrf_token);

      args.customFormData = [
        {id: args.fileData.id},
        {filename: args.fileData.name},
        {size: args.fileData.size},
        {type: args.fileData.type}
      ];
      console.log(args);
    },

    removingAttachment(args){
      args.postRawFile = false;
      args.currentRequest.setRequestHeader("Authorization", "Bearer " + this.csrf_token);
      args.currentRequest.setRequestHeader("X-CSRF-TOKEN", this.csrf_token);
    },

    sendReply(){
      console.log('send reply...');

      let _this = this;
      let invalid_emails = true;
      let invalid_ccs = false;
      let invalid_bccs = false;
      let files = [];

      if(this.$refs.ejs_uploader_reply !== undefined){
        files = this.$refs.ejs_uploader_reply.getFilesData();
      }
      let attachments = [];

      if(this.email_addresses !== null){
        invalid_emails = validateEmails(this.email_addresses);
      }else{
        this.$notification.warning("No email addresses added!", {  timer: 5 });
      }

      if(this.cc_addresses !== null){
        invalid_ccs = validateEmails(this.cc_addresses);
      }

       if(this.bcc_addresses !== null){
        invalid_bccs = validateEmails(this.bcc_addresses);
      }

      if(files.length !== 0){
        files.forEach(file => {
          if(file.statusCode === "2"){
            attachments.push({
              id: file.id,
              filename: file.name,
              size: file.size,
              status: "uploaded",
              type: file.type,
            });
          }
        });
      }

      if(invalid_emails || invalid_ccs || invalid_bccs){
        return alert("invalid email exists!");
      }

      let data = {
        option: this.type,
        // email_id: this.email_data.email_id,
        addresses: this.email_addresses,
        cc: this.cc_addresses,
        bcc: this.bcc_addresses,
        subject: this.email_subject,
        message: this.$refs.vueditor_cont.getContent(),
        attachments: attachments,
      }

      axios.post(this.routes.send_mail, data)
          .then((response) => {
            let message = _this.email_action === "reply_email" ? "Reply Sent" : "Email Sent";
            _this.$notification.success(message, {  timer: 5 });
            this.show_reply = false;
          }).catch(error => {
            console.log(error);
            _this.$notification.error("somthing went wrong", {  timer: 5 });
          });
    },
  }
});
</script>
