<template>
<div class="relative">
  <div class="e-input-group" :class="{ 'e-input-focus': e_inputs[0].is_focused }"> 
    <span id="searchbar_icon" @mousedown="icon_button_md(0)" @mouseup="icon_button_mu(0)" :class="{'e-input-btn-ripple': e_buttons[0].is_clicked}" class="e-input-group-icon e-input-picture"><i class="h-4 w-4 text-lg fas fa-search mr-2"></i></span>
    <input id="searchbar" @focus="inputFocus(0)" @blur="inputBlur(0)" class="e-input e-textbox" type="text" placeholder="Search">
    <span id="show_filters_icon" @click="showFilters" @mousedown="icon_button_md(1)" @mouseup="icon_button_mu(1)" :class="{'e-input-btn-ripple': e_buttons[1].is_clicked}" class="e-input-group-icon e-input-calendar"><i class="fas fa-caret-down"></i></span>
  </div>

  <!-- Filters -->
  <div id="filters_opton" class="hidden absolute w-full shadow-black bg-white top-8 z-50">
    <div class="grid grid-cols-5 gap-4 m-4">

      <!-- Filter by Sender -->
      <div class="flex flex-wrap content-center"><p class="text-sm">From</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[1].is_focused }">
        <input id="filter_from" @focus="inputFocus(1)" @blur="inputBlur(1)" class="e-input e-textbox" type="text" placeholder="">
      </div>

      <!-- Filter by Receiver -->
      <div class="flex flex-wrap content-center"><p class="text-sm">To</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[2].is_focused }">
        <input id="filter_to" @focus="inputFocus(2)" @blur="inputBlur(2)" class="e-input e-textbox" type="text" placeholder="">
      </div>

      <!-- Filter by Subject -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Subject</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[3].is_focused }">
        <input id="filter_subject" @focus="inputFocus(3)" @blur="inputBlur(3)" class="e-input e-textbox" type="text" placeholder="">
      </div>
      
      <!-- including words -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Has the words</p></div>
      <div class="e-input-group col-span-4" :class="{ 'e-input-focus': e_inputs[4].is_focused }">
        <input id="filter_include_words" @focus="inputFocus(4)" @blur="inputBlur(4)" class="e-input e-textbox" type="text" placeholder="">
      </div>

      <!-- excluding words -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Doesn't have</p></div>
      <div class="e-input-group col-span-4 " :class="{ 'e-input-focus': e_inputs[5].is_focused }">
        <input id="filter_exclude_words" @focus="inputFocus(5)" @blur="inputBlur(5)" class="e-input e-textbox" type="text" placeholder="">
      </div>

      <!-- Size filter -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Size</p></div>
      <div class="col-span-2">
        <ejs-dropdownlist id='filter_size_op' :dataSource="size_ops" :fields="data_fields" :index="0" ></ejs-dropdownlist>
      </div>
      <div class="e-input-group col-span-1" :class="{ 'e-input-focus': e_inputs[6].is_focused }">
        <input id="filter_size" @focus="inputFocus(6)" @blur="inputBlur(6)" class="e-input e-textbox" type="text" placeholder="">
      </div>
      <div class="col-span-1">
        <ejs-dropdownlist id='filter_size_type' :dataSource="size_types" :fields="data_fields" :index="0" ></ejs-dropdownlist>
      </div>

      <!-- Filter By Date -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Date within</p></div>
      <div class="col-span-2">
        <ejs-dropdownlist id='filter_time_period' :dataSource="time_period" :fields="data_fields" :index="0" ></ejs-dropdownlist>
      </div>
      <div class="col-span-2">
        <ejs-datepicker id="date_picker" :placeholder="'Choose a date'"></ejs-datepicker>
      </div>

      <!-- Filter By Types -->
      <div class="flex flex-wrap content-center"><p class="text-sm">Doesn't have</p></div>
      <div class="col-span-4">
        <ejs-dropdownlist id='filter_mail_types' :dataSource="mail_types" :fields="data_fields" :index="0" ></ejs-dropdownlist>
      </div>

      <!-- Filter By Checkbox -->
      <div class="col-span-5">
        <ejs-checkbox id="has_attachment" v-bind:label="'Has attachment'" v-model="has_attachment"></ejs-checkbox>
      </div>

      <div class="col-span-5 flex justify-end">
        <ejs-button class="mr-3">Create Filter</ejs-button>
        <ejs-button :isPrimary="true">Search</ejs-button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Vue from "vue";
import { TextBoxPlugin } from '@syncfusion/ej2-vue-inputs';
import { DropDownListPlugin } from "@syncfusion/ej2-vue-dropdowns";
import { DatePickerPlugin } from "@syncfusion/ej2-vue-calendars";
import { CheckBoxPlugin } from "@syncfusion/ej2-vue-buttons";
import { ButtonPlugin } from "@syncfusion/ej2-vue-buttons";

Vue.use(TextBoxPlugin);
Vue.use(DropDownListPlugin);
Vue.use(DatePickerPlugin);
Vue.use(CheckBoxPlugin);
Vue.use(ButtonPlugin);

export default Vue.extend({
  name: "SearchBarComponent",
  data() {
    return {
      is_focused: false,
      show_filters: false,
      has_attachment: false,
      size_ops:[
        {id: 0, option: "greater than"},
        {id: 1, option: "less than"}
      ],
      size_types:[
        {id: 0, option: "MB"},
        {id: 1, option: "KB"},
        {id: 2, option: "Bytes"}
      ],
      time_period:[
        {id: 0, option: "1 day"},
        {id: 1, option: "3 days"},
        {id: 2, option: "1 week"},
        {id: 3, option: "2 weeks"},
        {id: 4, option: "1 month"},
        {id: 5, option: "2 months"},
        {id: 6, option: "6 months"},
        {id: 7, option: "1 year"}
      ],
      mail_types:[
        {id: 0, option: "Inbox"},
        {id: 1, option: "Starred"},
        {id: 2, option: "Important"},
        {id: 3, option: "Sent"},
        {id: 4, option: "Drafts"},
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
  mounted(){
  },
  methods: {
    inputFocus(index){
      this.e_inputs[index].is_focused = true;
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
    getParentNode(element) {
      let parentNode = element.parentNode;
      if (parentNode.classList.contains('e-input-in-wrap')) {
        return parentNode.parentNode;
      }
      return parentNode;
    }
  }
});


</script>
