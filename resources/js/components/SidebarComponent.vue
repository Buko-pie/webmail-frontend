<template>
<div class="control-section sidebar-default">
    <!-- sample level element  -->
    
    <!-- sample level element  -->
    <div id="wrapper">
      <div class="col-lg-12 col-sm-12 col-md-12">
        <!-- Sidebar element declaration -->
        <ejs-sidebar id="dockSidebar" ref="dockSidebar" class="mt-16 bg-white text-gray-700" :enableDock='enableDock' :position="position" :width='width' :dockSize='dockSize'>
          <div class="compose_btn_container">
            <button class="compose_btn shadow-black pill">
              <i class="fas fa-plus-circle text-lg"></i>
              <span class="compose_text" v-show="toggled">Compose</span>
            </button>
          </div>
          <div id="sidebar_list" ref="sidebar_list" class="sidebar-list">
            <a @click="getInbox" class="sidebar_items_selected" href="#">
              <div class="sidebar_icons">
                <i class="fas fa-inbox text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Inbox</p>
            </a>
            <a @click="starredOnly" class="sidebar_items" href="#">
              <div class="sidebar_icons">
                <i class="far fa-star text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Starred</p>
            </a>
            <a @click="importantOnly" class="sidebar_items" href="#">
              <div class="sidebar_icons">
                <i class="fas fa-thumbtack text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Important</p>
            </a>
            <a class="sidebar_items" href="#">
              <div class="sidebar_icons">
                <i class="far fa-paper-plane text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Sent</p>
            </a>
            <a class="sidebar_items" href="#">
              <div class="sidebar_icons">
                <i class="far fa-file text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Drafts</p>
            </a>
            <a class="sidebar_items" href="#">
              <div class="sidebar_icons">
                <i class="fas fa-tag text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Categories</p>
            </a>
          </div>
      </ejs-sidebar>
      <!-- end of sidebar element -->
      <!-- main content declaration -->
      <div>
          <vue-grid-component ref="data_grid" :custom_labels="custom_labels"/>
      </div>
    </div> 
  </div>
</div>
</template>

<script>
import Vue from "vue";
import { SidebarPlugin } from '@syncfusion/ej2-vue-navigations';
import { ButtonPlugin , RadioButtonPlugin } from '@syncfusion/ej2-vue-buttons';
import { enableRipple } from '@syncfusion/ej2-base';

Vue.use(SidebarPlugin, ButtonPlugin, RadioButtonPlugin);
const grid = Vue.component("vue-grid-component", require("./ExampleComponent.vue").default);

export default Vue.extend({
  data: function() {
    return {
      enableDock:  true,
      dockSize : '72px',
      width : '16rem',
      position :'Left',
      toggled: true,
      custom_labels:[
        {id: 0, title: "test_label"},
        {id: 1, title: "bruh_label"}
      ]
    }
  },
  components:{
    grid
  },
  mounted(){
    
    if(this.custom_labels.length > 0){
      this.custom_labels.forEach(function(){
        //add custome labels to sidebar here
      });
      console.log(this.custom_labels);
    }
  },
  methods: {
    toggleClick :function() {
      this.$refs.dockSidebar.toggle();
      this.toggled = !this.toggled;
    },
    closeClick: function() {
      this.$refs.dockSidebar.hide();
    },
    openClick:function() {
      this.$refs.dockSidebar.show();
    },
    getInbox:function(event){
      this.$refs.data_grid.viewData = this.$refs.data_grid.localData;
    },
    starredOnly:function(event) {
      console.log("starred Only");
      let email_list = this.$refs.data_grid.localData;
      let temp = [];

      email_list.forEach(function(value, index, array){
        if(value.starred){
          temp.push(value);
        }
      });

      this.$refs.data_grid.viewData = temp;
    },
    importantOnly:function(event) {
      console.log("important Only");
      let email_list = this.$refs.data_grid.localData;
      let temp = [];

      email_list.forEach(function(value, index, array){
        if(value.important){
          temp.push(value);
        }
      });

      this.$refs.data_grid.viewData = temp;
    }
  }
});
</script>


<style>

</style>