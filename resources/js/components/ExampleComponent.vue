<template>
  <div>
    <ejs-grid 
      :dataSource="localData" 
      :selectionSettings="selectionSettings" 
      :allowPaging='true' 
      :allowSorting='true'
      :contextMenuItems="menuItems"
    >
      <e-columns>
        <e-column type='checkbox' :allowFiltering='false' :allowSorting='false' width='45'></e-column>
        <e-column field='id' headerText='' width='38' :template='starred_template' :visible='false'></e-column>
        <e-column field='starred' headerText='' width='38' :template='starred_template'></e-column>
        <e-column field='important' headerText='' width='40' :template='important_template'></e-column>
        <e-column field='sender' headerText='' minWidth="100" width="150" maxWidth="160"></e-column>
        <e-column field='message' headerText=''></e-column>
        <e-column field='date' headerText='' text-align="Right"></e-column>
      </e-columns>
    </ejs-grid>
    <!-- <ejs-contextmenu target='#target' :items='menuItems' :select='onSelect'></ejs-contextmenu> -->
    <button @click="Custom()">
      test
    </button>
  </div>
</template>

<script>
import Vue from "vue";
import { GridPlugin, ContextMenu, Sort, Edit, Page } from "@syncfusion/ej2-vue-grids";

let starred_template = Vue.component("starred_template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important_template", require("./subcomponents/ImportantTemplate.vue").default);
Vue.use(GridPlugin);

function Custom() {
  console.log("bruh");
}

export default{
  data(){
    return{
      localData: [
        { id: 0, starred: true, important: true, sender: "Gmail", message: "test1", date: "May 1", read: false},
        { id: 1, starred: false, important: false, sender: "Test1", message: "test2", date: "May 2", read: false },
        { id: 2, starred: true, important: false, sender: "John Doe", message: "test3", date: "May 3", read: false },
        { id: 3, starred: false, important: true, sender: "Emily Doe", message: "test4", date: "May 4", read: false },
        { id: 4, starred: true, important: false, sender: "James Baxter", message: "test5", date: "May 5", read: false },
      ],
      contextMenuItems: [
        'AutoFit', 
        'AutoFitAll', 
        'Custom',
        'SortAscending', 
        'SortDescending',
        'Copy', 
        'Edit', 
        'Delete', 
        'Save', 
        'Cancel',
        'FirstPage', 
        'PrevPage',
        'LastPage',
        'NextPage'
      ],
      menuItems:[
        {
          text: 'Label As'
        },
        {
          text: 'Copy'
        },
        {
          text: 'Paste'
        },
        {
          separator: true
        },
        {
          text: 'Font'
        },
        {
          text: 'Paragraph'
        }
      ],
      filter: {
        type: 'CheckBox'
      },
      starred_template: function(){
        return{
          template: starred_template
        }
      },
      important_template: function(){
        return{
          template: important_template
        }
      },
      context_menu: function(){
        return{
          template: context_menu
        }
      },
      selectionSettings: { checkboxOnly: true }
    }
  },

  mounted(){
    console.log("vue-grids mounted");
  },

  methods:{
    trunGreen: function(args){
      document.getElementById("target").classList.add("bg-green");
    },
    onSelect: function(args) {
      console.log("bruh");
      if(args.item.text === 'Custom') {
        console.log("bruh2");
      }
    },
    getContextMenu:function(args){
      console.log("bruh");
    },
    Custom(){
      console.log("bruh");
    }
  },

  provide: {
    grid: [ContextMenu, Sort, Edit, Page]
  }
}
</script>

<style>
  @import url(https://cdn.syncfusion.com/ej2/material.css);
</style>
