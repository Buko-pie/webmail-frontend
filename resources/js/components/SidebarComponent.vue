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
          <vue-grid-component ref="data_grid" :custom_labels="custom_labels" :routes="routes"/>
      </div>
    </div> 
  </div>
</div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import { SidebarPlugin } from '@syncfusion/ej2-vue-navigations';
import { ButtonPlugin , RadioButtonPlugin } from '@syncfusion/ej2-vue-buttons';
import { enableRipple } from '@syncfusion/ej2-base';

Vue.use(SidebarPlugin, ButtonPlugin, RadioButtonPlugin);
const grid = Vue.component("vue-grid-component", require("./ExampleComponent.vue").default);

function formatDate(data) {
  data.forEach(function(value) {
    if(moment(value.created_at).isSame(new Date(), "day")){
      value.created_at = moment(value.created_at).format("LT");
    }else if(moment(value.created_at).isSame(new Date(), "year")){
      value.created_at = moment(value.created_at).format("MMM DD");
    }else{
      value.created_at = moment(value.created_at).format("YYYY/MM/DD");
    }
  });

  return data;
}

export default Vue.extend({
  props:{
    routes: { type: Object, required: true }
  },
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
    console.log(this.routes.data_route);
    console.log(typeof this.routes);
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
      console.log("get all");
      let _this = this;
      axios({
        method: "GET",
        url: this.routes.data_route,
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          token: "{{ csrf_token() }}",
          option: "get_all"
        }
      }).then(function (response) {
        _this.$refs.data_grid.viewData = response.data.dummy_data;
        console.log(response.data.dummy_data);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    },
    starredOnly:function(event) {
      console.log("starred Only");
      let _this = this;

      axios({
        method: "GET",
        url: this.routes.data_route,
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          token: "{{ csrf_token() }}",
          option: "starred_only"
        }
      }).then(function (response) {
        let data = response.data.dummy_data;
        _this.$refs.data_grid.viewData = formatDate(data);
        console.log(response.data.dummy_data);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    },
    importantOnly:function(event) {
      console.log("important Only");
      let _this = this;

      axios({
        method: "GET",
        url: this.routes.data_route,
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          token: "{{ csrf_token() }}",
          option: "important_only"
        }
      }).then(function (response) {
        let data = response.data.dummy_data;
        _this.$refs.data_grid.viewData = formatDate(data);
        console.log(response.data.dummy_data);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    }
  }
});
</script>


<style>

</style>