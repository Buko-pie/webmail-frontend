<template>
<div class="relative">
  <div class="e-input-group" :class="{ 'e-input-focus': e_inputs[0].is_focused }"> 
    <span id="searchbar_icon" @mousedown="icon_button_md(0)" @mouseup="icon_button_mu(0)" :class="{'e-input-btn-ripple': e_buttons[0].is_clicked}" class="e-input-group-icon e-input-picture"><i class="w-4 text-lg fas fa-search mr-2"></i></span>
    <input id="searchbar" @focus="inputFocus(0)" @blur="inputBlur(0)" @change="searchInput" v-model="search" class="e-input e-textbox" type="text" placeholder="Search">
    <span id="show_filters_icon" @click="showFilters" @mousedown="icon_button_md(1)" @mouseup="icon_button_mu(1)" :class="{'e-input-btn-ripple': e_buttons[1].is_clicked}" class="e-input-group-icon e-input-calendar"><i class="fas fa-caret-down"></i></span>
  </div>

  <!-- Filters -->
  <div id="filters_opton" class="hidden absolute w-full shadow-black bg-white top-8 z-50">
    <div class="grid grid-cols-5 gap-4 m-4">

      <!-- Filter by Sender -->
      <div class="flex flex-wrap content-center"><p class="text-sm">From</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[1].is_focused }">
        <!-- <input id="filter_from" v-model="fromTextHolder" @focus="inputFocus(1)" @blur="inputBlur(1)" class="e-input e-textbox" type="text" placeholder=""> -->

        <vue-simple-suggest 
            v-model="fromText" 
            ref="fromValue" 
            :list="autocompleteItems" 
            :styles="autoCompleteStyle" 
            @focus="inputFocus(1)" 
            @blur="inputBlur(1)" 
            @input="getEmails(fromText, 'from')" 
            @suggestion-click="formatInput('from')"
            :remove-list="showList"
            :controls="controls"
        ></vue-simple-suggest>
      </div>

      <!-- Filter by Receiver -->
      <div class="flex flex-wrap content-center"><p class="text-sm">To</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[2].is_focused }">
        <!-- <input id="filter_to" v-model="toText" @focus="inputFocus(2)" @blur="inputBlur(2)" class="e-input e-textbox" type="text" placeholder=""> -->

        <vue-simple-suggest 
            v-model="toText" 
            ref="toValue" 
            :list="autocompleteItems" 
            :styles="autoCompleteStyle" 
            @focus="inputFocus(2)" 
            @blur="inputBlur(2)" 
            @input="getEmails(toText, 'to')" 
            @suggestion-click="formatInput('to')"
            :remove-list="showList"
            :controls="controls"
        ></vue-simple-suggest>
      </div>

      <!-- Filter by Subject -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Subject</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[3].is_focused }">
        <input id="filter_subject" v-model="subjectText" @focus="inputFocus(3)" @blur="inputBlur(3)" class="e-input e-textbox" type="text" placeholder="">
      </div>
      
      <!-- including words -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Has the words</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[4].is_focused }">
        <input id="filter_include_words" v-model="includeText" @focus="inputFocus(4)" @blur="inputBlur(4)" class="e-input e-textbox" type="text" placeholder="">
      </div>

      <!-- excluding words -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Doesn't have</p></div>
      <div class="e-input-group col-span-4 " :class="{ 'e-input-focus': e_inputs[5].is_focused }">
        <input id="filter_exclude_words" v-model="excludeText" @focus="inputFocus(5)" @blur="inputBlur(5)" class="e-input e-textbox" type="text" placeholder="">
      </div>

      <!-- Size filter -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Size</p></div>
      <div class="col-span-2">
        <ejs-dropdownlist v-model="filterSize_op" id='filter_size_op' :dataSource="size_ops" :fields="data_fields"></ejs-dropdownlist>
      </div>
      <div class="e-input-group col-span-1" :class="{ 'e-input-focus': e_inputs[6].is_focused }">
        <input id="filter_size" v-model="filterSize" @focus="inputFocus(6)" @blur="inputBlur(6)" class="e-input e-textbox" type="text" placeholder="">
      </div>
      <div class="col-span-1">
        <ejs-dropdownlist id='filter_size_type' v-model="filterSizeMetric" :dataSource="size_types" :fields="data_fields"></ejs-dropdownlist>
      </div>

      <!-- Filter By Date -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Date within</p></div>
      <div class="col-span-2">
        <ejs-dropdownlist id='filter_time_period' v-model="dateScale" :dataSource="time_period" :fields="data_fields" :index="0" ></ejs-dropdownlist>
      </div>
      <div class="col-span-2">
        <ejs-datepicker id="date_picker" v-model="datePicker" :placeholder="'Choose a date'"></ejs-datepicker>
      </div>

      <!-- Filter By Inboxes/Labels -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Search</p></div>
      <div class="col-span-4">
        <ejs-dropdownlist id='filter_mail_types' v-model="searchIn" :dataSource="mail_types" :fields="data_fields" :user_labels="user_labels" ></ejs-dropdownlist>
      </div>

      <!-- Filter By Checkbox -->
      <div class="col-span-5">
        <ejs-checkbox id="has_attachment" v-bind:label="'Has attachment'" v-model="has_attachment"></ejs-checkbox>
      </div>

      <div class="col-span-5 flex justify-end">
        <ejs-button class="mr-3">Create Filter</ejs-button>
        <ejs-button :isPrimary="true" @click.native="searchBtn">Search</ejs-button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Vue from "vue";
import moment from "moment";
import VueSimpleSuggest from 'vue-simple-suggest'
import 'vue-simple-suggest/dist/styles.css'

import { TextBoxPlugin } from '@syncfusion/ej2-vue-inputs';
import { DropDownListPlugin } from "@syncfusion/ej2-vue-dropdowns";
import { DatePickerPlugin } from "@syncfusion/ej2-vue-calendars";
import { CheckBoxPlugin } from "@syncfusion/ej2-vue-buttons";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";
import {bus} from "../app";

Vue.use(TextBoxPlugin);
Vue.use(DropDownListPlugin);
Vue.use(DatePickerPlugin);
Vue.use(CheckBoxPlugin);
Vue.use(ButtonPlugin);
Vue.component('vue-simple-suggest', VueSimpleSuggest)

export default Vue.extend({
  name: "SearchBarComponent",
  data() {
    return {
      fromText: '',
      toText: '',
      subjectText: '',
      includeText: "",
      excludeText: "",
      filterSize: "",
      filterSize_op: "larger",
      filterSizeMetric: "M",
      datePicker: null,
      dateScale: 0,
      searchIn: 0,
      search: '',
      routes:'',
      csrf_token:'',
      user_email:'',
      headers:'',
      autocompleteItems:[],
      debounce:null,
      autoCompleteStyle:{
        vueSimpleSuggest: "auto-suggest",
        inputWrapper: "e-input e-textbox auto-suggest",
        defaultInput : "auto-suggest",
        suggestions: "",
        suggestItem: ""
      },
      controls:{
        selectionUp: [38],
        selectionDown: [40],
        select: [13],
        showList: [40],
        hideList: [27],
        autocomplete: [32, 13]
      },
      showList: false,
      fromTextHolder: [],
      toTextHolder: [],
      is_focused: false,
      show_filters: false,
      has_attachment: false,

      size_ops:[ 
        {id: "larger", option: "greater than"},
        {id: "smaller", option: "less than"}
      ],
      size_types:[
        {id: "M", option: "MB"},
        {id: "K", option: "KB"},
        {id: "", option: "Bytes"}
      ],
      time_period:[
        {id: 0, option: "1 day", period:1, metric:"d"},
        {id: 1, option: "3 days", period:3, metric:"d"},
        {id: 2, option: "1 week", period:1, metric:"w"},
        {id: 3, option: "2 weeks", period:2, metric:"w"},
        {id: 4, option: "1 month", period:1, metric:"M"},
        {id: 5, option: "2 months", period:2, metric:"M"},
        {id: 6, option: "6 months", period:6, metric:"M"},
        {id: 7, option: "1 year", period:1, metric:"y"}
      ],
      mail_types:[
        {id: 0, option: "All Mail", op:""},
        {id: 1, option: "Inbox", op:"in:inbox"},
        {id: 2, option: "Starred", op:"is:starred"},
        {id: 3, option: "Important", op:"is:important"},
        {id: 4, option: "Sent", op:"is:sent"},
        {id: 5, option: "Drafts", op:"in:drafts"},
        {id: 6, option: "Spam", op:"in:spam"},
        {id: 7, option: "Trash", op:"in:trash"},
        {id: 8, option: "Mail & Spam & Trash", op:"in:anywhere"},
        {id: 9, option: "Read Mail", op:"is:read"},
        {id: 10, option: "Unread Mail", op:"is:unread"},
      ],
      e_inputs:[
        {id: 0, is_focused: false, title: 'search'},
        {id: 1, is_focused: false, title: 'from'},
        {id: 2, is_focused: false, title: 'to'},
        {id: 3, is_focused: false, title: 'subject'},
        {id: 4, is_focused: false, title: 'include_words'},
        {id: 5, is_focused: false, title: 'exclude_words'},
        {id: 6, is_focused: false, title: 'size'}
      ],
      e_buttons:[
        {id: 0, is_clicked: false, icon: 'search'},
        {id: 1, is_clicked: false, icon: 'show_filter'}
      ],
      data_fields: {value: "id", text: "option"}
     }
  },
  computed:{
    user_labels(){
      let labels =  this.$store.state.user_labels;
      if(labels !== null){
        labels.forEach(label => {
          let lbl = label.text.toLowerCase().replace(/\//g, "-");
          lbl = lbl.replace(/\s/g, "-");
          this.mail_types.push(
            {
              id: this.mail_types.length,
              option: label.text,
              op: "label:" + lbl,
            }
          )
        });
      }
      return labels
    }
  },
  mounted(){
    this.routes = this.$store.state.routes;
    this.csrf_token = this.$store.state.csrf_token;
    this.user_email = this.$store.state.user_email;
    this.headers = {
      "Content-Type": "multipart/mixed",
      "Authorization": "Bearer " + this.csrf_token,
      "X-CSRF-TOKEN": this.csrf_token
    }

    // this.getSuggestionList();

    bus.$on('addFilterToSearch', (data) => {
      console.log(data)
      var filter = data.toLowerCase();
      const isOptions = ['starred', 'important']
      const inOptions = ['sent', 'draft', 'trash']
      const categoryOptions = ['social', 'updates', 'forums', 'promotions']

      if(isOptions.includes(filter)){
        this.search = 'is:'+filter;
      }else if(inOptions.includes(filter)){
        this.search = 'in:'+filter;
      }else if(categoryOptions.includes(filter)){
        this.search = 'category:'+filter;
      }else{
        this.search = '';
      }
      
    })
  },
  methods: {
    inputFocus(index){
      this.e_inputs[index].is_focused = true;
      this.showList= false
    },

    inputBlur(index){
      this.e_inputs[index].is_focused = false;
    },

    icon_button_md(index){
      this.e_buttons[index].is_clicked = true;
    },

    icon_button_mu(index){
      setTimeout(() => this.e_buttons[index].is_clicked = false, 500);
    },

    showFilters(){
      this.show_filters = !this.show_filters;
      let filters = document.getElementById("filters_opton")

      if(this.show_filters){
        filters.classList.remove("hidden")
      }else{
        filters.classList.add("hidden")
      }
      
    },

    searchInput() {
      let a = this.search.split(' ')
      let command = ""
      let query = this.search;
      // a.forEach((element,index) => {
      //   let extracted_command = this.search.split(':')
      //   if(extracted_command[0] === "in" || extracted_command[0] === "label") {
      //     command = extracted_command[0]
      //     query = extracted_command[1]
      //   }
      // });
      this.$store.dispatch("set_search", this.search)
      this.$eventHub.$emit("search_inbox", {
        event: "search_inbox",
        command,
        query
      });
      console.log(this.search)
    },

    searchBtn() {
      this.showFilters()
      this.search = `${this.mail_types[this.searchIn].op} `;
      let command = ""

      if(this.fromText.length > 0) {
        // if(this.fromText.split(' ').length > 1) {
        //   this.search = `from:(${this.fromText})`
        //   command = `in:drafts from:(${this.fromText})`
        // } else {
        //   this.search = `from:${this.fromText}`
        //   command = `in:drafts from:${this.fromText}`
        // }
        this.search += `from:(${this.fromText}) `;
      }

      if(this.toText.length > 0) {
        // if(this.toText.split(' ').length > 1) {
        //   this.search = `${this.search} to:(${this.toText})`
        //   command = `${this.search} in:sent to:(${this.toText})`
        // } else {
        //   this.search = `${this.search} in:sent to:${this.toText}`
        //   command = `${this.search} to:${this.toText}`
        // }
        this.search += `to:(${this.toText}) `;
      }
      
      if(this.subjectText.length > 0) {
        // if(this.subjectText.split(' ').length > 1) {
        //   this.search = `${this.search} subject:(${this.subjectText})`
        // } else {
        //   this.search = `${this.search} subject:${this.subjectText}`
        // }

        this.search += `subject:${this.subjectText} `;
      }

      if(this.includeText.length > 0) {
        this.search += `${this.includeText} `;
      }

      if(this.excludeText.length > 0) {
        this.search += `-${this.excludeText} `;
      }

      if(this.filterSize.length > 0) {
        this.search += `${this.filterSize_op}:${this.filterSize}${this.filterSizeMetric} `;
      }

      if(this.datePicker !== null){
        let after = moment(this.datePicker).subtract(this.time_period[this.dateScale].period, this.time_period[this.dateScale].metric).format("YYYY/MM/DD");
        let before = moment(this.datePicker).add(this.time_period[this.dateScale].period, this.time_period[this.dateScale].metric).format("YYYY/MM/DD");
        
        this.search += `after:${after} before:${before} `;
      }

      if(this.search.length > 0) {
        // const command = this.search.split(':')[0]
        // this.$store.dispatch("set_search_command", command)
        this.$store.dispatch("set_search_command", this.search)
      }

      this.search = this.search.slice(0, -1);
      console.log(this.search);
      this.searchInput()
    },

    formatInput(type){

      if(type=='from'){
        clearTimeout(this.debounce);
        this.debounce = setTimeout(() => {

          this.fromTextHolder = this.fromTextHolder.filter(e =>  e);
          this.fromTextHolder.push(this.$refs.fromValue.selected)
          this.fromTextHolder.push('')
          this.fromText = this.fromTextHolder.toString()
          this.showList = true
        }, 100);
      }else{
        clearTimeout(this.debounce);
        this.debounce = setTimeout(() => {

          this.toTextHolder = this.toTextHolder.filter(e =>  e);
          this.toTextHolder.push(this.$refs.toValue.selected)
          this.toTextHolder.push('')
          this.toText = this.toTextHolder.toString()
          this.showList = true
        }, 100);
      }
      
    },

    getEmails(value, type){
      this.showList= false
      value = value.slice(value.lastIndexOf(',') + 1);
      const _this = this
      axios.get(this.routes.getContactSearch,
      {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          pageSize: 50,
          query: value
        },
      }).then(function(response){

        const emails = response.data;
        var results = []

        var address = Object.values(emails)

        results = address.filter(element => element.includes(value))
        _this.autocompleteItems = []
        for (let x in results) {
          _this.autocompleteItems.push(results[x])
        }

        if(type == 'from'){
          _this.$refs.fromValue.suggestions = _this.autocompleteItems
          _this.$refs.fromValue.showSuggestions()
        }else{
          _this.$refs.toValue.suggestions = _this.autocompleteItems
          _this.$refs.toValue.showSuggestions()
        }

      }).catch(error => {
        this.$notification.error("Something went wrong", {  timer: 5 });
        console.log(error)
      })
    },

    getSuggestionList(){
      const _this = this

      axios.get(this.routes.getContactList,
      {
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer " + this.csrf_token,
          "X-CSRF-TOKEN": this.csrf_token
        },
        params: {
          pageSize: 50,
          pageToken: '',
          requestSyncToken: '',
          syncToken:''
        },
      }).then(function(response){

        const emails = response.data;

        for (let x in emails) {
          _this.autocompleteItems.push(emails[x])
        }

      }).catch(error => {
        this.$notification.error("Something went wrong", {  timer: 5 });
        console.log(error)
      })
    }
  }
});


</script>

