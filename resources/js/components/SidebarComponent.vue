<template>
<div class="control-section sidebar-default">
  <!-- run start computed -->
  <div :start="start"></div>

  <!-- Compose Modal  -->
  <modal name="compose_new_modal" :adaptive="true" width="70%" height="70%" @before-open="modalOpened" @before-close="modalClosed">
    <div class="p-2 h-full relative flex ">
      <div class="grid grid-cols-3">
        <div class="flex flex-col">
          <!-- <ejs-textbox ref="to" id="to" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="To"></ejs-textbox> -->
          <vue-tags-input
            v-model="email_address_tag"
            ref="vue_tags_address"
            :tags="email_address_tags"
            :add-on-key="[13, 32]"
            :save-on-key="[13, 32]"
            :allowEditTags="true"
            placeholder="To:"
            @before-adding-tag="email_address_tags_add_class"
            @before-saving-tag="email_address_tags_edit"
            @tags-changed="email_address_tags_new"
            
          />
          <ejs-textbox ref="email_subject" id="email_subject" v-model="email_subject" type="text" floatLabelType="Auto" :enabled="true" :readonly="false"  placeholder="Subject"></ejs-textbox>
          <vue-tags-input
            v-model="cc_address_tag"
            ref="cc_tags_address"
            :tags="cc_address_tags"
            :add-on-key="[13, 32]"
            :save-on-key="[13, 32]"
            :allowEditTags="true"
            placeholder="Cc:"
            @before-adding-tag="email_address_tags_add_class"
            @before-saving-tag="email_address_tags_edit"
            @tags-changed="cc_address_tags_new"
            
          />
          <vue-tags-input
            v-model="bcc_address_tag"
            ref="bcc_tags_address"
            :tags="bcc_address_tags"
            :add-on-key="[13, 32]"
            :save-on-key="[13, 32]"
            :allowEditTags="true"
            placeholder="Bcc:"
            @before-adding-tag="email_address_tags_add_class"
            @before-saving-tag="email_address_tags_edit"
            @tags-changed="bcc_address_tags_new"
            
          />

          <ejs-uploader 
            ref="ejs_uploader"
            id='ejs_uploader'
            name="UploadFiles"
            maxFileSize=26214400
            :asyncSettings="attachment_path"
            :uploading="attachmentUpload"
            :removing="removingAttachment"
            :sequentialUpload='true'
            :autoUpload='true' >
          </ejs-uploader> 

          <div class="flex justify-end mt-auto pr-2">
            <ejs-button @click.native="sendMail" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
          </div>
        </div>
        <div class="col-span-2">
          <Vueditor ref="vueditor"></Vueditor>
        </div>
      </div>
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

  <!-- Edit label modal  -->
  <modal name="edit_label_modal" @before-open="modalOpened" @before-close="modalClosed">
    <div class="p-5 h-full relative">
      <h3 class="text-2xl font-semibold">Edit Label</h3>
      <br>
      <p class="text-gray-400">{{ new_lbl_txt }}</p>
      <div class="e-input-group" :class="{ 'e-input-focus': new_lbl_input_is_focused }">
        <input id="new_label_name_input" ref="new_label_name_input" v-model="new_lbl_name" @focus="new_lbl_input_is_focused = true" @blur="new_lbl_input_is_focused = false" class="e-input e-textbox" type="text" placeholder="(ex. Appointments)">
      </div>

      <div class="absolute bottom-5 right-5">
        <ejs-button v-on:click.native="modalHide" class="mr-3">Cancel</ejs-button>
        <ejs-button v-on:click.native="editLabel" :isPrimary="true">Edit</ejs-button>
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
          <a @click="goToInbox('All', 'All')" :class="[ current_inbox.name === 'All' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <i class="fas fa-box-open text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">All emails</p>
          </a>

          <a @click="goToInbox('INBOX', 'INBOX')" :class="[ current_inbox.name === 'INBOX' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <i class="fas fa-inbox text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">Inbox</p>
          </a>

          <a @click="goToInbox('STARRED', 'STARRED')" :class="[ current_inbox.name === 'STARRED' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <i class="far fa-star text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">Starred</p>
          </a>

          <a @click="goToInbox('IMPORTANT', 'IMPORTANT')" :class="[ current_inbox.name === 'IMPORTANT' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <!-- <i class="fas fa-thumbtack text-lg"></i> -->
              <img :src="'/images/label_important_black_20dp.png'" alt="important icon">
            </div>
            <p class="sidebar_text" v-show="toggled">Important</p>
          </a>

          <a @click="goToInbox('SENT', 'SENT')" :class="[ current_inbox.name === 'SENT' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <i class="far fa-paper-plane text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">Sent</p>
          </a>

          <a @click="goToInbox('DRAFT', 'DRAFT')" :class="[ current_inbox.name === 'DELETE' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <i class="far fa-file text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">Drafts</p>
          </a>

          <a @click="goToInbox('TRASH', 'TRASH')" :class="[ current_inbox.name === 'TRASH' ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
            <div class="sidebar_icons">
              <i class="fa fa-trash text-lg"></i>
            </div>
            <p class="sidebar_text" v-show="toggled">Trash</p>
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
            <a v-for="label in user_labels" :key="label.id" @click="goToLabel(label.text, label.id)" :class="[ current_inbox.name === label.text ? 'sidebar_items_selected' : 'sidebar_items' ]" href="#">
              <div class="sidebar_icons">
                <i  class="fas fa-tag rotate-135 text-lg" 
                    :style="{ color: label.color.backgroundColor }"
                />
              </div>
              <p class="sidebar_text" v-show="toggled">{{ label.text }}</p>
              <ejs-dropdownbutton :items="more_items" :select="label_options" @click.native="selected_label = label" @mouseover.native="mouseHover" @mouseleave.native="mouseLeave" iconCss="fas fa-ellipsis-v leading-5" cssClass="e-round shadow-none e-caret-hide ml-auto w-9 h-9 items-center justify-center"></ejs-dropdownbutton>
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
          <input id="searchbar_label" @keyup="searchInput" :value="search" @focus="searchbar_label = true" @blur="searchbar_label = false" class="e-input e-textbox" type="text" placeholder="Search">
          <span id="show_filters_icon"  class="e-input-group-icon e-input-calendar"><i class="h-4 w-4 text-lg fas fa-search mr-2"></i></span>
        </div>
        <div class="overflow-y-auto w-full h-72">
          <ejs-listview :dataSource="custom_labels_temp" showCheckBox="true" :select="selected" :fields="fields"></ejs-listview>
        </div>
      </div>
      <div>
        <ejs-listview id="lbl_options" ref="lbl_options" :fields="fields" :select="selectLabelOps" :dataSource="label_selected ? labels_options_apply : labels_options"></ejs-listview>
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
          <ejs-listview id="moveTo_options_0" ref="moveTo_options_0" :select="selectMoveToOps" :dataSource="labels_locations" :fields="fields"></ejs-listview>
        </div>
      </div>
      <div>
        <ejs-listview id="moveTo_options_1" ref="moveTo_options_1" :select="selectMoveToOps" :dataSource="moveTo_options" :fields="fields"></ejs-listview>
      </div>
      <div>
        <ejs-listview id="moveTo_options_2" ref="moveTo_options_2" :select="selectMoveToOps" :dataSource="label_selected ? labels_options_apply : labels_options" :fields="fields"></ejs-listview>
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
  
  <!-- Email details dropdown -->
  <div 
    v-click-outside="dropdownHideEmailData"
    ref="email_data_dropdown"
    class="custom-dropdown-user bg-white p-3" 
    :class="[!dropdown_btn_email_data ? 'hidden' : 'block']" 
    :style="{
      top: dropdown_label.top + 'px', 
      left: dropdown_label.left + 'px',
      'width': '35%',
      'z-index': dropdown_zIndex,
    }"
  >
    <div v-if="email_data" class="grid grid-cols-4 gap-2 p-3">
      <div class="text-right">
        <p>from:</p>
        <p>reply-to:</p>
        <p>to:</p>
        <p v-if="email_data.cc">cc:</p>
        <p v-if="email_data.bcc">bcc:</p>
        <p>date:</p>
        <p>subject:</p>
        <p>mailed-by:</p>
        <p>signed-by:</p>
      </div>
      <div class="col-span-3 pl-2">
        <p>{{ email_data.from.name ? email_data.from.name : "" }}{{ email_data.from.email ? "&lt;" + email_data.from.email + "&gt;" : "" }}</p>
        <p>{{ email_data.from.name ? email_data.from.name : "" }}{{ email_data.from.email ? "&lt;" + email_data.from.email + "&gt;" : "" }}</p>
        <p>{{ email_data.to.email ? email_data.to.email : email_data.to.name }}</p>
        <p>{{ email_data.cc ? email_data.cc : "" }}</p>
        <p>{{ email_data.bcc ? email_data.bcc : "" }}</p>
        <p>{{ email_data.date }}</p>
        <p>{{ email_data.subject }}</p>
        <p>{{ email_data.arc_auth }}</p>
        <p>{{ email_data.arc_auth }}</p>
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

import { SidebarPlugin } from "@syncfusion/ej2-vue-navigations";
import { ButtonPlugin , RadioButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { ListViewPlugin } from "@syncfusion/ej2-vue-lists";
import { enableRipple, isNullOrUndefined  } from "@syncfusion/ej2-base";
import { CalendarPlugin } from "@syncfusion/ej2-vue-calendars";
import { SplitterPlugin } from "@syncfusion/ej2-vue-layouts";
import { VueTagsInput } from "@johmun/vue-tags-input";
import { UploaderPlugin } from "@syncfusion/ej2-vue-inputs";
import VueNotification from "@kugatsu/vuenotification";

Vue.component('avatar-cropper', AvatarCropper);
// Vue.component('my-upload', myUpload);

Vue.use(VModal, { dialog: true });
Vue.use(SidebarPlugin);
Vue.use(ButtonPlugin);
Vue.use(RadioButtonPlugin);
Vue.use(ListViewPlugin);
Vue.use(CalendarPlugin);
Vue.use(SplitterPlugin);
Vue.use(VueTagsInput);
Vue.use(UploaderPlugin);
Vue.use(VueNotification, {
  timer: 20
});

enableRipple(true);
const inbox_component = Vue.component("inbox-component", require("./InboxDisplayComponent.vue").default);
const email_view_component = Vue.component("email-view-component", require("./subcomponents/EmailViewTemplate.vue").default);
const accounts_list_template = Vue.component("accounts-list-template", require("./subcomponents/AccountsListTemplate.vue").default);
const csrf_token = $('meta[name="csrf-token"]').attr('content');


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

function isExistLabel(new_label, user_labels){
  for (let i = 0; i < user_labels.length; i++) {
    console.log(user_labels)
    if(user_labels[i].text === new_label){
      return true;
    }
  }

  return false;
}

export default Vue.extend({
  name: "SidebarComponent",
  props:{
    gmail_user: { type: String, required: true },
    url_base: { type: String, required: true }
  },

  data() {
    return {
      search: '',
      label_selected: false,
      label_selected_array: [],
      routes: null,
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
      hover_label_opt: false,
      selected_label: null,
      email_address_tag: "",
      email_address_tags: [],
      email_addresses: null,
      cc_address_tag: "",
      cc_address_tags: [],
      cc_addresses: null,
      bcc_address_tag: "",
      bcc_address_tags: [],
      bcc_addresses: null,
      email_subject: "",
      email_address_tag_validation: [],
      new_lbl_txt: "Please enter new label name:",
      fields: { tooltip: 'text'},
      custom_labels: [],
      custom_labels_temp: [],

      labels_options:[
        {id: 0, text: "Create new"}, 
        {id: 1, text: "Manage labels"}
      ],

      labels_options_apply: [
        {id: 0, text: "Apply"}
      ],

      moveTo_options:[
        {id: 0, text: "Spam"}, 
        {id: 1, text: "Trash"},
        {id: 2, text: "Inbox"},
      ],

      categories:[
        { id: 0, text: "Social" },
        { id: 0, text: "Updates" },
        { id: 0, text: "Forums" },
        { id: 0, text: "Promotions" }
      ],

      more_items:[  
        { id: 0, text: "Edit label" },
        { id: 1, text: "Delete label" }
      ],

      // moveTo_locations:[],
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
      },

      attachment_path: null,

      dropElement: '.control-fluid',
      email_date_display: null,
    }
  },

  computed:{
    ref_headerTemplate(){
      return this.$store.state.headerTemplate;
    },

    start(){
      console.log("Sidebar component computed");
      let routes = {
        data_route:           this.url_base + "/get_data",
        send_mail:            this.url_base + "/send_mail",
        upload_attachment:    this.url_base + "/upload_attachment",
        check_attachment:     this.url_base + "/check_attachment",
        download_attachment:  this.url_base + "/download_attachment",
        remove_attachment:    this.url_base + "/remove_attachment",
        toggle_route:         this.url_base + "/toggle_data",
        set_many_route:       this.url_base + "/toggle_many_data",
        logging_out:          this.url_base + "/logging_out",
        upload_profile_pic:   this.url_base + "/upload_profile_pic",
        user_profile_path:    this.url_base + "/img/users_profile_photo/",
        delete_mail:          this.url_base + "/delete_mail",
        delete_mail_forever:  this.url_base + "/delete_mail_forever",
        move_to_inbox:        this.url_base + "/move_to_inbox",
        labels:               this.url_base + "/labels",
        labels_add:           this.url_base + "/labels/add",
      };
      console.log(routes);
      this.$store.dispatch("set_routes", routes);
      this.$store.dispatch("set_csrf_token", csrf_token);
      this.$store.dispatch("set_user_email", this.gmail_user);

      this.attachment_path = {
        saveUrl: routes.upload_attachment,
        removeUrl: routes.remove_attachment
      }
      this.routes = routes;
    },

    current_inbox(){
      return this.$store.state.current_inbox;
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
    },

    dropdown_btn_email_data(){
      return this.$store.state.dropdown_btn_email_data;
    },

    user_labels(){
      return this.$store.state.user_labels;
    },

    labels_locations(){
      if(this.user_labels){
        // return this.user_labels.concat(this.categories);
        return this.user_labels;
      }else{
        // return this.categories;
      }
      
    },

    email_data(){
      let data = this.$store.state.selected_email_data

      if(data !== null){
        this.email_date_display = moment(data.date).format("LLL");
      }

      return data;
    },

    selected_items_dataID(){
      return this.$store.state.selected_items_dataID
    },
  },

  components:{},

  created(){
    console.log("Sidebar component created");
  },

  mounted(){
    console.log("Sidebar component mounted");
    console.log(this.gmail_user);
    
    this.$store.dispatch("set_splitter_height", this.$refs.splitterObj.$el.clientHeight);
    // console.log("user_profile_photo: " + this.user_profile_photo);
    
    // if(this.user_labels.length > 0){
    //   this.moveTo_locations = this.user_labels.concat(this.categories);
    // }
    
    // this.$store.dispatch("set_user_profile_photo", this.user_profile_photo);

    // console.log(this.$store.state.user_profile_photo);
    this.custom_labels_temp = this.custom_labels
  },

  methods: {
    mouseHover(){
      this.hover_label_opt = true;
    },

    mouseLeave(){
      this.hover_label_opt = false;
    },
   
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

    dropdownHideEmailData(){
      this.$store.dispatch("dropdown_btn_email_data_toggle", false);
    },

    modalShow(){
      this.$modal.show('new_label_modal');
    },

    modalShow_editLabel(){
      this.$modal.show('edit_label_modal');
    },

    modalHide(){
      this.$modal.hide('new_label_modal');
      this.$modal.hide('edit_label_modal');
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

    sendMail(){
      let _this = this;
      let invalid_emails = validateEmails(this.email_addresses);
      let invalid_ccs = false;
      let invalid_bccs = false;
      let files = this.$refs.ejs_uploader.getFilesData();
      let attachments = [];

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
      
      
      if(!invalid_emails && !invalid_ccs && !invalid_bccs){
        console.log("sending Email...");
        let data = {
            option: "new_email",
            addresses: this.email_addresses,
            cc: this.cc_addresses,
            bcc: this.bcc_addresses,
            subject: this.email_subject,
            message: this.$refs.vueditor.getContent(),
            attachments: attachments,
        };

        axios.post(this.routes.send_mail, data, {
          headers:{
            "Content-Type": "multipart/mixed",
            "Authorization": "Bearer " + csrf_token,
            "X-CSRF-TOKEN": csrf_token
          }
        }).then(function (response) {
          console.log(response);
          _this.$modal.hide("compose_new_modal");
          _this.$notification.success("Message Sent", {  timer: 5 });
        }).catch(error => {
          console.log(error);
          _this.$modal.hide("compose_new_modal");
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });

      }else{
        this.$notification.warning("invalid email exists!", {  timer: 5 });
      }
    },

    createNewLabel(){
      if(this.new_lbl_name){
        if(isExistLabel(this.new_lbl_name, this.labels_locations)){
          this.new_lbl_txt = "The label name you have chosen already exists. Please try another name:"
        }else{
          console.log(this.new_lbl_name);
          let _this = this;
          let new_lbl_name = this.new_lbl_name;

          let data = {
            option: "create",
            label_name: new_lbl_name,
          };

          axios.post(this.routes.labels, data, {
          headers:{
              "Content-Type": "multipart/mixed",
              "Authorization": "Bearer " + csrf_token,
              "X-CSRF-TOKEN": csrf_token
            }
          }).then(function (response) {
            let payload = response.data;
            console.log(payload);
            console.log(new_lbl_name);

            _this.$store.dispatch("set_user_labels", payload.labels)
            _this.$modal.hide("new_label_modal");
            _this.$notification.success("Label: " + new_lbl_name + " created", {  timer: 5 });
          }).catch(error => {
            console.log(error);
            _this.$modal.hide("new_label_modal");
            _this.$notification.error("somthing went wrong", {  timer: 5 });
          });

          this.new_lbl_txt = null;
          this.new_lbl_name = null;
        }
      }else{
        this.new_lbl_txt = "No name specified. Please try another name:"
      }
      
    },

    editLabel(){
      if(this.new_lbl_name){
        if(isExistLabel(this.new_lbl_name, this.labels_locations)){
          this.new_lbl_txt = "The label name you have chosen already exists. Please try another name:"
        }else{
          let data = {
            option: "edit",
            label_id: this.selected_label.id,
            label_name: this.new_lbl_name,
          }
          this.labels_ajax(data);
        }
      }else{
        this.new_lbl_txt = "No name specified. Please try another name:"
      }
      
     
    },

    //Compose new email
    composeNew(args){
      this.$modal.show("compose_new_modal");
    },

    attachmentUpload(args){
      args.currentRequest.setRequestHeader("Authorization", "Bearer " + csrf_token);
      args.currentRequest.setRequestHeader("X-CSRF-TOKEN", csrf_token);
      
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
      args.currentRequest.setRequestHeader("Authorization", "Bearer " + csrf_token);
      args.currentRequest.setRequestHeader("X-CSRF-TOKEN", csrf_token);
    },

    goToInbox(inbox_name, inbox_id){
      console.log(inbox_name);
      let _this = this;

      _this.ref_headerTemplate.show_loading = true;
      _this.ref_headerTemplate.loading = true;

      axios.get(this.routes.data_route,{
        headers: this.headers,
        params: {
          inbox: inbox_name,
          inbox_id: inbox_id,
          option: null
        }
      }).then(function (response) {
        console.log(response);
        _this.$store.dispatch("set_current_inbox", {name: inbox_name, id: inbox_id, type: 0});
        _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));+
        _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
        _this.$store.dispatch("set_inbox_total", response.data.inbox_info.messagesTotal);
        
        _this.ref_headerTemplate.show_loading = false;
        _this.ref_headerTemplate.loading = false;
        _this.$eventHub.$emit("page_change");
      }).catch(error => {
        console.log(error);
        this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    goToLabel(label_name, lable_id){
      if(!this.hover_label_opt){
        let _this = this;

        _this.ref_headerTemplate.show_loading = true;
        _this.ref_headerTemplate.loading = true;
        axios.get(this.routes.data_route,{
          headers: this.headers,
          params: {
            inbox: label_name,
            label_id: lable_id,
            option: "labeled"
          }
        }).then(function (response) {
          console.log(response);
          _this.$store.dispatch("set_current_inbox", {name: label_name, id: lable_id, type: 1});
          _this.$store.dispatch("set_email_batch", formatDate(response.data.repackaged_data));+
          _this.$store.dispatch("set_inbox_items", response.data.inbox_items_length);
          _this.$store.dispatch("set_inbox_total", response.data.inbox_info.messagesTotal);
          
          _this.ref_headerTemplate.show_loading = false;
          _this.ref_headerTemplate.loading = false;
          _this.$eventHub.$emit("page_change");
        }).catch(error => {
          console.log(error);
          this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      }
      
    },

    selectMoveToOps(args){
      let _this = this;
      this.dropdownHideMoveTo();
      this.$refs.moveTo_options_0.selectItem();
      this.$refs.moveTo_options_1.selectItem();
      this.$refs.moveTo_options_2.selectItem();
  
      if(args.data.text === "Create new"){
        this.modalShow();
      }else if(args.data.id === 1) { // Trash
        this.selectedItemsTo(9, this.$store.state.selected_items_dataID, this.$store.state.routes);
      }else{
        // Move to labels
        
        _this.ref_headerTemplate.show_loading = true;
        _this.ref_headerTemplate.loading = true;
        axios.get(this.routes.set_many_route, {
          headers: {
            "Content-Type":   "application/json",
            "Authorization":  "Bearer {{ csrf_token() }}"
          },
          params: {
            option:   10,
            dataIDs:  this.selected_items_dataID,
            labelID:  args.data.id,
            current_inbox_id: this.current_inbox.id,
          }
        }).then(function (response) {
          // _this.refreshInbox();
          _this.$eventHub.$emit("refresh_inbox", {
            event: "refresh_inbox"
          });
          console.log(response);
        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      }
    },

    selectLabelOps(args){
      let _this = this;
      console.log(this.$refs.lbl_options);
      this.dropdownHideLabel();
      this.$refs.lbl_options.selectItem();
      if(args.data.text === "Create new"){
        this.modalShow();
      } else if(args.data.text === "Apply") { // Apply button
        console.log(this.label_selected_array)
        // set labels
        axios.get(this.routes.labels_add, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + csrf_token,
          "X-CSRF-TOKEN": csrf_token
        },
        params: {
          id: this.selected_items_dataID,
          labels: this.label_selected_array
        }
      }).then(function (response) {
        console.log(response.data.success)
        _this.refreshInbox()
        response.data.success ? _this.$notification.success("Label added", {  timer: 5 }) : _this.$notification.error("Something went wrong", { timer: 5 })
      }).catch(error => {
        console.log(error);
        this.$notification.error("somthing went wrong", {  timer: 5 });
      });
      }
    },

    logout(){
      axios.get(this.routes.logging_out, {
        headers: this.headers,
        params: {
          message: "loggout"
        }
      }).then(function (response) {
        window.location.href = response.data;
      }).catch(error => {
        console.log(error);
        this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    refreshInbox(){
      console.log("header refresh inbox");
      this.$eventHub.$emit("refresh_inbox", {
        event: "refresh_inbox"
      });
      this.loading = true;
    },

    selectedItemsTo(option, dataIDs, route) {
      let _this = this;

      _this.ref_headerTemplate.show_loading = true;
      _this.ref_headerTemplate.loading = true;
      axios.get(route.set_many_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          option: option,
          dataIDs: dataIDs,
        }
      }).then(function (response) {
        _this.refreshInbox();
        console.log(response);
      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    labels_ajax(data){
      if(data){
        let _this = this;
        axios.post(this.routes.labels, data, {
        headers:{
            "Content-Type": "multipart/mixed",
            "Authorization": "Bearer " + csrf_token,
            "X-CSRF-TOKEN": csrf_token
          }
        }).then(function (response) {
          let payload = response.data;
          console.log(payload);

          _this.modalHide();
          _this.$store.dispatch("set_user_labels", payload.labels);
          _this.$notification.success("Label: " + data.label_name + " " + data.option + "ed", {  timer: 5 });
        }).catch(error => {
          console.log(error);
          _this.$notification.error("somthing went wrong", {  timer: 5 });
        });
      }
    },

    label_options(args){

      let data = null;
      switch (args.item.text) {
        case "Delete label":
          data = {
            option: "delete",
            label_id: this.selected_label.id,
            label_name: this.selected_label.text,
          }
          this.labels_ajax(data);
          break;

        case "Edit label":
          this.new_lbl_name = this.selected_label.text;
          this.modalShow_editLabel();
          break;
      
        default:
          this.$notification.error("somthing went wrong", {  timer: 5 });
          break;
      }
    },

    searchInput(e) {
      const value = e.target.value
      this.search = value
      const result = this.custom_labels.filter(word => word.text.includes(this.search))
      this.custom_labels_temp = result
    },

    selected(args) {
      console.log(args)
      if(args.isChecked) {
        this.label_selected = true
        this.label_selected_array.push(args.data)
      } else {
        const pos = this.label_selected_array.findIndex( element => {
          if (element.id === args.data.id) {
            return true
          }
        })
        this.label_selected_array.splice(pos,1)
        this.label_selected_array.length > 0 ? this.label_selected = true : this.label_selected = false
      }
      console.log(this.label_selected_array)
    }
  },

  directives: {
    ClickOutside
  },

  created(){
    let _this = this;

    this.$eventHub.$on("show_custom_dropdown", (e) => {
      console.log(e);
      // this.dropdown_btn_tgl = this.dropdown_btn_tgl ? false : true;
      this.dropdown_zIndex++;
      if(e.button === "btn_labels"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left);
      }else if(e.button === "btn_move"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left);
      }else if(e.button === "btn_email_data"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left);
      }else if(e.button === "user_dropdown"){
        this.dropdown_label.top = parseInt(e.top + 43);
        this.dropdown_label.left = parseInt(e.left - 298);
      }
    });

    this.$eventHub.$on("show_datepick_modal", (e) => {
      this.$modal.show('date_picker_modal');
    });

    this.$eventHub.$on("show_custom_dropdown", (e) => {
      this.custom_labels = this.$store.state.user_labels
      this.custom_labels_temp = this.custom_labels
    });
  }
});
</script>
