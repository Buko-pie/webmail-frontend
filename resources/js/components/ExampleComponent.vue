<template>
  <div id="app">
    <ejs-grid :dataSource="localData" :selectionSettings='selectionSettings'>
      <e-columns>
        <e-column type='checkbox' :allowFiltering='false' :allowSorting='false' width='45'></e-column>
        <e-column field='starred' headerText='' width='30' :template='starred_template'></e-column>
        <e-column field='important' headerText='' width='40' :template='important_template'></e-column>
        <e-column field='sender' headerText='' minWidth="100" width="150" maxWidth="160"></e-column>
        <e-column field='message' headerText=''></e-column>
        <e-column field='date' headerText='' text-align="Right"></e-column>
      </e-columns>
    </ejs-grid>
  </div>
</template>

<script>
import Vue from "vue";
import { GridPlugin } from "@syncfusion/ej2-vue-grids";
Vue.use(GridPlugin);

export default{
  data(){
    return{
      localData: [
        { starred: true, important: true, sender: "Gmail", message: "test1", date: "May 1", read: false},
        { starred: false, important: false, sender: "Test1", message: "test2", date: "May 2", read: false },
        { starred: true, important: false, sender: "John Doe", message: "test3", date: "May 3", read: false },
        { starred: false, important: true, sender: "Emily Doe", message: "test4", date: "May 4", read: false },
        { starred: true, important: false, sender: "James Baxter", message: "test5", date: "May 5", read: false },
      ],
      filter: {
          type: 'CheckBox'
      },
      starred_template: function(){
        return{
          template: Vue.component('starred_template', {
              template: `
                <div id="starred" :class="[data.starred ? 'text-yellow-500' : 'text-gray-500']" class="items-center justify-center pb-0.5">
                  <div v-if="data.starred">
                    <button v-on:click="data.starred = !data.starred" class="p-1 rounded-full hover:bg-gray-300 focus:outline-none">
                      <i class="fas fa-star text-base"></i>
                    </button>
                  </div>
                  <div v-else>
                    <button v-on:click="data.starred = !data.starred" class="p-1 rounded-full hover:bg-gray-300 focus:outline-none">
                      <i class="far fa-star text-base"></i>
                    </button>
                  </div>
                </div>
             `,
              data: function () { return { data: {}, active: 'Active', inactive: 'Inactive' }; }
          })
        }
      },
      important_template: function(){
        return{
          template: Vue.component('important_template', {
              template: `
                <div id="important" :class="[data.important ? 'text-yellow-500' : 'text-gray-500']" class="items-center justify-center ">
                  <div v-if="data.important">
                    <button v-on:click="data.important = !data.important" class="px-1.5 py-1 rounded-full hover:bg-gray-300 focus:outline-none">
                      <i class="fas fa-thumbtack text-base"></i>
                    </button>
                  </div>
                  <div v-else>
                    <button v-on:click="data.important = !data.important" class="px-1.5 py-1 rounded-full hover:bg-gray-300 focus:outline-none">
                      <i class="fas fa-thumbtack text-base"></i>
                    </button>
                  </div>
                </div>
             `,
              data: function () { return { data: {}, active: 'Active', inactive: 'Inactive' }; }
          })
        }
      },
      selectionSettings: { persistSelection: true, type: 'Multiple', checkboxOnly: true }
    }
  },
  
  mounted(){
    console.log("vue-grids mounted");
  }
}
</script>

<style>
  .e-grid .e-rowcell {
    padding: 6px 6px;
  }
  .e-grid .e-rowcell .e-checkbox-wrapper, .e-grid .e-rowcell .e-css.e-checkbox-wrapper {
    height: auto !important;
    line-height: 20px;
  }
  .e-gridchkbox {
    align-items: center;
  }
  @import url(https://cdn.syncfusion.com/ej2/material.css);
</style>
