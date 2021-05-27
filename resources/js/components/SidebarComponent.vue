<template>
<div class="control-section sidebar-default">
    <!-- New label modal  -->
    <modal name="new_label_modal" @before-open="modalOpened" @before-close="modalClosed">
      <div class="p-5 h-full relative">
        <h3 class="text-2xl font-semibold">New Label</h3>
        <br>
        <p class="text-gray-400">{{ new_lbl_txt }}</p>
        <div class="e-input-group" :class="{ 'e-input-focus': new_lbl_input_is_focused }">
          <input id="new_label_name_input" ref="new_label_name_input" v-model="new_lbl_name" @focus="new_lbl_input_is_focused = true" @blur="new_lbl_input_is_focused = false" class="e-input e-textbox" type="text" placeholder="(ex. Appointments)">
        </div>

        <div class="absolute bottom-5 right-5">
          <ejs-button v-on:click.native="modalHide" class="mr-3">Cancel</ejs-button>
          <ejs-button v-on:click.native="createNewLabel" :isPrimary="true">Create</ejs-button>
        </div>
      </div>
    </modal>
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

            <a @click="category_toggle = !category_toggle" class="sidebar_items w-full" href="#">
              <div class="sidebar_icons">
                <i class="fas fa-tag text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Categories</p>
              <div class="sidebar_icons_rightmost">
                <i v-show="toggled" :class="category_toggle ? 'fas fa-chevron-up text-base' : 'fas fa-chevron-down text-base'"></i>
              </div>
            </a>
            
            <div v-show="category_toggle">
              <a class="sidebar_items" :class="{ 'pl-6' : toggled }" href="#">
                <div class="sidebar_icons">
                  <i class="fas fa-users text-lg"></i>
                </div>
                <p class="sidebar_text" v-show="toggled">Social</p>
              </a>
              
              <a class="sidebar_items" :class="{ 'pl-6' : toggled }" href="#">
                <div class="sidebar_icons">
                  <i class="fas fa-exclamation text-lg"></i>
                </div>
                <p class="sidebar_text" v-show="toggled">Updates</p>
              </a>
              
              <a class="sidebar_items" :class="{ 'pl-6' : toggled }" href="#">
                <div class="sidebar_icons">
                  <i class="fas fa-comments text-lg"></i>
                </div>
                <p class="sidebar_text" v-show="toggled">Forums</p>
              </a>
              
              <a class="sidebar_items" :class="{ 'pl-6' : toggled }" href="#">
                <div class="sidebar_icons">
                  <i class="fas fa-bullhorn text-lg"></i>
                </div>
                <p class="sidebar_text" v-show="toggled">Promotions</p>
              </a>
            </div>
 
            <!-- Custom labels -->
            <div ref="sidebar_custom_labels">
              <a v-for="labels in custom_labels" :key="labels.id" class="sidebar_items" href="#">
                <div class="sidebar_icons">
                  <i class="fas fa-sticky-note text-lg"></i>
                </div>
                <p class="sidebar_text" v-show="toggled">{{ labels.title }}</p>
              </a>
            </div>

            <a @click="modalShow" class="sidebar_items" href="#">
              <div class="sidebar_icons">
                <i class="fas fa-plus text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">Add new label</p>
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
import VModal from 'vue-js-modal';

import { SidebarPlugin } from '@syncfusion/ej2-vue-navigations';
import { ButtonPlugin , RadioButtonPlugin } from '@syncfusion/ej2-vue-buttons';
import { enableRipple } from '@syncfusion/ej2-base';

enableRipple(true);

Vue.use(VModal, { dialog: true });
Vue.use(SidebarPlugin, ButtonPlugin, RadioButtonPlugin);

const grid = Vue.component("vue-grid-component", require("./ExampleComponent.vue").default);
const new_label_modal = Vue.component("new_label_modal", require("./modalcomponents/NewLabelComponent").default);

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

function isExistLabel(new_label, custom_labels){
  for (let i = 0; i < custom_labels.length; i++) {
    console.log(custom_labels)
    if(custom_labels[i].title === new_label){
      return true;
    }
  }

  return false;
}

export default Vue.extend({
  name: "SidebarComponent",
  props:{
    routes: { type: Object, required: true }
  },
  data: function() {
    return {
      enableDock:  true,
      dockSize : "72px",
      width : "16rem",
      position : "Left",
      toggled: true,
      new_lbl_name: null,
      toggled_sidebar_before_modal_open: false,
      new_lbl_input_is_focused: false,
      category_toggle: false,
      new_lbl_txt: "Please enter new label name:",
      custom_labels:[
        {id: 0, title: "test_label"},
        {id: 1, title: "test_label_2"}
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
    modalShow(){
      this.$modal.show('new_label_modal',{
        buttons:[
          {
            title: "Cancel",
            handler:() => {
              this.$modal.hide("new_label_modal")
            }
          }
        ]
      });
    },

    modalHide(){
      this.$modal.hide('new_label_modal');
    },

    modalOpened(){
      if(this.toggled){
        this.$refs.dockSidebar.toggle();
        this.toggled = !this.toggled;
      }
    },
    
    modalClosed(){
      if(this.toggled_sidebar_before_modal_open === false){
        this.$refs.dockSidebar.toggle();
        this.toggled = !this.toggled;
      }
    },

    toggleClick() {
      this.$refs.dockSidebar.toggle();
      this.toggled = !this.toggled;
      this.toggled_sidebar_before_modal_open = !this.toggled_sidebar_before_modal_open;
    },

    closeClick() {
      this.$refs.dockSidebar.hide();
    },

    openClick:function() {
      this.$refs.dockSidebar.show();
    },

    createNewLabel(){
      if(this.new_lbl_name){
        if(isExistLabel(this.new_lbl_name, this.custom_labels)){
          this.new_lbl_txt = "The label name you have chosen already exists. Please try another name:"
        }else{
          console.log(this.new_lbl_name);
          this.custom_labels.push({id: (this.custom_labels.length), title: this.new_lbl_name});
          this.new_lbl_name = null;
          this.$modal.hide('new_label_modal');
        }
      }else{
        this.new_lbl_txt = "No name specified. Please try another name:"
      }
      
    },

    getInbox(event){
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
        let data = response.data.dummy_data;
        _this.$refs.data_grid.viewData = formatDate(data);
        console.log(response.data.dummy_data);
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    },

    starredOnly(event) {
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
    importantOnly(event) {
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