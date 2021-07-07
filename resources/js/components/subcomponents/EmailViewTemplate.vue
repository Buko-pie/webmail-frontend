<template>
<div id="email_html_body" class="p-5" :start="start">
  <div v-if="email_data" class="flex">
    <div>
      <p class="font-bold text-base">{{ email_data.from.name }}</p>
      <p class="text-gray-500 text-sm"> {{ email_data.from.email }} </p>
    </div>

    <div class="ml-auto">
      {{ email_data.date }}
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
      <div class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
        <p class="flex font-bold px-3 py-5 w-40 items-center justify-center"><i class="fas fa-arrow-right pr-2"></i> Forward</p>
      </div>
    </div>
  </div>

  <div v-if="show_reply" id="email_actions" class="grid grid-cols-4 gap-3 mt-10 pt-3">
    <div :class="[ show_attachment ? 'col-span-3' : 'col-span-4']">
      <div class="w-full">
        <a class="text-sm text-gray-500 mb-5">To: {{ reply_address }}</a>
        <Vueditor ref="reply_content" class="w-full"></Vueditor>
      </div>
    </div>
    <div v-if="show_attachment" class="col-span-1">
      <div class="border w-full p-3 mt-5">
        <p class="text-base font-bold"><i class="fas fa-paperclip"></i> Attchments</p>
      </div>
      <ejs-uploader 
        ref="ejs_uploader_reply"
        id='ejs_uploader_reply'
        name="UploadFiles"
        :asyncSettings="attachment_path"
        :uploading="attachmentUpload"
        :removing="removingAttachment"
        :sequentialUpload='true'
        :autoUpload='true' >
      </ejs-uploader>
    </div>
    <div class="border flex mt-3" :class="[ show_attachment ? 'col-span-3' : 'col-span-4']">
      <ejs-button @click.native="sendReply" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
      <ejs-button @click.native="show_attachment = !show_attachment"><i class="fas fa-paperclip"></i></ejs-button>
      <ejs-button @click.native="replyEmail" class="ml-auto bg-red-600 text-white"><i class="fas fa-trash"></i></ejs-button>
    </div>
  </div>
</div>
</template>

<script>
import moment from "moment";

function formatDate(date) {
  let new_date = moment(date).format("LLL");


  // if(moment(date).isSame(new Date(), "day")){
  //   new_date = moment(value.created_at).format("LT");
  // }else if(moment(value.created_at).isSame(new Date(), "year")){
  //   new_date = moment(value.created_at).format("MMM DD");
  // }else{
  //   new_date = moment(value.created_at).format("YYYY/MM/DD");
  // }

  return new_date;
}

export default({
  name: "EmailViewTemplate",
  data(){
    return{
      routes: null,
      csrf_token: null,
      headers: null,
      show_reply: false,
      show_attachment: false,
      reply_address: null,
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
      this.headers = {
        "Content-Type": "application/json",
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
      let data = this.$store.state.selected_email_data;

      if(data !== null){
        data.date = formatDate(data.date);
        if(this.current_inbox === "sent"){
          this.reply_address = data.to.email ? data.to.email : data.to.name;
        }else{
          this.reply_address = data.from.email ? data.from.email : data.from.name;
        }
        
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
    },

    sendReply(){
      console.log('send reply...');

      axios({
        method: "POST",
        url: this.routes.send_mail,
        headers: this.headers,
        params: {
          option: "reply_email",
          email_id: this.email_data.email_id,
          addresses: this.reply_address,
          message: this.$refs.reply_content.getContent()
        }
      }).then(function (response) {
        console.log(response.data);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
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
  }
});
</script>
