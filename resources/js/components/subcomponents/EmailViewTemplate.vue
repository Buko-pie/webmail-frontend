<template>
<div id="email_html_body" class="p-5" :start="start">
  <div v-if="email_data" class="flex">
    <div>
      <p class="font-bold text-base">{{ email_data.from.name }}</p>
      <p class="text-gray-500 text-sm"> {{ email_data.from.email }} </p>
    </div>

    <div class="ml-auto">
      {{ email_date_display }}
    </div>
  </div>
  <div class="divide-y divide-gray-500 mt-5 pt-3">
    <div v-html="email_body_html">
      {{ email_body_html }}
    </div>
    <div v-if="email_attachments !== null" class="mt-8 pt-3">

      <div v-for="(file, index) in email_attachments" :key="index" @click="attachmentClicked($event, file.name)" class="flex bg-white mb-4 border rounded-lg w-48 h-10 items-center truncate cursor-pointer">
        <p class="font-bold px-3 py-5 w-40">{{ file.name }}</p>
      </div>
    </div>
  </div>

  <div v-if="email_body_html && !show_reply" id="email_actions" class="flex mt-10 pt-2">
    <div class="m-2">
      <div @click="replyEmail" class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
        <p class="flex font-bold px-3 py-5 w-40 items-center justify-center"><i class="fas fa-reply pr-2"></i> Reply</p>
      </div>
    </div>
    <div class="m-2">
      <div @click="forwardEmail" class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
        <p class="flex font-bold px-3 py-5 w-40 items-center justify-center"><i class="fas fa-arrow-right pr-2"></i> Forward</p>
      </div>
    </div>
  </div>

  <div v-if="show_reply" id="email_actions" class="grid grid-cols-4 gap-3 mt-6 pt-3">
    <div :class="[ show_attachment ? 'col-span-3' : 'col-span-4']">
      <div class="w-full">
        <div class="flex w-full">
          <div>
            <div class="flex items-center">
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
            </div>

            <div v-if="add_cc" class="flex items-center">
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

            <div v-if="add_bcc" class="flex items-center">
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
          </div>
          
          <div class="ml-auto flex text-gray-500 font-bold">
            <p v-if="!add_cc" @click="add_cc = !add_cc" class="mr-3 cursor-pointer">Cc</p>
            <p v-if="!add_bcc" @click="add_bcc = !add_bcc" class="cursor-pointer">Bcc</p>
          </div>
        </div>
        
        <Vueditor ref="reply_content" class="w-full"></Vueditor>

        <div class="flex w-full mt-3">
          <ejs-button @click.native="sendReply" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
          <ejs-button @click.native="show_attachment = !show_attachment"><i class="fas fa-paperclip"></i></ejs-button>
          <ejs-button @click.native="replyEmail" class="ml-auto bg-red-600 text-white"><i class="fas fa-trash"></i></ejs-button>
        </div>
      </div>
    </div>
    <div v-if="show_attachment" class="col-span-1">
      <div class="border w-full p-3 mt-5">
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
        :autoUpload='true' >
      </ejs-uploader>
    </div>
  </div>
</div>
</template>

<script>
import moment from "moment";

function formatDate(date) {
  let new_date = moment(date).format("LLL");
  return new_date;
}

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

export default({
  name: "EmailViewTemplate",
  data(){
    return{
      routes: null,
      csrf_token: null,
      user_email: null,
      headers: null,
      email_action: null,
      show_reply: false,
      show_attachment: false,
      email_date_display: null,
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
    };
  },

  computed:{
    start(){
      console.log("email view computed")
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

    current_inbox(){
      return this.$store.state.current_inbox;
    },

    email_body_html(){
      return this.$store.state.selected_email_html_body;
    },

    email_data(){
      this.show_reply = false;
      this.email_action = null;
      let data = this.$store.state.selected_email_data

      if(data !== null){
        this.email_date_display = formatDate(data.date);
      }
      
      return data;
    },

    email_attachments(){
      return this.$store.state.selected_email_attachments;
    }
  },

  methods:{
    attachmentClicked(event, file){
      console.log(file);

      const url = this.routes.download_attachment + "?file=" + file;
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', file);
      document.body.appendChild(link);
      link.click()
    },

    replyEmail(){
      console.log("replyEmail");
      this.show_reply = !this.show_reply;
      this.email_action = "reply_email";
      this.add_cc = false;
      this.add_bcc = false;
      let data = this.email_data;

      if(data !== null){
        
        if(this.current_inbox === "sent"){
          let reply_address = data.to.email ? data.to.email : data.to.name;
          this.email_addresses = [reply_address];

          this.email_address_tags = [{
            text: reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];
        }else{
          let reply_address = data.from.email ? data.from.email : data.from.name;
          this.email_addresses = [reply_address];

          this.email_address_tags = [{
            text: reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];
        }
        
      }
    },

    forwardEmail(){
      this.show_reply = !this.show_reply;
      this.email_action = "forward_email";
      this.add_cc = false;
      this.add_bcc = false;
      this.email_address_tags = [];
      this.email_addresses = null;
      let from_email = this.email_data.from.email !== null ? this.email_data.from.email : this.email_data.from.name;
      let to_email = this.email_data.to.email ? this.email_data.to.email : this.email_data.to.name;

      console.log(this.email_data.from.name);
      console.log(from_email);

      let forward_msg_template = "<div><br></div><div><br></div>" +
        "<div>On " + moment().format("LLLL") + " < " + this.user_email + " > wrote:</div>" +
        "<div>---------- Forwarded message ---------</div>" +
        "<div>From: &lt;" + from_email + "&gt;</div>" +
        "<div>Date: " + moment(this.email_data.date).format("llll") + "</div>" +
        "<div>To: &lt;" + to_email + "&gt;</div>"
      
      console.log( forward_msg_template);

      if(this.email_data.cc !== null){
        let cc_email = this.email_data.cc;
        cc_email = cc_email.replace(/"/g, "");
        cc_email = cc_email.replace(/</g, "&lt;");
        cc_email = cc_email.replace(/>/g, "&gt;");
        
        forward_msg_template = forward_msg_template + "<div>Cc: " + cc_email + "</div>"
      }

      forward_msg_template = forward_msg_template + "<div><br></div><div><br></div>" + this.email_body_html;
      setTimeout(() => this.$refs.reply_content.setContent(forward_msg_template), 200);
      
    },

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

    sendReply(){
      console.log('send reply...');
      console.log(this.$refs.vue_tags_address.tags);

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
        alert("No email addresses added");
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
        console.log(attachments);
      }

      if(!invalid_emails && !invalid_ccs && !invalid_bccs){
        axios({
          method: "POST",
          url: this.routes.send_mail,
          headers: this.headers,
          params: {
            option: this.email_action,
            email_id: this.email_data.email_id,
            addresses: this.email_addresses,
            cc: this.cc_addresses,
            bcc: this.bcc_addresses,
            message: this.$refs.reply_content.getContent(),
            attachments: attachments,
          }
        }).then(function (response) {
          console.log(response.data);
        }).catch(error => {
          console.log(error);
          alert("somthing went wrong");
        });
      }else{
        alert("invalid email exists!");
      }
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
  }
});
</script>
