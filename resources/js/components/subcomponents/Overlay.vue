<template>
  <div>
    <div v-if="max_toggle" class="absolute centered-axis-xy h-full w-full bg-black bg-opacity-30" style="z-index: 9998;"></div>
    <div :start="start" class="overlay border-gray-300" :class="[ max_toggle ? 'centered-axis-xy' : 'absolute right-5 bottom-0 border' ]" :style="{width: w}">
      <div class="bg-gray-700 flex p-2">
        <p class="text-white font-semibold text-sm">{{ (email_subject === null) ? "New Message" : email_subject }}</p>
        
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
            :autocomplete-items="autocompleteItems"
            @input="getEmails"
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
          <div class="p-2 w-full">
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
          <div class="max-h-24 overflow-y-scroll">
            <ejs-uploader
                ref="ejs_uploader_reply"
                id="ejs_uploader_reply"
                name="UploadFiles"
                maxFileSize=26214400
                :asyncSettings="attachment_path"
                :uploading="attachmentUpload"
                :removing="removingAttachment"
                :sequentialUpload='true'
                :autoUpload='true'
            />
          </div>
        </div>
        <div class="absolute bottom-1 flex w-full mt-3">
          <ejs-button @click.native="sendReply" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
          <ejs-button @click.native="show_attachment = !show_attachment; text_edit_h = show_attachment ? text_edit_h - 155 : text_edit_h + 155;"><i class="fas fa-paperclip"></i></ejs-button>
          <ejs-button @click.native="close_overlay" class="ml-auto bg-red-600 text-white"><i class="fas fa-trash"></i></ejs-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Vue from "vue";
import moment from "moment";

function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function extractEmails(text){
  const re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/g;
  return text.match(re);
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
  props:[
    "ref_sidebar",
    "index",
    "routes",
    "csrf_token",
    "user_email",
    "attachment_path",
    "email_id",
    "email_action",
    "reply_to_email",
    "email_subject",
    "email_body_html",
    "email_date",
    "recipients",
    "cc_info"
  ],
  data(){
    return{
      w: 600,
      h: 568,
      text_edit_h: 440,
      headers: null,

      type: "new_email",
      change_subj: false,

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

      autocompleteItems:[]

    }
  },

  computed:{
    start(){
      console.log(this.routes);
      this.headers = {
        "Content-Type": "multipart/mixed",
        "Authorization": "Bearer " + this.csrf_token,
        "X-CSRF-TOKEN": this.csrf_token
      }
    }
  },

  mounted(){

    if(this.email_action === "reply_email" && this.reply_to_email !== null){
      this.reply_method();
    }else if(this.email_action === "reply_all_email" && this.reply_to_email !== null){
      
      this.add_cc = true
      let recipients_addresses = [];

      this.recipients.forEach(address => {
        let ue = this.user_email.replace(/\./g, "")
        let ae = address.email.replace(/\./g, "")

        if(ae !== ue){
          
          recipients_addresses.push(address.email);

          this.cc_address_tags.push({
            text: address.email,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          });
        }
      });

      if(this.cc_info !== null){
        console.log(this.cc_info);
        let cc_emails = extractEmails(this.cc_info);
        console.log(cc_emails);

        cc_emails.forEach(address => {
          let ue = this.user_email.replace(/\./g, "")
          let ae = address.replace(/\./g, "")

          if(ae !== ue){
            
            recipients_addresses.push(address);

            this.cc_address_tags.push({
              text: address,
              classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
            });
          }
        });
      }

      this.cc_addresses = recipients_addresses;

      this.reply_method();
    }else if(this.email_action === "forward_email" && this.reply_to_email !== null){
      if(!this.email_subject.includes("Re: ")){
        this.email_subject = "Fwd: " + this.email_subject;
      }else{
        this.email_subject = this.email_subject.replace(/Re: /g, "Fwd: ");
      }
      
      this.reply_content = "<div><br></div><div><br></div>" +
        "<div dir='ltr'>On " + moment().format("LLLL") + " <<a href='mailto:" + this.user_email +"'>" + this.user_email + "</a>> wrote:</div>" +
        "<div class='gmail_quote'>" +
        "<blockquote  style='margin: 0px 0px 0px 0.8ex;border-left: 1px solid rgb(204, 204, 204);padding-left: 1ex;'>" +
        "<div>---------- Forwarded message ---------</div>" +
        "<div>From: &lt;<a href='mailto:" + this.user_email +"'>" + this.user_email + "</a>&gt;</div>" +
        "<div>Date: " + moment(this.email_date).format("llll") + "</div>" +
        "<div>Subject: " + this.email_subject.replace(/Re: /g, "") + "</div>" +
        "<div>To: &lt;<a href='mailto:" + this.reply_to_email +"'>" + this.reply_to_email + "</a>&gt;</div>"

      if(this.cc_info !== null){
        let cc_email = this.cc_info;
        cc_email = cc_email.replace(/"/g, "");
        cc_email = cc_email.replace(/</g, "&lt;");
        cc_email = cc_email.replace(/>/g, "&gt;");

        this.reply_content = this.reply_content + "<div>Cc: " + cc_email + "</div>"
      }

      this.reply_content = this.reply_content + "<div><br></div><div><br></div>" + this.email_body_html +"</blockquote></div>";
      setTimeout(()=>{
        this.$refs.vueditor_cont.setContent(this.reply_content);
      }, 200);
    }
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
      this.$destroy();
      this.$el.parentNode.removeChild(this.$el);
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

    reply_method(){
      if(!this.email_subject.includes("Re: ")){
        this.email_subject = "Re: " + this.email_subject;
      }

      this.email_addresses = [this.reply_to_email];
      this.email_address_tags = [{
        text: this.reply_to_email,
        classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
      }];

      this.reply_content = "<div><br></div><div><br></div>" +
        "<div class='gmail_quote'>" +
          "<div dir='ltr'>On " + moment(this.email_date).format("LLLL") + " <<a href='mailto:" + this.reply_to_email +"'>" + this.reply_to_email + "</a>> wrote:</div>" +
          "<blockquote  style='margin: 0px 0px 0px 0.8ex;border-left: 1px solid rgb(204, 204, 204);padding-left: 1ex;'>" +
            this.email_body_html +
          "</blockquote>" +  
        "</div>";
      
      setTimeout(()=>{
        this.$refs.vueditor_cont.setContent(this.reply_content);
      }, 200);
    },

    sendReply(){
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
        option:       this.email_action === "reply_email" ? "reply_email" : "new_email",
        email_id:     this.email_id,
        addresses:    this.email_addresses,
        cc:           this.cc_addresses,
        bcc:          this.bcc_addresses,
        subject:      this.email_subject,
        change_subj:  this.change_subj,
        message:      this.$refs.vueditor_cont.getContent(),
        attachments:  attachments,
      }

      axios.post(this.routes.send_mail, data)
      .then((response) => {
        let message = _this.email_action === "reply_email" ? "Reply Sent" : "Email Sent";
        _this.$notification.success(message, {  timer: 5 });
        this.close_overlay();
      }).catch(error => {
        console.log(error);
        _this.$notification.error("something went wrong", {  timer: 5 });
      });
    },

    getEmails(){
      this.autocompleteItems = []
      const _this = this
      axios.get(this.routes.getEmails,
      {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
      }).then(function(response){

        const emails = response.data.emails;
        var results = []
        
        // for(var i=0; i<emails.length; i++) {
        //   for(key in emails[i]) {
        //     if(emails[i][key].indexOf(_this.email_address_tag)!=-1) {
        //       results.push(emails[i]);
        //     }
        //   }
        // }

        var address = Object.values(emails)

        console.log(address)

        results = address.filter(element => element.includes(_this.email_address_tag))
        _this.autocompleteItems = []
        for (let x in results) {
          _this.autocompleteItems.push({text:results[x]})
        }

      }).catch(error => {
        console.log(error);
        this.$notification.error("Something went wrong", {  timer: 5 });
      })
    }
  }
});
</script>
