<template>
<div class="control-section sidebar-default">
  <!-- run start computed -->
  <div :start="start"></div>
  <div class="control-section">
    <div class="sample-container">
      <div class="default-section">
        <ejs-richtexteditor 
          ref="rteObj"

          :toolbarSettings="toolbarSettings"
          :actionBegin="handleFullScreen"
          :actionComplete="actionCompleteHandler"
          :showCharCount="true"
          :maxLength="maxLength"
          :fileManagerSettings="fileManagerSettings"
          :quickToolbarSettings='quickToolbarSettings'
        >
        </ejs-richtexteditor>
      </div>
    </div>
  </div>
  
  <!-- Compose Modal  -->
  <modal name="compose_new_modal">
    <div class="p-2 h-full relative">
      <ejs-textbox ref="to" id="to" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="To"></ejs-textbox>
      <ejs-textbox ref="subject" id="subject" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Subject"></ejs-textbox>

      
    </div>
  </modal>
  
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

  <!-- Date picker modal -->
  <modal name="date_picker_modal" :adaptive="true">
    <div class="p-4 h-auto relative">
      <ejs-calendar id="calendar" ></ejs-calendar>
    </div>
  </modal>

  <!-- Upload Profile Picture modal  -->
  <modal name="new_profile_modal">
    <div class="p-5 h-full relative">
      <h3 class="text-2xl font-semibold">Select Profile Photo</h3>
      <br>
      <button class="btn btn-primary btn-sm" id="pick-avatar">Select an new image</button>

      <avatar-cropper
        trigger="#pick-avatar"
        :upload-headers="profile_upload_headers"
        :labels="{submit: 'Set as profile photo', cancel: 'Cancel'}"
        :upload-url="routes.upload_profile_pic"
        :withCredentials="true"
        :upload-form-data="{token: token}"
        @uploading="handleUploading"
        @uploaded="handleUploaded"
        @completed="handleCompleted"
        @error="handlerError">
      </avatar-cropper>
    </div>
  </modal>



  <!--
  <my-upload 
    field="profile_photo"
    @crop-success="cropSuccess"
    @crop-upload-success="cropUploadSuccess"
    @crop-upload-fail="cropUploadFail"
    :langType="'en'"
    :width="500"
    :height="500"
    :url="routes.upload_profile_pic"
    :params="upload_profile_params"
    :headers="profile_upload_headers"
    :withCredentials="true"
    :noSquare="true"
    :noRotate="false"
    v-model="show_upload_profile_pic"
    img-format="png">
  </my-upload>
    -->

    <!-- sample level element  -->
  <div id="wrapper">
    <div class="col-lg-12 col-sm-12 col-md-12">
      <!-- Sidebar element declaration -->
      <ejs-sidebar id="dockSidebar" ref="dockSidebar" class="mt-16 bg-white text-gray-700" :enableDock='enableDock' :position="position" :width='width' :dockSize='dockSize' :close="sidebarClose" :open="sidebarOpen">
        <div class="compose_btn_container">
          <button @click="composeNew" class="compose_btn shadow-black pill">
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
              <i class="fas fa-mail-bulk text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">Categories</p>
            <div class="sidebar_icons_rightmost">
              <i v-show="toggled"  :class="category_toggles"></i>
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
                <i class="fas fa-tag rotate-135 text-lg"></i>
              </div>
              <p class="sidebar_text" v-show="toggled">{{ labels.text }}</p>
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
        <!-- <inbox-component ref="data_grid" :custom_labels="custom_labels" :routes="routes"/> :resizing='onSplitterResize' -->
        
        <ejs-splitter id="splitter" ref="splitterObj" orientation="Vertical" :resizing="splitterResizing" width="100%" height="92%">
          <e-panes>
            <e-pane size="50%" min="20%" :content="inbox_template" cssClass="overflow-y-hidden e-inbox-display"></e-pane>
            <e-pane size="50%" min ="20%" :content="email_view_template"></e-pane>
          </e-panes>
        </ejs-splitter>
      
        <!-- 
        <splitpanes horizontal >
          <pane min-size="20"><inbox-component ref="data_grid" :custom_labels="custom_labels"/></pane>
        
          <pane>5</pane>
        </splitpanes>
        -->
      </div>
    </div> 
  </div>

  <!-- label drop down -->
  <div 
    v-click-outside="dropdownHideLabel"
    class="custom-dropdown-menu bg-white p-3" 
    :class="[!dropdown_btn_lbl ? 'hidden' : 'block']" 
    :style="{
      top: dropdown_label.top + 'px', 
      left: dropdown_label.left + 'px',
      'z-index': dropdown_zIndex
    }"
  >
  <div class="grid grid-cols-1 divide-y">
    <div>
        <p>Label as:</p>
        <div class="e-input-group" :class="{ 'e-input-focus' : searchbar_label }"> 
          <input id="searchbar_label" @focus="searchbar_label = true" @blur="searchbar_label = false" class="e-input e-textbox" type="text" placeholder="Search">
          <span id="show_filters_icon"  class="e-input-group-icon e-input-calendar"><i class="h-4 w-4 text-lg fas fa-search mr-2"></i></span>
        </div>
        <div class="overflow-y-auto w-full h-72">
          <ejs-listview :dataSource="custom_labels" showCheckBox="true" :fields="fields"></ejs-listview>
        </div>
      </div>
      <div>
        <ejs-listview id="lbl_options" ref="lbl_options" :fields="fields" :select="selectLabelOps" :dataSource="labels_options"></ejs-listview>
      </div>
    </div>
  </div>

  <!-- move to drop down -->
  <div 
    v-click-outside="dropdownHideMoveTo"
    class="custom-dropdown-menu bg-white p-3" 
    :class="[!dropdown_btn_mv ? 'hidden' : 'block']" 
    :style="{
      top: dropdown_label.top + 'px', 
      left: dropdown_label.left + 'px', 
      'z-index': dropdown_zIndex
    }"
  >
  <div class="grid grid-cols-1 divide-y">
    <div>
        <p>Move to:</p>
        <div class="e-input-group" :class="{ 'e-input-focus' : searchbar_label }"> 
          <input id="searchbar_moveTo" @focus="searchbar_label = true" @blur="searchbar_label = false" class="e-input e-textbox" type="text" placeholder="Search">
          <span id="show_filters_icon"  class="e-input-group-icon e-input-calendar"><i class="h-4 w-4 text-lg fas fa-search mr-2"></i></span>
        </div>
        <div class="overflow-y-auto w-full h-72">
          <ejs-listview :dataSource="moveTo_locations" :fields="fields"></ejs-listview>
        </div>
      </div>
      <div>
        <ejs-listview id="moveTo_options_1" ref="moveTo_options_1" :select="selectMoveToOps" :dataSource="moveTo_options" :fields="fields"></ejs-listview>
      </div>
      <div>
        <ejs-listview id="moveTo_options_2" ref="moveTo_options_2" :select="selectMoveToOps" :dataSource="labels_options" :fields="fields"></ejs-listview>
      </div>
    </div>
  </div>

  <!-- user drop down menu -->
  <div 
    v-click-outside="dropdownHideUser"
    ref="user_dropdown"
    class="custom-dropdown-user bg-white p-3" 
    :class="[!dropdown_btn_user ? 'hidden' : 'block']" 
    :style="{
      top: dropdown_label.top + 'px', 
      left: dropdown_label.left + 'px', 
      'z-index': dropdown_zIndex
    }"
  >
    <div class="grid grid-cols-1 divide-y">
      <div>
        <div class="flex mb-5">
          <div class="relative">
            <img class="h-20 w-20 rounded-full" src="" alt="">
            <div class="absolute bottom-1.5 right-0.5">
              <button @click="openModalNewProfile" class="bg-white rounded-full text-gray-400 hover:text-pink-500 focus:outline-none">
                <i class="w-6 p-0.5 fas fa-camera text-sm"></i>
              </button>
            </div>
          </div>
          <div class="m-4 items-center">
            <!-- <b>{{ user.first_name }} {{ user.last_name }}</b>-->
            <p>{{ gmail_user }}</p> 
          </div>
        </div>
        <div class="flex justify-center mb-3">
          <ejs-button iconCss="" cssClass='shadow-none bg-gray-200'>Manage your account</ejs-button>
        </div>
      </div>
      <div>
        <ejs-listview id='accounts_listView' :dataSource="email_accounts" :fields="fields" :template="accounts_list_template"></ejs-listview>
      </div>
      <div>
        <div class="m-3 flex justify-center">
          <ejs-button @click.native="logout" iconCss="" cssClass='shadow-none p-3 bg-gray-200'>Sign Out</ejs-button>
        </div>
      </div>
      <div>
        <div class="flex justify-center items-center mt-3">
          <ejs-button iconCss="" cssClass='shadow-none bg-white text-xs text-gray-500'>Privacy Policy</ejs-button>
          <i class="fas fa-circle text-2xs text-gray-400"></i>
          <ejs-button iconCss="" cssClass='shadow-none bg-white text-xs text-gray-500'>Terms of Service</ejs-button>
        </div>
      </div>
    </div>
  </div>
  
    
</div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import VModal from "vue-js-modal";
import ClickOutside from 'vue-click-outside';
import AvatarCropper from "vue-avatar-cropper";
// import myUpload from "vue-image-crop-upload/upload-2.vue";
import $ from "jquery";

import { RichTextEditorPlugin, Toolbar, Link, Image, Count, HtmlEditor, QuickToolbar, Table, FileManager } from "@syncfusion/ej2-vue-richtexteditor";
import { SidebarPlugin } from "@syncfusion/ej2-vue-navigations";
import { ButtonPlugin , RadioButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { ListViewPlugin } from "@syncfusion/ej2-vue-lists";
import { enableRipple, Browser, addClass, removeClass } from "@syncfusion/ej2-base";
import { CalendarPlugin } from "@syncfusion/ej2-vue-calendars";
import { SplitterPlugin } from "@syncfusion/ej2-vue-layouts";


Vue.component('avatar-cropper', AvatarCropper);
// Vue.component('my-upload', myUpload);
Vue.use(RichTextEditorPlugin);
Vue.use(VModal, { dialog: true });
Vue.use(SidebarPlugin);
Vue.use(ButtonPlugin);
Vue.use(RadioButtonPlugin);
Vue.use(ListViewPlugin);
Vue.use(CalendarPlugin);
Vue.use(SplitterPlugin);

enableRipple(true);

const inbox_component = Vue.component("inbox-component", require("./InboxDisplayComponent.vue").default);
const email_view_component = Vue.component("email-view-component", require("./subcomponents/EmailViewTemplate.vue").default);
const accounts_list_template = Vue.component("accounts-list-template", require("./subcomponents/AccountsListTemplate.vue").default);
const csrf_token = $('meta[name="csrf-token"]').attr('content');

let hostUrl = 'https://ej2-aspcore-service.azurewebsites.net/';

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
    routes: { type: Object, required: true },
    gmail_user: { type: String, required: true }
    // user: { type: Object, required: true }
  },

  data() {
    return {
      enableDock:  true,
      dockSize : "72px",
      width : "16rem",
      position : "Left",
      toggled: true,
      show_upload_profile_pic: false,
      token: csrf_token,
      profile_upload_headers: {
        "Authorization": "Bearer " + csrf_token,
        "X-CSRF-TOKEN": csrf_token
      },
      headers: {
        "Content-Type": "application/json",
        "Authorization": "Bearer " + csrf_token,
        "X-CSRF-TOKEN": csrf_token
      },

      upload_profile_params: {
				// user_id: this.user.id,
				// filename: 'profile_photo_' + this.user.id + ".png"
			},

      // user_profile_photo: this.user.profile_photo ? this.routes.user_profile_path + "/" + this.user.profile_photo : this.routes.user_profile_path + "/default.jpg",
      new_lbl_name: null,
      toggled_sidebar_before_modal_open: false,
      new_lbl_input_is_focused: false,
      category_toggle: false,
      
      myCodeMirror: '',
      quickToolbarSettings: {
        table: ['TableHeader', 'TableRows', 'TableColumns', 'TableCell', '-', 'BackgroundColor', 'TableRemove', 'TableCellVerticalAlign', 'Styles']
      },
      maxLength: 2000,
      toolbarSettings: {
        items: ['Bold', 'Italic', 'Underline', 'StrikeThrough',
        'FontName', 'FontSize', 'FontColor', 'BackgroundColor',
        'LowerCase', 'UpperCase', 'SuperScript', 'SubScript' , '|',
        'Formats', 'Alignments', 'OrderedList', 'UnorderedList',
        'Outdent', 'Indent', '|',
        'CreateTable', 'CreateLink', 'Image', 'FileManager', '|', 'ClearFormat', 'Print', 'SourceCode', 'FullScreen', '|', 'Undo', 'Redo']
      },
      fileManagerSettings: {
        enable: true,
        path: '/Pictures/Food',
        ajaxSettings: {
          url: hostUrl + 'api/FileManager/FileOperations',
          getImageUrl: hostUrl + 'api/FileManager/GetImage',
          uploadUrl: hostUrl + 'api/FileManager/Upload',
          downloadUrl: hostUrl + 'api/FileManager/Download'
        }
      },

      new_lbl_txt: "Please enter new label name:",
      fields: { tooltip: 'text'},
      custom_labels:[
        {id: 0, text: "test_label"},
        {id: 1, text: "test_label_2"}
      ],

      labels_options:[
        {id: 0, text: "Create new"}, 
        {id: 1, text: "Manage labels"}
      ],

      moveTo_options:[
        {id: 0, text: "Spam"}, 
        {id: 1, text: "Trash"}
      ],

      categories:[
        { id: 0, text: "Social" },
        { id: 0, text: "Updates" },
        { id: 0, text: "Forums" },
        { id: 0, text: "Promotions" }
      ],

      moveTo_locations:[],
      searchbar_label: false,
      dropdown_btn_tgl: false,
      dropdown_zIndex: 1005,
      dropdown_label:{
        top: 0,
        left: 0
      },

      email_accounts:[
        {id: 0, text: "Add another account"}
      ],
      
      accounts_list_template(){
        return{
          template: accounts_list_template
        }
      },

      inbox_template(){
        return{ template: inbox_component }
      },
      
      email_view_template(){
        return{ template: email_view_component}
      }

    }
  },

  computed:{
    start(){
      console.log("Sidebar component computed");
      this.$store.dispatch("set_routes", this.routes);
      this.$store.dispatch("set_csrf_token", csrf_token);
    },
    
    category_toggles(){
      
      return this.category_toggle ? "fas fa-chevron-up text-base" : "fas fa-chevron-down text-base";
    },

    message(){
      return this.$store.state.message;
    },

    dropdown_btn_lbl(){
      return this.$store.state.dropdown_btn_lbl;
    },

    dropdown_btn_mv(){
      return this.$store.state.dropdown_btn_mv;
    },

    dropdown_btn_user(){
      return this.$store.state.dropdown_btn_user;
    }
  },

  components:{
  },

  created(){
    console.log("Sidebar component created");
  },

  mounted(){
    console.log("Sidebar component mounted");
    console.log(this.gmail_user);
    console.log(this.$refs.splitterObj.$el.clientHeight);
    this.$store.dispatch("set_splitter_height", this.$refs.splitterObj.$el.clientHeight);
    // console.log("user_profile_photo: " + this.user_profile_photo);
    if(this.custom_labels.length > 0){
      this.moveTo_locations = this.custom_labels.concat(this.categories);
    }
    
    // this.$store.dispatch("set_user_profile_photo", this.user_profile_photo);

    // console.log(this.$store.state.user_profile_photo);
  },

  methods: {
    openModalNewProfile(){
      // this.$modal.show('new_profile_modal');
      this.show_upload_profile_pic = !this.show_upload_profile_pic;
      this.dropdownHideUser();
    },

    //vue-avater event hander
    handleUploading(form, xhr){
      console.log("handleUploading");
      
      form.forEach(element => {
        console.log(element);
      });
      console.log(xhr);
    },

    handleUploaded(response){
      console.log("handleUploaded");
    },

    handleCompleted(response, form, xhr){
      console.log("handleUploaded");
    },

    handlerError(message, type, xhr){
      console.log("handlerError");
    },
    //vue-avater event hander end

    //vue-image-crop
    cropSuccess(imgDataUrl, field){
      console.log('-------- crop success --------');
      console.log('imgDataUrl: ' + imgDataUrl);
      // this.user_profile_photo = imgDataUrl;
      console.log('field: ' + field);
    },

    cropUploadSuccess(jsonData, field){
      console.log('-------- upload success --------');
      console.log(jsonData);
      console.log('field: ' + field);
    },

    cropUploadFail(status, field){
      console.log('-------- upload fail --------');
      console.log(status);
      console.log('field: ' + field);
    },
    //vue-image-crop end

    splitterResizing(args){
      // console.log(args.paneSize[0]);
      this.$store.dispatch("set_splitter_pane_0_height", args.paneSize[0]);
    },

    dropdownHideLabel(){
      this.$store.dispatch("dropdown_btn_lbl_toggle", false);
    },

    dropdownHideMoveTo(){
      this.$store.dispatch("dropdown_btn_mv_toggle", false);
    },

    dropdownHideUser(){
      this.$store.dispatch("dropdown_btn_user_toggle", false);
    },

    modalShow(){
      this.$modal.show('new_label_modal');
    },

    modalHide(){
      this.$modal.hide('new_label_modal');
    },

    modalOpened(){
      if(this.toggled){
        this.$refs.dockSidebar.toggle();
      }
    },
    
    modalClosed(){
      if(!this.toggled_sidebar_before_modal_open){
        this.$refs.dockSidebar.toggle();
      }
    },

    toggleClick() {
      this.$refs.dockSidebar.toggle();
      this.toggled_sidebar_before_modal_open = !this.toggled_sidebar_before_modal_open;
    },

    sidebarClose(){
      this.toggled = !this.toggled;
    },

    sidebarOpen(){
      this.toggled = !this.toggled;
    },

    closeClick() {
      this.$refs.dockSidebar.hide();
    },

    openClick() {
      this.$refs.dockSidebar.show();
    },

    createNewLabel(){
      if(this.new_lbl_name){
        if(isExistLabel(this.new_lbl_name, this.custom_labels)){
          this.new_lbl_txt = "The label name you have chosen already exists. Please try another name:"
        }else{
          console.log(this.new_lbl_name);
          this.custom_labels.push({id: (this.custom_labels.length), text: this.new_lbl_name});
          
          this.moveTo_locations = this.custom_labels.concat(this.categories);

          this.new_lbl_name = null;
          this.$modal.hide("new_label_modal");
        }
      }else{
        this.new_lbl_txt = "No name specified. Please try another name:"
      }
      
    },

    //Compose new email
    composeNew(args){
      this.$modal.show("compose_new_modal");
    },
     mirrorConversion: function(e) {
        var textArea = this.$refs.rteObj.ej2Instances.contentModule.getEditPanel();
        var id = this.$refs.rteObj.ej2Instances.getID() +  'mirror-view';
        var mirrorView = this.$refs.rteObj.$el.parentNode.querySelector('#' + id);
        var charCount = this.$refs.rteObj.$el.parentNode.querySelector('.e-rte-character-count');
        if (e.targetItem === 'Preview') {
          textArea.style.display = 'block';
          mirrorView.style.display = 'none';
          textArea.innerHTML = this.myCodeMirror.getValue();
          charCount.style.display = 'block';
        }
        else {
          if (!mirrorView) {
            mirrorView = document.createElement('div', { className: 'e-content' });
            mirrorView.id = id;
            textArea.parentNode.appendChild(mirrorView);
          }
          else {
            mirrorView.innerHTML = '';
          }
          textArea.style.display = 'none';
          mirrorView.style.display = 'block';
          this.renderCodeMirror(mirrorView, this.$refs.rteObj.ej2Instances.value);
          charCount.style.display = 'none';
        }
      },
      renderCodeMirror: function(mirrorView, content) {
      this.myCodeMirror = CodeMirror(mirrorView, {
        value: content,
        lineNumbers: true,
        mode: 'text/html',
        lineWrapping: true,

      });
    },
    handleFullScreen: function(e){
      var sbCntEle = document.querySelector('.sb-content.e-view');
      var sbHdrEle = document.querySelector('.sb-header.e-view');
      var leftBar;
      var transformElement;
      if (Browser.isDevice) {
        leftBar = document.querySelector('#right-sidebar');
        transformElement = document.querySelector('.sample-browser.e-view.e-content-animation');
      }
      else {
        leftBar = document.querySelector('#left-sidebar');
        transformElement = document.querySelector('#right-pane');
      }
      if (e.targetItem === 'Maximize') {
        if (Browser.isDevice && Browser.isIos) {
          addClass([sbCntEle, sbHdrEle], ['hide-header']);
        }
        addClass([leftBar], ['e-close']);
        removeClass([leftBar], ['e-open']);
        if (!Browser.isDevice) {
          transformElement.style.marginLeft = '0px';
        }
        transformElement.style.transform = 'inherit';
      }
      else if (e.targetItem === 'Minimize') {
        if (Browser.isDevice && Browser.isIos) {
          removeClass([sbCntEle, sbHdrEle], ['hide-header']);
        }
        removeClass([leftBar], ['e-close']);
        if (!Browser.isDevice) {
          addClass([leftBar], ['e-open']);
          transformElement.style.marginLeft = leftBar.offsetWidth + 'px';
        }
        transformElement.style.transform = 'translateX(0px)';
      }
    },

    actionCompleteHandler: function(e) {
      if (e.targetItem && (e.targetItem === 'SourceCode' || e.targetItem === 'Preview')) {
        this.$refs.rteObj.ej2Instances.sourceCodeModule.getPanel().style.display = 'none';
        this.mirrorConversion(e);
      }
      else {
        var proxy = this;
        setTimeout(function () { proxy.$refs.rteObj.ej2Instances.toolbarModule.refreshToolbarOverflow(); }, 400);
      }
    },


    getInbox(event){
      console.log("get all");
      let _this = this;
      axios({
        method: "GET",
        url: this.routes.data_route,
        headers: this.headers,
        params: {
          token: this.token,
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
        headers: this.headers,
        params: {
          token: this.token,
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
        headers: this.headers,
        params: {
          token: this.token,
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
    },

    selectMoveToOps(args){
      console.log(args);
      this.dropdownHideMoveTo();
      this.$refs.moveTo_options_1.selectItem();
      this.$refs.moveTo_options_2.selectItem();

      if(args.data.text === "Create new"){
        console.log("bruh");
        this.modalShow();
      }
    },

    selectLabelOps(args){
      console.log(this.$refs.lbl_options);
      this.dropdownHideLabel();
      this.$refs.lbl_options.selectItem();
      if(args.data.text === "Create new"){
        console.log("bruh");
        this.modalShow();
      }
    },

    logout(){
      axios({
        method: "GET",
        url: this.routes.logging_out,
        headers: this.headers,
        params: {
          token: this.token,
          message: "loggout"
        }
      }).then(function (response) {
        window.location.href = response.data;
      }).catch(error => {
        console.log(error);
        alert("somthing went wrong");
      });
    }
  },

  directives: {
    ClickOutside
  },

  created(){
    let _this = this;

    this.$eventHub.$on("show_custom_dropdown", (e) => {
      // this.dropdown_btn_tgl = this.dropdown_btn_tgl ? false : true;
      this.dropdown_zIndex++;
      if(e.button === "btn_labels"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left);
      }else if(e.button === "btn_move"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left);
      }else if(e.button === "user_dropdown"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left - 298);
        console.log(this.$refs.user_dropdown.clientWidth);
      }
    });

    this.$eventHub.$on("show_datepick_modal", (e) => {
      this.$modal.show('date_picker_modal');
    });
  }
});
</script>
