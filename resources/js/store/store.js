import axios from "axios";
import { truncate } from "lodash";
import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
Vue.config.devtools = true

function assign(obj, keyPath, value) {
  keyPath.forEach(path => {
    if (!(path in obj)){
      obj[path] = {_prop: value}
    }else{
      obj = obj[path];
    }
    
  });
  obj[keyPath[keyPath.length-1]] = {_prop: value};
}

export const store = new Vuex.Store({
  state:{
    message: "Vuex Active",
    counter: 0,
    routes: {},
    csrf_token: null,
    current_inbox: {name: 'INBOX', id: 'INBOX', type: 0}, // type 0 for inbox folders, 1 for labels
    email_batch: null,
    current_page: 0,
    max_page: 0,
    inbox_items: 1,
    inbox_total: 0,
    username: null,
    user_email: null,
    user_labels: null,
    user_labels_keyed: null,
    labels_tree: null,
    user_profile_photo: null,
    dropdown_menu_opened: null,
    dropdown_btn_lbl: false,
    dropdown_btn_mv: false,
    dropdown_btn_user: false,
    dropdown_btn_email_data: false,
    selected_items_count: 0,
    selected_all_items: false,
    selected_items_dataID: [],
    selected_item_unread: 0,
    selected_email_html_body: null,
    selected_email_data: null,
    selected_email_rowData: null,
    selected_email_attachments: null,
    splitter_height: null,
    splitter_pane_0_height: null,
    search: '',
    searchCommand: '',
    searchOptions: { fields: ['plain_text','message','labels','receiver','sender'], operator: 'contains', key: '', ignoreCase: true },
    actions: {},
    ids: [],
    rowSelected: [],
    viewEmailFull: false,
    current_subject: "New Message",

    //COMPONENTS REF
    headerTemplate: null,
    inboxDisplay: null,
    sidebar: null,
    emailView_full: null,
    overlays: [],
    overlay_header: null,
    overlay_body: null,
  },

  mutations:{
    set_routes(state, payload){
      state.routes = payload;
    },

    set_csrf_token(state, payload){
      state.csrf_token = payload;
    },

    set_current_inbox(state, payload){
      state.current_inbox = {name: payload.name, id: payload.id, type: payload.type};
    },

    set_email_batch(state, payload){
      state.email_batch = payload;
    },

    modify_email_batch(state, payload){
      state.email_batch[payload.index][payload.property] = payload.value;
    },

    set_current_page(state, payload){
      state.current_page = payload;
    },

    set_max_page(state, payload){
      state.max_page = payload;
    },

    set_inbox_items(state, payload){
      state.inbox_items = payload;
    },

    set_inbox_total(state, payload){
      state.inbox_total = payload
    },

    set_user_email(state, payload){
      state.user_email = payload
    },

    set_user_labels(state, payload){
      payload.sort((a,b) => (a.text > b.text) ? 1 : ((b.text > a.text) ? -1 : 0));
      
      let labelKey = [];
      let tree = {};
      
      //Labels dictionary Note: still need to revise to ids only, no other properties
      payload.forEach(label => {
        labelKey[label.id] = {
          name: label.text, 
          color: label.color.backgroundColor !== "#000000" ? label.color : { backgroundColor: "#d1d5db", textColor: "#000000" },
          messagesUnread: label.messagesUnread,
        };
        let labels = label.text.split("/");
        let value = {
          id: label.id,
          text: label.text,
        }
        if(labels.length > 1){
          
          assign(tree, labels, value);
        }else{ 
          tree[label.text] = {_prop: value};
        }
      });

      state.labels_tree = tree;
      state.user_labels_keyed = labelKey;
      state.user_labels = payload;
    },

    set_user_profile_photo(state, payload){
      state.user_profile_photo = payload;
    },

    set_selected_items_count(state, payload){
      state.selected_items_count = payload;
    },

    set_selected_all_items(state, payload){
      state.selected_all_items = payload
    },

    set_selected_items_dataID(state, payload){
      state.selected_items_dataID = payload;
    },

    set_email_html_body(state, payload){
      state.selected_email_html_body = payload;
    },

    set_email_data(state, payload){
      state.selected_email_data = payload;
    },

    set_email_rowData(state, payload){
      state.selected_email_rowData = payload;
    },

    set_email_attachments(state, payload){
      state.selected_email_attachments = payload;
    },

    set_dropdown_menu_opened(state, payload){
      state.dropdown_menu_opened = payload;
    },

    dropdown_btn_lbl_toggle(state, payload){
      state.dropdown_btn_lbl = payload;
    },

    dropdown_btn_mv_toggle(state, payload){
      state.dropdown_btn_mv = payload;
    },

    dropdown_btn_user_toggle(state, payload){
      state.dropdown_btn_user = payload;
    },

    dropdown_btn_email_data_toggle(state, payload){
      state.dropdown_btn_email_data = payload;
    },

    set_splitter_height(state, payload){
      state.splitter_height = payload;
    },

    set_splitter_pane_0_height(state, payload){
      state.splitter_pane_0_height = payload;
    },

    set_headerTemplate(state, payload){
      state.headerTemplate = payload;
    },

    set_inboxDisplay(state, payload){
      state.inboxDisplay = payload;
    },

    set_sidebar(state, payload){
      state.sidebar = payload;
    },

    set_emailView_full(state, payload){
      state.emailView_full = payload;
    },

    add_overlays(state, payload){
      state.overlays.push(payload);
    },

    set_overlay_header(state, payload){
      state.overlay_header = payload;
    },

    set_overlay_body(state, payload){
      state.overlay_body = payload;
    },

    set_search(state, payload){
      state.search = payload;
    },

    set_search_command(state, payload){
      state.searchCommand = payload;
    },

    set_searchOptions(state, payload){
      state.searchOptions.fields = payload;
    },

    set_actions(state, payload){
      state.actions = payload;
    },

    set_ids(state, payload){
      state.ids = payload;
    },

    set_rowSelected(state, payload){
      state.rowSelected = payload;
    },

    set_viewEmailFull(state, payload){
      state.viewEmailFull = payload;
    },

    set_current_subject(state, payload){
      state.current_subject = payload;
    }
  },

  getters:{
    Routes(state){
      return state.routes;
    }
  },

  actions:{
    set_routes(state, payload){
      state.commit("set_routes", payload);
    },

    set_csrf_token(state, payload){
      state.commit("set_csrf_token", payload);
    },

    set_current_inbox(state, payload){
      state.commit("set_current_inbox", payload);
    },

    set_email_batch(state, payload){
      state.commit("set_email_batch", payload);
    },

    modify_email_batch(state, payload){
      state.commit("modify_email_batch", payload);
    },

    set_current_page(state, payload){
      state.commit("set_current_page", payload);
    },

    set_max_page(state, payload){
      state.commit("set_max_page", payload)
    },

    set_inbox_items(state, payload){
      state.commit("set_inbox_items", payload);
    },

    set_inbox_total(state, payload){
      state.commit("set_inbox_total", payload);
    },

    set_user_email(state, payload){
      state.commit("set_user_email", payload);
    },

    set_user_labels(state, payload){
      state.commit("set_user_labels", payload);
    },

    set_user_profile_photo(state, payload){
      state.commit("set_user_profile_photo", payload);
    },

    set_selected_items_count(state, payload){
      state.commit("set_selected_items_count", payload);
    },

    set_selected_all_items(state, payload){
      state.commit("set_selected_all_items", payload);
    },

    set_selected_items_dataID(state, payload){
      state.commit("set_selected_items_dataID", payload);
    },

    set_email_html_body(state, payload){
      state.commit("set_email_html_body", payload);
    },

    set_email_data(state, payload){
      state.commit("set_email_data", payload);
    },

    set_email_rowData(state, payload){
      state.commit("set_email_rowData", payload);
    },

    set_email_attachments(state, payload){
      state.commit("set_email_attachments", payload);
    },

    set_dropdown_menu_opened(state, payload){
      state.commit("set_dropdown_menu_opened", payload);
    },

    dropdown_btn_lbl_toggle(state, payload){
      state.commit("dropdown_btn_lbl_toggle", payload);
    },

    dropdown_btn_mv_toggle(state, payload){
      state.commit("dropdown_btn_mv_toggle", payload);
    },

    dropdown_btn_user_toggle(state, payload){
      state.commit("dropdown_btn_user_toggle", payload);
    },

    dropdown_btn_email_data_toggle(state, payload){
      state.commit("dropdown_btn_email_data_toggle", payload);
    },

    set_splitter_height(state, payload){
      state.commit("set_splitter_height", payload);
    },

    set_splitter_pane_0_height(state, payload){
      state.commit("set_splitter_pane_0_height", payload);
    },

    set_headerTemplate(state, payload){
      state.commit("set_headerTemplate", payload);
    },

    set_inboxDisplay(state, payload){
      state.commit("set_inboxDisplay", payload);
    },

    set_sidebar(state, payload){
      state.commit("set_sidebar", payload);
    },

    set_emailView_full(state, payload){
      state.commit("set_emailView_full", payload);
    },

    add_overlays(state, payload){
      state.commit("add_overlays", payload);
    },

    set_overlay_header(state, payload){
      state.commit("set_overlay_header", payload);
    },

    set_overlay_body(state, payload){
      state.commit("set_overlay_body", payload);
    },

    set_search(state, payload){
      state.commit("set_search", payload);
    },

    set_search_command(state, payload){
      state.commit("set_search_command", payload);
    },

    set_searchOptions(state, payload){
      state.commit("set_searchOptions", payload);
    },
    
    set_actions(state, payload){
      state.commit("set_actions", payload);
    },

    set_ids(state, payload){
      state.commit("set_ids", payload);
    },

    set_rowSelected(state, payload){
      state.commit("set_rowSelected", payload);
    },

    set_viewEmailFull(state, payload){
      state.commit("set_viewEmailFull", payload);
    },

    set_current_subject(state, payload){
      state.commit("set_current_subject", payload);
    },

    data_toggle({state}, payload){
      if(payload){

        return axios.get(state.routes.toggle_route, {
          params: payload,
          headers:{
            "Content-Type": "application/json",
            "Authorization": "Bearer " + state.csrf_token,
            "X-CSRF-TOKEN": state.csrf_token
          }
        }).then((response) => {
          return response;
        }).catch(error => {
          throw error;
        });
      }else{
        return "Error Empty payload";
      }
    },

    data_toggle_many({state}, payload){
      if(payload){

        return axios.get(state.routes.set_many_route, {
          params: payload,
          headers:{
            "Content-Type": "application/json",
            "Authorization": "Bearer " + state.csrf_token,
            "X-CSRF-TOKEN": state.csrf_token
          }
        }).then((response) => {
          return response;
        }).catch(error => {
          throw error;
        });
      }else{
        return "Error Empty payload";
      }
    },

    goToLabel({state}, payload){
      if(payload){
        return axios.get(state.routes.data_route, {
          params: payload,
          headers:{
            "Content-Type": "application/json",
            "Authorization": "Bearer " + state.csrf_token,
            "X-CSRF-TOKEN": state.csrf_token
          }
        }).then((response) => {
          if(response.data.empty){
            state.email_batch = null;
            state.inbox_items = 0;
            state.inbox_total = 0;
            state.current_inbox = {name: payload.inbox, id: payload.label_id, type: 1};
          }else{
            return response;
          }
        }).catch(error => {
          throw error;
        });
      }else{
        return "Error Empty payload";
      }
    }
  },
});