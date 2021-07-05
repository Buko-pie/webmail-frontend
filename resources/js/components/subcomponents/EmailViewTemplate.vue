<template>
<div id="email_html_body" class="p-5 " :start="start">
  <div class="divide-y divide-gray-500">
    <div v-html="email_body_html">
      {{ email_body_html }}
    </div>
    <div v-if="email_attachments !== null" class="mt-10 pt-3">

      <div v-for="(file, index) in email_attachments" :key="index" @click="attachmentClicked($event, file.name)" class="flex bg-white mb-4 border rounded-lg w-48 h-10 items-center truncate cursor-pointer">
        <p class="font-bold px-3 py-5 w-40">{{ file.name }}</p>
      </div>
    </div>
  </div>

  <div v-if="email_body_html" id="email_actions" class="flex mt-10 pt-3">
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

</div>
</template>

<script>
export default({
  name: "EmailViewTemplate",
  data(){
    return{
      routes: null,
      csrf_token: null,
    };
  },
  computed:{
    start(){
      console.log("email view computed")
      this.routes = this.$store.state.routes;
      this.csrf_token = this.$store.state.csrf_token;
    },

    email_body_html(){
      return this.$store.state.selected_email_html_body;
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
      link.click();

      // window.open(this.routes.download_attachment + "?file=" + file);

      // axios({
      //   method: "GET",
      //   url: this.routes.download_attachment,
      //   headers: {
      //     "Content-Type": "application/json",
      //     "Authorization": "Bearer " + this.csrf_token,
      //     "X-CSRF-TOKEN": this.csrf_token
      //   },
      //   params: {
      //     token: this.csrf_token,
      //     file: file
      //   }
      // }).then(function (response) {
      //   console.log(response);
        // const url = window.URL.createObjectURL(new Blob([response.data]));
        // const link = document.createElement('a');
        // link.href = url;
        // link.setAttribute('download', file);
        // document.body.appendChild(link);
        // link.click();


      // }).catch(error => {
      //   console.log(error);
      //   alert("somthing went wrong");
      // });
    },

    replyEmail(){
      console.log("replyEmail");
    }
  }
});
</script>
