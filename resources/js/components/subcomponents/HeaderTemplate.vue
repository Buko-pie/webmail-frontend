<template>
  <div ref="header_template" class="flex relative items-center h-14">
    <div e-mappinguid="grid-column0">
      <div class="e-checkbox-wrapper e-css z-40">
        <input class="e-checkselectall e-focus" type="checkbox">
        <span class="e-frame e-icons e-uncheck"></span>
        <span class="e-label"> </span>
      </div>
      <ejs-dropdownbutton :items="drop_down_items" :select="dropDownSelect" class="absolute left-5 pl-0 py-1 pr-1.5"></ejs-dropdownbutton>
    </div>
    <div class="pl-5">
      <ejs-progressbutton id="refresh_progress" ref="refresh_progress"
        :enableProgress="true"
        :begin="refreshInbox" 
        :progress="onProgress"
        :spinSettings="{position: 'Center'}" 
        :duration="loading_duration"
        :animationSettings="{ effect: 'ZoomOut' }"
        cssClass="e-round shadow-none" 
        iconCss="fas fa-redo-alt text-lg">
      </ejs-progressbutton>
    </div>
    <div class="pl-5">
      <button class="bg-white p-1 rounded-full hover:bg-gray-300 focus:outline-none">
        <span class="sr-only">View notifications</span>
        <i class="h-5 w-5 fas  fa-ellipsis-v text-lg"></i>
      </button>
    </div>
    
  </div>
</template>

<script>
import Vue from "vue";
import { DropDownButtonPlugin, ProgressButtonPlugin  } from "@syncfusion/ej2-vue-splitbuttons";

Vue.use(DropDownButtonPlugin);
Vue.use(ProgressButtonPlugin);

export default Vue.extend({
  data(){
    return{
      data:{},
      false: "false",
      true: "true",
      loading: true,
      loading_duration: 15000,
      drop_down_items:[
        {
          id: 0,
          text: 'All'
        },{
          id: 1,
          text: 'None'
        },{
          id: 2,
          text: 'Read'
        },{
          id: 3,
          text: 'Unread'
        },{
          id: 4,
          text: 'Starred'
        },{
          id: 5,
          text: 'Unstarred'
        }
      ]
    };
  },

  methods:{
    dropDownSelect(args){
      this.$eventHub.$emit("select_by", {
        id: args.item.properties.id, 
        text: args.item.properties.text
      });
    },

    onProgress(args){
      if(this.loading){
        args.percent = 0;
        this.loading_duration = this.loading_duration + 100;
      }else{
        args.percent = 100;
        this.loading = false;
      }
    },

    refreshInbox(){
      console.log("header refresh inbox");
      this.$eventHub.$emit("refresh_inbox", {
        event: "refresh_inbox"
      });
      this.loading = true;
    }
  },

  created(){
    this.$eventHub.$on("stop_loading", (e) => {
      console.log("stop_loading");
      this.loading = false;
    });
  }
});
</script>