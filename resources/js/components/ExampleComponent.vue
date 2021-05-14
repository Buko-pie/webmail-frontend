<template>
  <div>
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

let starred_template = Vue.component("starred_template", require("./subcomponents/StarredTemplate.vue").default);
let important_template = Vue.component("important_template", require("./subcomponents/ImportantTemplate.vue").default);
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
          template: starred_template
        }
      },
      important_template: function(){
        return{
          template: important_template
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
  @import url(https://cdn.syncfusion.com/ej2/material.css);
</style>
