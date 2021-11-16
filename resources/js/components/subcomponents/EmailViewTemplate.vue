<template>
<div id="email_html_body" class="p-5" :start="start" :email_rowData="email_rowData">
  <div v-if="email_clicked">
    <div class="flex pb-4 items-center">
      <p class="font-bold text-xl">
        {{ email_rowData.message }}
      </p>
      <div @click="importantEmail" class="object-none content-center m-2 cursor-pointer">
        <img :src="[is_important ? '/images/label_important_yellow_20dp.png' : '/images/label_important_black_20dp.png']" alt="important icon">
      </div>
      
      <p v-for="label_id in email_rowData.labels" :key="label_id">
        <span v-if="user_labels_keyed[label_id]" class="px-2 py-1 mr-2 font-medium text-xs" :style="{ 'color': user_labels_keyed[label_id].color.textColor, 'background-color': user_labels_keyed[label_id].color.backgroundColor}">
          {{ user_labels_keyed[label_id].name }}
        </span>
        <span v-else-if="!label_id.includes('CATEGORY') && !user_labels_keyed[label_id] && label_id !== 'UNREAD' && label_id !== 'STARRED' && label_id !== 'IMPORTANT'" class="px-2 py-1 mr-2 bg-gray-300 font-medium text-xs">
          {{ label_id }}
        </span>
      </p>

      <ejs-button ref="" v-if="in_inbox" @click.native="emailView" iconCss="far fa-window-restore" cssClass="e-round shadow-none" class="ml-auto"></ejs-button>
    </div>
    
    <div class="flex">
      <div>
        <p class="font-bold text-base">
        {{ email_rowData.sender_info.name }} 
          <span class="text-gray-500 text-sm font-light">{{ email_rowData.sender_info.email ? "&lt;" + email_rowData.sender_info.email + "&gt;" : "" }}</span>
        </p>
        <p>
          {{ email_rowData.receiver === user_email ? "to me" : "to " + email_rowData.receiver }}{{ email_rowData.cc_info ? ", " + email_rowData.cc_info : ""}}
          <ejs-button ref="btn_email_data" @click.native="btnViewEmailData" iconCss="fas fa-caret-down" cssClass="e-round shadow-none" class=""></ejs-button>
        </p>
        
      </div>

      <div class="ml-auto" v-if="email_data">
        <p>
          {{ email_date_display }}
          <span>({{ time_duration }})</span>
          <ejs-button ref="" @click.native="starEmail" :iconCss="[is_starred ? 'fas fa-star text-yellow-500' : 'far fa-star']" cssClass="e-round shadow-none" class=""></ejs-button>
          <ejs-button ref="" @click.native="replyEmail" iconCss="fas fa-reply" cssClass="e-round shadow-none" class=""></ejs-button>
          <ejs-dropdownbutton :items="emailOpts" :select="moreEmailOpts" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
        </p>
      </div>
    </div>
   
    <div class="divide-y divide-gray-500 mt-5 pt-3">

      <iframe class="w-full" :srcdoc="email_body_html" frameborder="0" onload='javascript:(function(o){o.style.height=o.contentWindow.document.body.scrollHeight+20+"px";}(this));' style="display:block;width:100%;border:none;overflow:hidden;min-height:50px;">
        Your browser is not compatible!
      </iframe>

      <div v-if="email_attachments !== null" class="mt-8 pt-3">
        <div v-if="email_attachments.length > 1" class="flex"> 
          <b>{{ email_attachments.length}} Attachments</b>

          <ejs-tooltip content="Download all attachments" position="BottomCenter" class="ml-auto">
            <div @click="downloadAllAttachments" class="flex bg-white p-2 border rounded-lg cursor-pointer">
              <i class="fas fa-download"></i>
            </div>
          </ejs-tooltip>
        </div>

        <div v-for="(file, index) in email_attachments" :key="index" @click="attachmentClicked($event, file.name)" class="flex bg-white mb-4 border rounded-lg w-48 h-10 items-center truncate cursor-pointer">
          <p class="font-bold px-3 py-5 w-40">{{ file.name }}</p>
        </div>
      </div>
    </div>
  </div>
  
  <div v-if="email_body_html && !show_reply" id="email_actions" class="flex mt-10 pt-2">
    <div class="m-2">
      <div @click="replyEmail" class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
        <p class="flex font-bold px-3 py-5 w-40 items-center justify-center text-sm"><i class="fas fa-reply pr-2"></i> Reply</p>
      </div>
    </div>
    <div class="m-2">
      <div @click="forwardEmail" class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
        <p class="flex font-bold px-3 py-5 w-40 items-center justify-center text-sm"><i class="fas fa-arrow-right pr-2"></i> Forward</p>
      </div>
    </div>
  </div>

  <div v-if="show_reply" id="email_action_compose" class="grid grid-cols-4 gap-3 mt-6 pt-3 pb-3">
    <div :class="[ show_attachment ? 'col-span-3' : 'col-span-4']">
      <div class="w-full">
        <div class="flex w-full">
          <div class="w-full">
            <div class="flex items-center w-full">
              <ejs-dropdownbutton :items="items" :select="select_opts" cssClass="flex items-center justify-items-center">To:</ejs-dropdownbutton>
              <vue-tags-input
                class="tags-no-broders"
                v-model="email_address_tag"
                ref="vue_tags_address"
                :tags="email_address_tags"
                :add-on-key="[13, 32]"
                :save-on-key="[13, 32]"
                :allowEditTags="true"
                placeholder=""
                @tags-changed="email_address_tags_new"
                @before-adding-tag="email_address_tags_add_class"
                @before-saving-tag="email_address_tags_edit"
              />
            </div>

            <div v-if="change_subj" class="flex px-2 items-center justify-items-center border-b border-gray-300">
              <a class="text-sm text-gray-500">Subject:</a>
              <div class="p-2 w-full">
                <input type="text" class="focus:outline-none w-full" v-model="email_subject">
              </div>
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
                @before-adding-tag="cc_email_address_tags_add_class"
                @before-saving-tag="cc_email_address_tags_edit"
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
                @before-adding-tag="bcc_email_address_tags_add_class"
                @before-saving-tag="bcc_email_address_tags_edit"
                @tags-changed="bcc_address_tags_new"
              />
            </div>
          </div>

          <div class="ml-auto flex text-gray-500 font-bold">
            <p v-if="!add_cc" @click="add_cc = !add_cc" class="mr-3 cursor-pointer">Cc</p>
            <p v-if="!add_bcc" @click="add_bcc = !add_bcc" class="cursor-pointer">Bcc</p>
          </div>
        </div>

        <Vueditor ref="vueditor" class="w-full"></Vueditor>

        <div class="flex w-full mt-3">
          <ejs-button @click.native="sendReply" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
          <ejs-button @click.native="show_attachment = !show_attachment"><i class="fas fa-paperclip"></i></ejs-button>
          <ejs-button @click.native="trash_reply" class="ml-auto bg-red-600 text-white"><i class="fas fa-trash"></i></ejs-button>
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
import Vue from "vue";
import VueRouter from 'vue-router'
import moment from "moment";
import VueNotification from "@kugatsu/vuenotification";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { TooltipPlugin } from "@syncfusion/ej2-vue-popups";


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

// const emailFullView = Vue.component("email-full-view", require("../EmailFullView.vue").default);
Vue.use(TooltipPlugin);
Vue.use(ButtonPlugin);
// Vue.use(WindowPortal);
Vue.use(VueNotification, {
  timer: 20
});

export default Vue.extend({
  name: "EmailViewTemplate",
  components:{
    
  },
  data(){
    return{
      open: false,
      routes: null,
      csrf_token: null,
      user_email: null,
      headers: null,
      in_inbox: true,
      is_important: false,
      is_starred: false,
      email_action: null,
      email_subject: null,
      change_subj: false,
      show_reply: false,
      show_attachment: false,
      reply_address: null,
      reply_content: null,
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
      time_duration: null,
      emailOpts:[
        { id: 0, text: "Reply" },
        { id: 1, text: "Forward" },
        { id: 2, text: "Delete this message" },
        { id: 3, text: "Mark as unread" }
      ],
      items:[
        { text: "Reply",  iconCss: "fas fa-reply" },
        { text: "Forward", iconCss: "fas fa-arrow-right" },
        { text: "Edit Subject" },
        { text: "Pop out reply" },
      ]
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

    ref_headerTemplate(){
      return this.$store.state.headerTemplate;
    },

    ref_inboxDisplay(){
      return this.$store.state.inboxDisplay;
    },

    ref_sidebar(){
      return this.$store.state.sidebar;
    },

    ref_emailView_full(){
      return this.$store.state.emailView_full;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
    },

    email_body_html:{
      get(){
        return this.$store.state.selected_email_html_body;
      },
      set(new_data){
        return this.$store.dispatch('set_email_html_body', new_data);
      }
      
    },

    email_batch:{
      get(){
        return this.$store.state.email_batch;
      },
      set(new_batch){
        return this.$store.dispatch('set_email_batch', new_batch);
      }
    },

    email_data:{
      get(){
        this.show_reply = false;
        this.email_action = null;
        let data = this.$store.state.selected_email_data

        if(data !== null){
          this.email_subject = data.subject;
          this.email_date_display = formatDate(data.date);
          this.time_duration = moment(data.date, "YYYYMMDDhhmmss").fromNow();
        }

        return data;
      },
      set(new_data){
        return this.$store.dispatch('set_email_data', new_data);
      },
    },

    email_clicked() {
      return this.$store.state.email_clicked
    },

    email_rowData(){
      if(this.$store.state.selected_email_rowData){
        this.is_important = this.$store.state.selected_email_rowData.important;
        this.is_starred = this.$store.state.selected_email_rowData.starred;
      }

      return this.$store.state.selected_email_rowData;
    },

    email_attachments(){
      return this.$store.state.selected_email_attachments;
    },

    dropdown_btn_email_data(){
      return this.$store.state.dropdown_btn_email_data;
    },

    user_labels_keyed(){
      return this.$store.state.user_labels_keyed;
    },

    viewEmailFull:{
      get(){
        return this.$store.state.viewEmailFull;
      },
      set(new_data){
        return this.$store.dispatch("set_viewEmailFull", new_data);
      }
    }
  },

  methods:{
    scrollToEnd(){    	
      if(this.in_inbox){
        var container = this.ref_sidebar.$el.querySelector("#splitter .pane_1");
        container.scrollTop = container.scrollHeight;
      }else{
        window.scrollTo(0,document.body.scrollHeight);
      }
    },

    attachmentClicked(event, file){
      console.log(file);

      const url = this.routes.download_attachment + "?file=" + file;
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', file);
      document.body.appendChild(link);
      link.click()
    },

    downloadAllAttachments(event){
      let files = [];
      this.email_attachments.forEach(file => {
        files.push(file.name)
      });

      let arrStr = encodeURIComponent(JSON.stringify(files));
      console.log(arrStr);
      let zip_name = (this.email_data.subject.replace(/[^A-Z0-9]/ig, "")).toLowerCase();
      const url = this.routes.download_attachment + "?all=true&zip_name=" + zip_name + "&file=" + arrStr;
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', zip_name + ".zip");
      document.body.appendChild(link);
      link.click()
    },

    replyEmail(){
      let _this = this;
      this.show_reply = true;
      this.email_action = "reply_email";
      this.add_cc = false;
      this.add_bcc = false;
      let data = this.email_data;
      let from_email = this.email_data.from.email !== null ? this.email_data.from.email : this.email_data.from.name;
      this.change_subj = false;

      if(data !== null){

        if(this.current_inbox.name === "SENT"){
          this.reply_address = data.to.email ? data.to.email : data.to.name;
          this.email_addresses = [this.reply_address];

          this.email_address_tags = [{
            text: this.reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];
        }else{
          this.reply_address = data.from.email ? data.from.email : data.from.name;
          this.email_addresses = [this.reply_address];

          this.email_address_tags = [{
            text: this.reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];
        }

      }

      this.reply_content = "<div><br></div><div><br></div>" +
        "<div class='gmail_quote'>" +
          "<div dir='ltr'>On " + moment().format("LLLL") + " <<a href='mailto:" + from_email +"'>" + from_email + "</a>> wrote:</div>" +
          "<blockquote  style='margin: 0px 0px 0px 0.8ex;border-left: 1px solid rgb(204, 204, 204);padding-left: 1ex;'>" +
            this.email_body_html +
          "</blockquote>" +  
        "</div>";
      
      setTimeout(()=>{
        this.$refs.vueditor.setContent(this.reply_content);
      }, 200);

      setTimeout(function() {
        _this.scrollToEnd();
      }, 0);
      
    },

    forwardEmail(){
      let _this = this;
      this.show_reply = true;
      this.email_action = "forward_email";
      this.add_cc = false;
      this.add_bcc = false;
      this.email_address_tags = [];
      this.email_addresses = null;
      let from_email = this.email_data.from.email !== null ? this.email_data.from.email : this.email_data.from.name;
      let to_email = this.email_data.to.email ? this.email_data.to.email : this.email_data.to.name;
      this.change_subj = false;
      this.reply_address = null;

      this.reply_content = "<div><br></div><div><br></div>" +
        "<div dir='ltr'>On " + moment().format("LLLL") + " <<a href='mailto:" + this.user_email +"'>" + this.user_email + "</a>> wrote:</div>" +
        "<div class='gmail_quote'>" +
        "<blockquote  style='margin: 0px 0px 0px 0.8ex;border-left: 1px solid rgb(204, 204, 204);padding-left: 1ex;'>" +
        "<div>---------- Forwarded message ---------</div>" +
        "<div>From: &lt;<a href='mailto:" + from_email +"'>" + from_email + "</a>&gt;</div>" +
        "<div>Date: " + moment(this.email_data.date).format("llll") + "</div>" +
        "<div>Subject: " + this.email_data.subject + "</div>" +
        "<div>To: &lt;<a href='mailto:" + to_email +"'>" + to_email + "</a>&gt;</div>"

      if(this.email_data.cc !== null){
        let cc_email = this.email_data.cc;
        cc_email = cc_email.replace(/"/g, "");
        cc_email = cc_email.replace(/</g, "&lt;");
        cc_email = cc_email.replace(/>/g, "&gt;");

        this.reply_content = this.reply_content + "<div>Cc: " + cc_email + "</div>"
      }

      this.reply_content = this.reply_content + "<div><br></div><div><br></div>" + this.email_body_html +"</blockquote></div>";
      setTimeout(() => this.$refs.vueditor.setContent(this.reply_content), 200);
      
      setTimeout(function() {
        _this.scrollToEnd();
      }, 0);
    },

    trash_reply(){
      this.$refs.vueditor.setContent("");
      this.reply_content = null;
      this.change_subj = false;
      this.email_subject = null;
      this.show_reply = false;
    },

    email_address_tags_add_class(args){
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
        if(this.email_addresses == null){
              this.email_addresses = [this.email_address_tag];
        }else{
              this.email_addresses.push(this.email_address_tag);
        }
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

    cc_email_address_tags_add_class(args){
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
        if(this.cc_addresses == null){
              this.cc_addresses = [this.cc_address_tag];
        }else{
              this.cc_addresses.push(this.cc_address_tag);
        }
      }else{
        args.tag.classes = "bg-red-500 rounded-full px-3 justify-center items-center";
      }

      args.addTag();
    },

    cc_email_address_tags_edit(args){
      console.log('edit');
       console.log(args)
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
      }else{
        args.tag.classes = "bg-red-500 rounded-full px-3 justify-center items-center";
      }

      args.saveTag();
    },

    bcc_email_address_tags_add_class(args){
       console.log('add');
      console.log(args);
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
        if(this.bcc_addresses == null){
              this.bcc_addresses = [this.bcc_address_tag];
        }else{
              this.bcc_addresses.push(this.bcc_address_tag);
        }
      }else{
        args.tag.classes = "bg-red-500 rounded-full px-3 justify-center items-center";
      }

      args.addTag();
    },

    bcc_email_address_tags_edit(args){
      if(validateEmail(args.tag.text)){
        args.tag.classes = "bg-pink-500 rounded-full px-3 justify-center items-center";
      }else{
        args.tag.classes = "bg-red-500 rounded-full px-3 justify-center items-center";
      }

      args.saveTag();
    },

    email_address_tags_new(){
      if(this.email_address_tag != '' && validateEmail(this.email_address_tag)){
        if(this.email_addresses == null){
              this.email_addresses = [this.email_address_tag];
        }else{
              this.email_addresses.push(this.email_address_tag);
        }
      }
    },

    cc_address_tags_new(){
      if(this.cc_address_tag != '' && validateEmail(this.cc_address_tag)){
        if(this.cc_addresses == null){
              this.cc_addresses = [this.cc_address_tag];
        }else{
              this.cc_addresses.push(this.cc_address_tag);
        }
      }
    },

    bcc_address_tags_new(){
      if(this.bcc_address_tag != '' && validateEmail(this.bcc_address_tag)){
        if(this.bcc_addresses == null){
              this.bcc_addresses = [this.bcc_address_tag];
        }else{
              this.bcc_addresses.push(this.bcc_address_tag);
        }
      }
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

      this.email_address_tags_new();
      this.cc_address_tags_new();
      this.bcc_address_tags_new();

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
        option: this.email_action,
        email_id: this.email_data.email_id,
        addresses: this.email_addresses,
        cc: this.cc_addresses,
        bcc: this.bcc_addresses,
        message: this.$refs.vueditor.getContent(),
        attachments: attachments,
        change_subj: this.change_subj,
        subject: this.email_subject,
      }

      axios.post(this.routes.send_mail, data)
          .then((response) => {
            let message = "";
            if(_this.change_subj){
              message = "Email Sent";
            }else{
              message = _this.email_action === "reply_email" ? "Reply Sent" : "Email Forwarded";
            }
            _this.$notification.success(message, {  timer: 5 });
            this.show_reply = false;
            this.change_subj = false;
          }).catch(error => {
            console.log(error);
            _this.$notification.error("somthing went wrong", {  timer: 5 });
          });
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

    btnViewEmailData(){
      let _this = this;

      this.$eventHub.$emit("show_custom_dropdown", {
        button: "btn_email_data",
        top: this.$refs.btn_email_data.$el.getBoundingClientRect().top,
        left: this.$refs.btn_email_data.$el.getBoundingClientRect().left
      });

      setTimeout(function() {
        _this.$store.dispatch("dropdown_btn_email_data_toggle", !this.dropdown_btn_email_data);
      }, 0);
    },

    starEmail(){
      let _this = this;
      let params = {
        column: "starred",
        id: this.email_data.email_id,
        value: !this.is_starred
      }
      
      this.$store.dispatch("data_toggle", params).then((response) => {
        _this.is_starred = !this.is_starred;
        _this.email_batch[_this.email_rowData.index].starred = _this.is_starred;
        _this.ref_inboxDisplay.soft_refresh();
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    importantEmail(){
      let _this = this;
      let params = {
        column: "important",
        id: this.email_data.email_id,
        value: !this.is_important
      };
      
      this.$store.dispatch("data_toggle", params).then((response) => {
        _this.is_important = !this.is_important;
        _this.email_batch[_this.email_rowData.index].important = _this.is_important;
        _this.ref_inboxDisplay.soft_refresh();
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    moreEmailOpts(args){
      let _this = this;
      switch (args.item.id) {
        case 0:
          //Reply
          this.replyEmail();
          break;

        case 1:
          //Forward
          this.forwardEmail();
          break;

        case 2:
          //Delete this message
          
          let params = {
            option: 9,
            dataIDs: [this.email_data.email_id],
            current_inbox_id: this.current_inbox.id
          };

          this.$store.dispatch("data_toggle_many", params).then((response) => {
            _this.email_data = null;
            _this.email_body_html = null;
            _this.ref_headerTemplate.refreshInbox();
          }).catch(error => {
            console.log(error);
            _this.$notification.error("somthing went wrong", {  timer: 5 });
          });
          break;

        case 3:
          //Mark as unread
          let params_ = {
            column: "read",
            id: this.email_data.email_id,
            value: false
          };
          
          this.$store.dispatch("data_toggle", params_).then((response) => {
            _this.email_batch[_this.email_rowData.index].read = false;
            _this.email_data = null;
            _this.email_body_html = null;
            _this.ref_inboxDisplay.soft_refresh();
          }).catch(error => {
            console.log(error);
            _this.$notification.error("somthing went wrong", {  timer: 5 });
          });
          break;
      
        default:
          this.$notification.error("somthing went wrong", {  timer: 5 });
          break;
      }
    },

    emailView(){
      this.viewEmailFull = true;
    },


    select_opts(args){
      console.log(args);
      switch (args.item.text) {
        case "Reply":
          this.replyEmail();
          break;

        case "Forward":
          this.forwardEmail();
          break;
        
        case "Edit Subject":
          this.change_subj = true;
          break;

        case "Pop out reply":
          let _this = this;
          let reply_content = this.reply_content;
          let email_subject = this.email_subject;
          this.ref_sidebar.composeNew();
          this.$refs.vueditor.setContent("");
          this.change_subj = false;
          this.email_subject = null;
          this.reply_content = null;
          this.show_reply = false;
          
          setTimeout(function() {
            console.log(_this.ref_sidebar.$refs.overlay_comps);
            let overlay = _this.ref_sidebar.$refs.overlay_comps[0];

            overlay.email_subject = email_subject;
            // overlay.$refs.vueditor_cont.setContent(_this.reply_content);
            setTimeout(() => overlay.$refs.vueditor_cont.setContent(reply_content), 100);
            if(_this.reply_address){

              overlay.email_addresses = [_this.reply_address];
              
              overlay.email_address_tags = [{
                text: _this.reply_address,
                classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
              }];
            }
            
            
          }, 0);
          
          break;
      
        default:
          console.error("somthing went wrong: drp_down select");
          break;
      }
    }
  }
});
</script>
