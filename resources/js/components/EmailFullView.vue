<template>
  <div id="EmailViewFull_comp" ref="EmailViewFull_comp" :start="start">
    <!-- Email details dropdown -->
    <div 
      v-click-outside="drpdwn_click_out"
      ref="email_data_dropdown"
      class="custom-dropdown-user bg-white p-3" 
      :class="[selected_drpdwn === 'email_details' ? 'block' : 'hidden']" 
      :style="{
        top: dropdown_label.top + 'px', 
        left: dropdown_label.left + 'px',
        'width': '38%',
        'z-index': dropdown_zIndex,
      }"
    >
      <div v-if="email_data" class="p-3">
        <table class="table-fixed">
          <thead>
            <tr>
              <th class="w-1/5"></th>
              <th class="w-1/2 "></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-right align-top">
                <p>from:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.from.name ? email_data.from.name : "" }}{{ email_data.from.email ? "&lt;" + email_data.from.email + "&gt;" : "" }}</p>
              </td>
            </tr>

            <tr>
              <td class="text-right align-top">
                <p>reply-to:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.from.name ? email_data.from.name : "" }}{{ email_data.from.email ? "&lt;" + email_data.from.email + "&gt;" : "" }}</p>
              </td>
            </tr>
            
            <tr>
              <td class="text-right align-top">
                <p>to:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.to.email ? email_data.to.email : email_data.to.name }}</p>
              </td>
            </tr>
            
            <tr v-if="email_data.cc">
              <td class="text-right align-top">
                <p>cc:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.cc ? email_data.cc : "" }}</p>
              </td>
            </tr>
            
            <tr v-if="email_data.bcc">
              <td class="text-right align-top">
                <p>bcc:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.bcc ? email_data.bcc : "" }}</p>
              </td>
            </tr>
            
            <tr>
              <td class="text-right align-top">
                <p>date:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.date }}</p>
              </td>
            </tr>
            
            <tr>
              <td class="text-right align-top">
                <p>subject:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.subject }}</p>
              </td>
            </tr>
            
            <tr>
              <td class="text-right align-top">
                <p>mailed-by:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.arc_auth }}</p>
              </td>
            </tr>
            
            <tr>
              <td class="text-right align-top">
                <p>signed-by:</p>
              </td>
              <td class="pl-2">
                <p>{{ email_data.arc_auth }}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    
    
    
    </div>
    <div 
      ref="email_option_dropdown"
      class="custom-dropdown-user bg-white p-3" 
      :class="[selected_drpdwn === 'emailOptions' ? 'block' : 'hidden']" 
      :style="{
        top: dropdown_label.top + 'px', 
        left: dropdown_label.left + 'px',
        'z-index': dropdown_zIndex,
        width: '200px',
        height: '200px',
      }"
    >
    <ejs-listview :dataSource="emailOpts" :select="moreEmailOpts"></ejs-listview>
    </div>
    <!-- label drop down -->
    <div 
      v-click-outside="drpdwn_click_out"
      class="custom-dropdown-menu bg-white p-3" 
      :class="[selected_drpdwn === 'view_labelAs' ? 'block' : 'hidden']" 
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
            <input id="searchbar_label_" ref='textboxEle' @keyup='searchInput' :value="search" @focus="searchbar_label = true" @blur="searchbar_label = false" class="e-input e-textbox" type="text" placeholder="Search">
            <span id="show_filters_icon_"  class="e-input-group-icon e-input-calendar"><i class="h-4 w-4 text-lg fas fa-search mr-2"></i></span>
          </div>
          <div class="overflow-y-auto w-full h-72">
            <ejs-listview :dataSource="user_labels_temp" ref='listObj' showCheckBox="true" :select="selected" :fields="fields"></ejs-listview>
          </div>
        </div>
        <div>
          <ejs-listview id="lbl_options" ref="lbl_options" :fields="fields" :select="ref_sidebar.selectLabelOps" :dataSource="labels_options"></ejs-listview>
        </div>
      </div>
    </div>

    <div ref="header_template" class="flex relative items-center h-14" :start="start">
      <div v-if="in_inbox" e-mappinguid="grid-column0">
        <div class="e-checkbox-wrapper e-css z-40">
          <input class="e-checkselectall e-focus" type="checkbox">
          <span class="e-frame e-icons e-uncheck"></span>
          <span class="e-label"> </span>
        </div>
        <ejs-dropdownbutton :items="drop_down_items" :select="dropDownSelect" class="absolute left-5 pl-0 py-1 pr-1.5"></ejs-dropdownbutton>
      </div>
      <div class="pl-5 flex">
        <div :class="{'hidden': items_selected}" class="flex">

          <!--
          <ejs-tooltip content="Refresh" position="BottomCenter">
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
          </ejs-tooltip>
          -->

          <ejs-tooltip content="More" position="BottomCenter">
            <ejs-dropdownbutton :select="markAllRead" :items="more_items" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
          </ejs-tooltip>
        </div>
        <div :class="{'hidden': !items_selected}" class="grid grid-cols-3 divide-x">
          <div class="px-2 flex">
            <!-- Button Archive  -->
            <ejs-button @click.native="btnArchive" iconCss="fas fa-archive" cssClass="e-round shadow-none" ></ejs-button>

            <!-- Button Report as Spam  -->
            <ejs-button @click.native="btnReportSpam" iconCss="fas fa-exclamation-triangle" cssClass="e-round shadow-none" ></ejs-button>

            <!-- Button Delete  -->
            <ejs-button @click.native="btnDelete" iconCss="fas fa-trash-alt" cssClass="e-round shadow-none" ></ejs-button>
          </div>

          <div class="px-2 flex">
            <!-- Button Read  -->
            <ejs-button @click.native="btnToggleRead" :iconCss="read_tgl_button_icon" cssClass="e-round shadow-none" ></ejs-button>

            <!-- Button Snooze
            <ejs-tooltip content="Snooze" position="BottomCenter">
              <ejs-dropdownbutton target="#snooze_listView" :items="more_items" iconCss="fas fa-clock" cssClass="e-round shadow-none e-caret-hide"></ejs-dropdownbutton>
              <ejs-listview id="snooze_listView" ref="snooze_listView" class="shadow-black-lg" :select="snoozeSelect" :dataSource="snooze_opitons" :fields="snooze_fields" :template="snooze_template"></ejs-listview>
            </ejs-tooltip> -->
            <!-- Button Move to  -->

            <ejs-button ref="btn_move" @click.native="btnMove" iconCss="fas fa-file-export" cssClass="e-round shadow-none" ></ejs-button>

            <!-- Button Labels  -->
            <ejs-button ref="btn_labels" @click.native="btnLabels" iconCss="fas fa-tag rotate-135" cssClass="e-round shadow-none" ></ejs-button>
          </div>

          <div class="px-2 flex">
            <!-- Button More  -->
             <ejs-dropdownbutton @click.native="dropdownAction" target="#items_selected_" iconCss="fas fa-ellipsis-v" cssClass="e-round e-caret-hide"></ejs-dropdownbutton>
              <ejs-listview id="items_selected_" :dataSource="more_items_selected" :select="moreOptions"></ejs-listview>
          </div>
        </div>
      </div>
      <!--
      <div class="ml-auto">
        <div v-if="selected_all && !selected" class="flex items-center justify-center content-center place-content-center">
          <p class="text-gray-500 text-base font-medium font-roboto"> All <b>{{ selected_items }}</b> emails on this page are selected.</p>
          <ejs-button ref="select_all_email" @click.native="selected_all_items" cssClass="shadow-none">Select all {{ inbox_total }} emails in {{ current_inbox.name }}</ejs-button>
        </div>

        <div v-if="selected" class="flex items-center justify-center content-center place-content-center">
          <p class="text-gray-500 text-base font-medium font-roboto"> All <b>{{ inbox_total }}</b> emails on this inbox are selected.</p>
          <ejs-button ref="select_all_email" @click.native="clear_selected_items" cssClass="shadow-none">Clear selection</ejs-button>
        </div>

        <div v-if="(loading && items_selected) || (loading && show_loading)">
          <vue-loaders-ball-beat color="black" scale="0.8"></vue-loaders-ball-beat>
        </div>
      </div>
      -->
    </div>

    <div id="email_html_body" class="p-5" :start="start" :email_rowData="email_rowData">
      <div v-if="email_data">
        <div class="flex pb-4 items-center">
          <p class="font-bold text-xl">
            {{ email_data.subject }}
          </p>
          <div @click="importantEmail" class="object-none content-center m-2 cursor-pointer">
            <img :src="[is_important ? '/images/label_important_yellow_20dp.png' : '/images/label_important_black_20dp.png']" alt="important icon">
          </div>

          <p v-for="label_id in email_data.labels" :key="label_id">
            <span v-if="user_labels_keyed[label_id]" class="px-2 py-1 mr-2 font-medium text-xs" :style="{ 'color': user_labels_keyed[label_id].color.textColor, 'background-color': user_labels_keyed[label_id].color.backgroundColor}">
              {{ user_labels_keyed[label_id].name }}
            </span>
            <span v-else-if="!label_id.includes('CATEGORY') && !user_labels_keyed[label_id] && label_id !== 'UNREAD' && label_id !== 'STARRED' && label_id !== 'IMPORTANT'" class="px-2 py-1 mr-2 bg-gray-300 font-medium text-xs">
              {{ label_id }}
            </span>
          </p>

        </div>
        
        <div class="flex">
          <div>
            <p class="font-bold text-base">
            {{ email_data.from.name }} 
              <span class="text-gray-500 text-sm font-light">{{ email_data.from.email ? "&lt;" + email_data.from.email + "&gt;" : "" }}</span>
            </p>
            <p class="text-xs">
              {{ email_data.to.email === user_email ? "to me" : "to " + email_data.to.name }}{{ email_data.cc ? ", " + email_data.cc : ""}}
              <ejs-button ref="btn_email_data" @click.native="email_details" iconCss="fas fa-caret-down" cssClass="e-round shadow-none" class=""></ejs-button>
            </p>
            
          </div>

          <div class="ml-auto">
            <p class="text-xs">
              {{ email_date_display }}
              <span>({{ time_duration }})</span>
              <ejs-button ref="" @click.native="starEmail" :iconCss="[is_starred ? 'fas fa-star text-yellow-500' : 'far fa-star']" cssClass="e-round shadow-none" class=""></ejs-button>
              <ejs-button ref="" @click.native="hideShowCompose(); replyEmail();" iconCss="fas fa-reply" cssClass="e-round shadow-none" class=""></ejs-button>
              <!--<ejs-dropdownbutton :items="emailOpts" :select="moreEmailOpts" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none e-caret-hide">1</ejs-dropdownbutton>-->
              <ejs-button ref="btn_email_ops" @click.native="emailOptions" iconCss="fas fa-ellipsis-v" cssClass="e-round shadow-none" class=""></ejs-button>
            </p>
          </div>
        </div>
      
        <div class="divide-y divide-gray-500 mt-5 pt-3">

          <iframe class="w-full" :srcdoc="email_body_html" frameborder="0" onload='javascript:(function(o){o.style.height=o.contentWindow.document.body.scrollHeight+"px";}(this));' style="height:100%;width:100%;border:none;overflow:hidden;min-height:50px;">
            Your browser is not compatible!
          </iframe>

          <div v-if="email_attachments !== null" class="mt-8 pt-3">

            <div v-for="(file, index) in email_attachments" :key="index" @click="attachmentClicked($event, file.name)" class="flex bg-white mb-4 border rounded-lg w-48 h-10 items-center truncate cursor-pointer">
              <p class="font-bold px-3 py-5 w-40">{{ file.name }}</p>
            </div>
          </div>
        </div>
      </div>
      
      <div id="email_actions" :class="[ (email_body_html && !show_reply) ? 'block' : 'hidden']" class="flex mt-10 pt-2">
        <div class="m-2">
          <div @click="hideShowCompose(); replyEmail();" class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
            <p class="flex font-bold px-3 py-5 w-35 items-center justify-center text-sm"><i class="fas fa-reply pr-2"></i> Reply</p>
          </div>
        </div>
        <div class="m-2"  v-show="show_reply_all == true">
          <div @click="hideShowCompose(); replyAllEmail();" class="flex bg-white mb-4 border rounded-lg w-25 h-10 cursor-pointer">
            <p class="flex font-bold px-3 py-5 w-35 items-center justify-center text-sm"><i class="fas fa-reply-all pr-2"></i> Reply All</p>
          </div>
        </div>
        <div class="m-2">
          <div @click="hideShowCompose(); forwardEmail();" class="flex bg-white mb-4 border rounded-lg w-24 h-10 cursor-pointer">
            <p class="flex font-bold px-3 py-5 w-35 items-center justify-center text-sm"><i class="fas fa-arrow-right pr-2"></i> Forward</p>
          </div>
        </div>
      </div>

      <div  id="email_action_compose" :class="[ show_reply ? 'block' : 'hidden']" class="grid grid-cols-4 gap-3 mt-6 pt-3 pb-3">
        <div :class="[ show_attachment ? 'col-span-3' : 'col-span-4']">
          <div class="w-full">
            <div class="flex w-full">
              <div class="w-full">
                <div class="flex items-center w-full">
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
                    @tags-changed="email_address_tags_new"
                    @before-adding-tag="email_address_tags_add_class"
                    @before-saving-tag="email_address_tags_edit"
                  />
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

            <Vueditor ref="reply_content" class="w-full"></Vueditor>

            <div class="flex w-full mt-3">
              <ejs-button @click.native="sendReply" :isPrimary="true"><i class="fas fa-paper-plane"></i> Send</ejs-button>
              <ejs-button @click.native="show_attachment = !show_attachment"><i class="fas fa-paperclip"></i></ejs-button>
              <ejs-button id="trash_btn" @click.native="hideShowCompose(); replyEmail();" class="ml-auto bg-red-600 text-white"><i class="fas fa-trash"></i></ejs-button>
            </div>
          </div>
        </div>
        <div v-if="show_attachment" class="col-span-1">
          <div class="border w-full p-3 mt-5">
            <p class="text-base font-bold"><i class="fas fa-paperclip"></i> Attachments</p>
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
  </div>
</template>
<script>
import Vue from "vue";

import moment from "moment";
import VModal from 'vue-js-modal';
import VueNotification from "@kugatsu/vuenotification";

import ClickOutside from 'vue-click-outside';

import { DropDownButtonPlugin, ProgressButtonPlugin  } from "@syncfusion/ej2-vue-splitbuttons";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import { TooltipPlugin } from "@syncfusion/ej2-vue-popups";
import { ListViewPlugin } from "@syncfusion/ej2-vue-lists";

Vue.use(ButtonPlugin);
Vue.use(DropDownButtonPlugin);
Vue.use(ProgressButtonPlugin);
Vue.use(TooltipPlugin);
Vue.use(ListViewPlugin);
Vue.use(VModal, { dialog: true });
Vue.use(VueNotification, {
  timer: 20
});


const header_template = Vue.component("header-template", require("./subcomponents/HeaderTemplate.vue").default);
const email_view_component = Vue.component("email-view-component", require("./subcomponents/EmailViewTemplate.vue").default);
const snooze_template = Vue.component("snooze_template", require("./subcomponents/SnoozeListViewTemplate.vue").default);

function formatDate(date) {
  let new_date = moment(date).format("LLL");
  return new_date;
}

function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email.trim());
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
  name: "EmailFullView",

  data(){
    return{
      in_inbox: false,

      //drop downs
      search: '',
      fields: { tooltip: 'text'},
      searchbar_label: false,
      dropdown_label: 0,
      dropdown_zIndex: 1005,
      dropdown_label:{
        top: 0,
        left: 0
      },
      dropdown_btn_email_data: false,
      selected_drpdwn: null,
      labels_options:[
        {id: 0, text: "Create new"}, 
        {id: 1, text: "Manage labels"},
        {id: 2, text: "Apply"},
      ],

      //Email preview data
      open: false,
      routes: null,
      csrf_token: null,
      user_email: null,
      headers: null,
      is_important: false,
      is_starred: false,
      email_action: null,
      show_reply: false,
      show_reply_all: false,
      show_attachment: false,
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
        { id: 1, text: "Reply All" },
        { id: 2, text: "Forward" },
        { id: 3, text: "Delete this message" },
        { id: 4, text: "Mark as unread" }
      ],

      //Header template data
      // show_loading: true,
      // loading: true,
      items_selected: true,
      items_unread_selected: false,
      read_tgl_button_tt_content: "",
      read_tgl_button_icon: "",
      // loading_duration: 15000,
      drop_down_items:[
        { id: 0, text: 'All' },
        { id: 1, text: 'None' },
        { id: 2, text: 'Read' },
        { id: 3, text: 'Unread' },
        { id: 4, text: 'Starred' },
        { id: 5, text: 'Unstarred' }
      ],
      more_items:[  
        { id: 0, text: "Mark all as read" }
      ],
      more_items_selected: [],
      snooze_opitons :[
        { id: 0, class: "data", text: "Later today", day_time: "6:00 PM", category: "Snooze until..." },
        { id: 1, class: "data", text: "Tommorow", day_time: moment().add(1,'days').format("ddd") + ", 8:00 AM", category: "Snooze until..." },
        { id: 2, class: "data", text: "This weekend", day_time: "Sat, 8:00 AM", category: "Snooze until..." },
        { id: 3, class: "data", text: "Next week", day_time: "Mon, 8:00 AM", category: "Snooze until..." },
        { id: 4, class: "data", text: "Pick date & time", category: "Snooze until..." }
      ],
      snooze_fields: { tooltip: 'text', text: 'text', groupBy: 'category' },
      snooze_template(){
        return{
          template: snooze_template
        }
      },
    };
  },

  components:{
    header_template,
    email_view_component,
  },

  directives: {
    ClickOutside
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

    selected_email_rowData:{
      get(){
        console.log(this.$store.state.selected_email_rowData);
        return this.$store.state.selected_email_rowData;
      },set(new_data){
        return this.$store.dispatch('set_selected_email_rowData', new_data);
      }
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

    message(){
      return this.$store.state.message;
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
        console.log('email_data');
        console.log(data);

        if(data !== null){
          this.email_date_display = formatDate(data.date);
          this.time_duration = moment(data.date, "YYYYMMDDhhmmss").fromNow();

          if(data.recipients.length == 1 && data.cc != null){ //1 recipient plus cc emails
              this.show_reply_all = true;
          }else if( data.recipients.length == 0 && data.cc != null){ //no recipient, more than 1 cc
          console.log(data.cc.match(/,/g).length);
              if(data.cc.match(/,/g).length >= 1)
                this.show_reply_all = true;
              }else{
                this.show_reply_all = false;
              }
          }else if(data.recipients.length > 1){ //more than 1 recipient
              this.show_reply_all = true;
          }else{
              this.show_reply_all = false;
          }

          if(this.show_reply_all){
              this.emailOpts = [
                { id: 0, text: "Reply" },
                { id: 1, text: "Reply All" },
                { id: 2, text: "Forward" },
                { id: 3, text: "Delete this message" },
                { id: 4, text: "Mark as unread" }
              ];

          }else{
              this.emailOpts = [
                { id: 0, text: "Reply" },
                { id: 2, text: "Forward" },
                { id: 3, text: "Delete this message" },
                { id: 4, text: "Mark as unread" }
              ];
          
        }

        return data;
      },
      set(new_data){
        return this.$store.dispatch('set_email_data', new_data);
      }
      
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

    user_labels_keyed(){
      return this.$store.state.user_labels_keyed;
    },

    viewEmailFull:{
      get(){
        console.log('get new data');
        return this.$store.state.viewEmailFull;
      },
      set(new_data){
        console.log('set new data');
        return this.$store.dispatch("set_viewEmailFull", new_data);
      }
    },

    selected_items(){
      return this.$store.state.selected_items_count
    },

    selected_items_dataID(){
      return this.$store.state.selected_items_dataID
    },

    selected(){
      return this.$store.state.selected_all_items;
    },

    user_labels_temp() {
      return this.user_labels_temporary
    },
  },

  mounted(){
    // this.$refs.header_comp.loading = false;
    // this.$refs.header_comp.items_selected = true;
    // this.$refs.header_comp.in_inbox = false;
    // this.$refs.header_comp.read_tgl_button_tt_content = !this.selected_email_rowData.read ? "Mark as read" : "Mark as unread";
    // this.$refs.header_comp.read_tgl_button_icon = !this.selected_email_rowData.read ? "fas fa-envelope-open-text" : "fas fa-envelope";
    
    this.read_tgl_button_tt_content = !this.selected_email_rowData.read ? "Mark as read" : "Mark as unread";
    this.read_tgl_button_icon = !this.selected_email_rowData.read ? "fas fa-envelope-open-text" : "fas fa-envelope";
    
    console.log("EMAIL FULL VIEW");
  },

  methods:{
    email_details(args){
      if(!this.selected_drpdwn){
        this.selected_drpdwn = 'email_details';
        this.dropdown_label.top = this.$refs.btn_email_data.$el.getBoundingClientRect().top + 43;
        this.dropdown_label.left = this.$refs.btn_email_data.$el.getBoundingClientRect().left;
        this.dropdown_zIndex++;
      }else{
        this.selected_drpdwn = null;
      }
    },

    emailOptions(args){
      if(!this.selected_drpdwn){
        this.selected_drpdwn = 'emailOptions';
        this.dropdown_label.top = this.$refs.btn_email_ops.$el.getBoundingClientRect().top + 43;
        this.dropdown_label.left = this.$refs.btn_email_ops.$el.getBoundingClientRect().left - 150;
        this.dropdown_zIndex++;
      }else{
        this.selected_drpdwn = null;
      }
    },

    searchInput(e) {
      const value = e.target.value
      this.search = value
      const result = this.user_labels.filter(word => word.text.includes(this.search))
      this.user_labels_temporary = result
    },

    //Email Preview methods
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

    hideShowCompose(){
      this.show_reply = !this.show_reply;
      console.log(this.show_reply);
    },

    replyEmail(){
      console.log("bruh");
      let _this = this;
      
      this.email_action = "reply_email";
      this.add_cc = false;
      this.add_bcc = false;
      let data = this.email_data;
      let from_email = this.email_data.from.email !== null ? this.email_data.from.email : this.email_data.from.name;

      if(data !== null){

        if(this.current_inbox.name === "SENT"){
          let reply_address = data.to.email ? data.to.email : data.to.name;
          this.email_addresses = [reply_address];

          this.email_address_tags = [{
            text: reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];
        }else{
          let reply_address = data.from.email ? data.from.email : data.from.name;
          this.email_addresses = [reply_address];

          this.email_address_tags = [{
            text: reply_address,
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
        this.$refs.reply_content.setContent(this.reply_content);
      }, 200);

      setTimeout(function() {
        _this.scrollToEnd();
      }, 0);
      
    },

    replyAllEmail(){
      let _this = this;
      this.show_reply = true;
      this.email_action = "reply_all_email";
      this.add_cc = true;
      this.add_bcc = true;
      let data = this.email_data;
      let from_email = this.email_data.from.email !== null ? this.email_data.from.email : this.email_data.from.name;
      this.change_subj = false;

      if(data !== null){

      //initialize addresses
      this.email_addresses = null;
      
        if(this.current_inbox.name === "SENT"){
          let reply_address = data.to.email ? data.to.email : data.to.name;
          this.email_addresses = [reply_address];

          this.email_address_tags = [{
            text: reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];

        }else{
          let reply_address = data.from.email ? data.from.email : data.from.name;
          this.email_addresses = [reply_address];

          this.email_address_tags = [{
            text: reply_address,
            classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
          }];
        }
        console.log('reply to:');
        console.log(this.email_addresses);

        if(data.cc != null){

          const cc_addresses_split = data.cc.split(",");
          this.cc_addresses = [];
          this.cc_address_tags = [];
          cc_addresses_split.forEach(element => {
            console.log(element);
            this.cc_addresses.push(element.trim());

            this.cc_address_tags.push({
                  text: element.trim(),
                  classes: "bg-pink-500 rounded-full px-3 justify-center items-center"
                });
            });
          
          console.log(this.cc_addresses);
        }    
      }

      if(data.cc != null){
          this.reply_content = "<div><br></div><div><br></div>" +
          "<div class='gmail_quote'>" +
            "<div dir='ltr'>On " + moment().format("LLLL") + " <<a href='mailto:" + from_email +"?cc="+ data.cc +"'>" + from_email + "</a>> wrote:</div>" +
            "<blockquote  style='margin: 0px 0px 0px 0.8ex;border-left: 1px solid rgb(204, 204, 204);padding-left: 1ex;'>" +
              this.email_body_html +
            "</blockquote>" +  
          "</div>";
      }else{
          this.reply_content = "<div><br></div><div><br></div>" +
          "<div class='gmail_quote'>" +
            "<div dir='ltr'>On " + moment().format("LLLL") + " <<a href='mailto:" + from_email +"'>" + from_email + "</a>> wrote:</div>" +
            "<blockquote  style='margin: 0px 0px 0px 0.8ex;border-left: 1px solid rgb(204, 204, 204);padding-left: 1ex;'>" +
              this.email_body_html +
            "</blockquote>" +  
          "</div>";
      }
      
      
      setTimeout(()=>{
        this.$refs.reply_content.setContent(this.reply_content);
      }, 200);

      setTimeout(function() {
        _this.scrollToEnd();
      }, 0);
      
    },

    forwardEmail(){
      let _this = this;
      this.email_action = "forward_email";
      this.add_cc = false;
      this.add_bcc = false;
      this.email_address_tags = [];
      this.email_addresses = null;
      let from_email = this.email_data.from.email !== null ? this.email_data.from.email : this.email_data.from.name;
      let to_email = this.email_data.to.email ? this.email_data.to.email : this.email_data.to.name;

      console.log(this.email_data.from.name);
      console.log(from_email);

      let forward_msg_template = "<div><br></div><div><br></div>" +
        "<div>On " + moment().format("LLLL") + " < " + this.user_email + " > wrote:</div>" +
        "<div>---------- Forwarded message ---------</div>" +
        "<div>From: &lt;" + from_email + "&gt;</div>" +
        "<div>Date: " + moment(this.email_data.date).format("llll") + "</div>" +
        "<div>To: &lt;" + to_email + "&gt;</div>"

      console.log( forward_msg_template);

      if(this.email_data.cc !== null){
        let cc_email = this.email_data.cc;
        cc_email = cc_email.replace(/"/g, "");
        cc_email = cc_email.replace(/</g, "&lt;");
        cc_email = cc_email.replace(/>/g, "&gt;");

        forward_msg_template = forward_msg_template + "<div>Cc: " + cc_email + "</div>"
      }

      forward_msg_template = forward_msg_template + "<div><br></div><div><br></div>" + this.email_body_html;
      setTimeout(() => this.$refs.reply_content.setContent(forward_msg_template), 200);
      
      setTimeout(function() {
        _this.scrollToEnd();
      }, 0);
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
        message: this.$refs.reply_content.getContent(),
        attachments: attachments,
      }

      axios.post(this.routes.send_mail, data)
          .then((response) => {
            let message = (_this.email_action === "reply_email" || _this.email_action === "reply_all_email") ? "Reply Sent" : "Email Forwarded";
            _this.$notification.success(message, {  timer: 5 });
            this.show_reply = false;
          }).catch(error => {
            console.log(error);
            _this.$notification.error("something went wrong", {  timer: 5 });
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
        _this.$notification.error("something went wrong", {  timer: 5 });
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
        _this.$notification.error("something went wrong", {  timer: 5 });
      });
    },

    moreEmailOpts(args){
      console.log('moreEmailOpts');
      console.log(args);
      console.log(args.item.id);
      console.log(args.item.id.substr(1));
      let _this = this;
      switch (parseInt(args.item.id.substr(1))) {
        case 0:
          //Reply
          this.hideShowCompose(); 
          this.replyEmail();
          break;

        case 1:
          console.log('replyAll');
          //Reply All
          this.replyAllEmail();
          break;

        case 2:
          //Forward
          this.hideShowCompose();
          this.forwardEmail();
          break;

        case 3:
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
            _this.$notification.error("something went wrong", {  timer: 5 });
          });
          break;

        case 4:
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
            _this.$notification.error("something went wrong", {  timer: 5 });
          });
          break;
      
        default:
          this.$notification.error("something went wrong", {  timer: 5 });
          break;
      }
      this.selected_drpdwn = null;
    },

    emailView(){
      console.log("viewEmailFull-popOut");
      this.viewEmailFull = true;
    },

    //Header methods
    drpdwn_click_out(){
      console.log("drpdwn_click_out");
      this.selected_drpdwn = null;
    },

    dropdownAction(args) {
      console.log(args)
      if(this.selected_items_dataID.length > 1) {
        this.$store.dispatch("set_actions", {
          read: false,
          important: false,
          starred: false
        })
        this.more_items_selected = [
          { id: 0, text: "Mark as read" },
          { id: 1, text: "Mark as unread" },
          { id: 2, text: "Mark as important" },
          { id: 3, text: "Mark as not important" },
          { id: 4, text: "Add star" },
          { id: 5, text: "Remove star" },
          // { id: 6, text: "Mute" },
          // { id: 7, text: "Forward as attachment" }
        ]
      } else {
        let objRead = {}
        let objImportant = {}
        let objStarred = {}
        if(this.$store.state.actions.read) {
          objRead = { id: 1, text: "Mark as unread" }
        } else {
          objRead = { id: 0, text: "Mark as read" }
        }

        if(this.$store.state.actions.important) {
          objImportant = { id: 3, text: "Mark as not important" }
        } else {
          objImportant = { id: 2, text: "Mark as important" }
        }
        
        if(this.$store.state.actions.starred) {
          objStarred = { id: 5, text: "Remove star" }
        } else {
          objStarred = { id: 4, text: "Add star" }
        }

        this.more_items_selected = [
          objRead,
          objImportant,
          objStarred
        ]
      }
    },

    selectedItemsTo(option, dataIDs, route) {
      let _this = this;

      // this.show_loading = true;
      // this.loading = true;
      axios.get(route.set_many_route, {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer {{ csrf_token() }}"
        },
        params: {
          option: option,
          dataIDs: dataIDs,
          current_inbox_id: this.current_inbox.id
        }
      }).then(function (response) {
        _this.$refs.refresh_progress.click();

      }).catch(error => {
        console.log(error);
        _this.$notification.error("somthing went wrong", {  timer: 5 });
      });
    },

    dropDownSelect(args){
      this.$eventHub.$emit("select_by", {
        id: args.item.properties.id, 
        text: args.item.properties.text
      });
    },

    // onProgress(args){
    //   if(this.loading){
    //     args.percent = 0;
    //     this.loading_duration = this.loading_duration + 100;
    //   }else{
    //     args.percent = 100;
    //     this.loading = false;
    //   }
    // },


    btnArchive(){
      console.log("Move selected emails to archive");
      console.log(this.selected_items_dataID);
      this.selectedItemsTo(8, this.$store.state.selected_items_dataID, this.$store.state.routes);
    },

    btnReportSpam(){
      console.log("Report selected emails as spam");
    },

    btnDelete(){
      console.log("Delete selected emails");
      this.selectedItemsTo(9, this.$store.state.selected_items_dataID, this.$store.state.routes);
    },

    btnToggleRead(){
      console.log(this.items_unread_selected ? "Mark selected emails as read" : "Mark selected emails as unread");
      if(this.items_unread_selected){
        this.selectedItemsTo(0, this.$store.state.selected_items_dataID, this.$store.state.routes);
      }else{
        this.selectedItemsTo(1, this.$store.state.selected_items_dataID, this.$store.state.routes);
      }

      this.$refs.refresh_progress.click();
    },

    btnSnooze(){
      console.log("Snooze selected emails");
    },

    btnMove(){
      let _this = this;
      this.$eventHub.$emit("show_custom_dropdown", {
        button: "btn_move",
        top: this.$refs.btn_move.$el.getBoundingClientRect().top,
        left: this.$refs.btn_move.$el.getBoundingClientRect().left
      });

      setTimeout(function() {
        _this.$store.dispatch("dropdown_btn_mv_toggle", !this.dropdown_btn_mv);
        _this.$store.dispatch("set_dropdown_menu_opened", "dropdown_btn_mv_toggle");
      }, 0);
    },

    btnLabels(){
      let _this = this;
      this.$eventHub.$emit("show_custom_dropdown", {
        button: "btn_labels",
        top: this.$refs.btn_labels.$el.getBoundingClientRect().top,
        left: this.$refs.btn_labels.$el.getBoundingClientRect().left
      });

      setTimeout(function() {
        _this.$store.dispatch("dropdown_btn_lbl_toggle", !this.dropdown_btn_lbl);
        _this.$store.dispatch("set_dropdown_menu_opened", "dropdown_btn_lbl_toggle");
      }, 0);
      
    },

    btnMore(){
      console.log("Show more options on selected emails");
    },

    snoozeSelect(args){
      this.$refs.snooze_listView.selectItem();
      if(args.data.text === "Pick date & time"){
        this.$eventHub.$emit("show_datepick_modal", {
          data: "show_datepick_modal modal open"
        });
      }
    },

    selected_all_items(){
      this.$store.dispatch("set_selected_all_items", true);
    },

    clear_selected_items(){ 
      this.$store.dispatch("set_selected_all_items", false);
    },
    
    markAllRead(){
      let unread_batch = [];

      this.email_batch.forEach(email_batch => {
        if(email_batch.read === false){
          unread_batch.push(email_batch.id);
        }
      });

      if (unread_batch === undefined || unread_batch.length == 0) {
        this.$notification.dark("No unread messages", {  timer: 5 });
      }else{
        this.selectedItemsTo(0, unread_batch, this.$store.state.routes);
      }
    },

    moreOptions(args){
      switch (args.data.id) {
        case 0:
          //Mark as read
          console.log("Mark as read");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 1:
          //Mark as unread
          console.log("Mark as unread");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 2:
          //Mark as important
          console.log("Mark as important");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 3:
          //Mark as not important
          console.log("Mark as not important");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 4:
          //Add star
          console.log("Add star");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 5:
          //Remove star
          console.log("Remove star");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 6:
          //Mute - disabled
          console.log("Mute");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
        
        case 7:
          //Forward as attachment - disabled
          console.log("Forward as attachment");
          this.selectedItemsTo(args.data.id, this.$store.state.selected_items_dataID, this.$store.state.routes);
        break;
      
        default:
          console.log("somthing went wrong!");
        break;
      }

      this.$refs.refresh_progress.click();
    }
  }
})
</script>
