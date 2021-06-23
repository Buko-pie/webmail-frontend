<template>
<div id="inbox_pagination" class="flex p-1">
  <ejs-tooltip content="Newest" position="BottomCenter">
    <ejs-button @click.native="btnNewest" iconCss="fas fa-angle-double-left" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <ejs-tooltip content="Previous" position="BottomCenter">
    <ejs-button @click.native="btnPrev" iconCss="fas fa-angle-left" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <ejs-tooltip content="Next" position="BottomCenter">
    <ejs-button @click.native="btnNext" iconCss="fas fa-angle-right" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <ejs-tooltip content="Oldest" position="BottomCenter">
    <ejs-button @click.native="btnOldest" iconCss="fas fa-angle-double-right" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <div class="flex items-center justify-center p-2 ml-auto">
    <p class="text-sm">1 - 50 (100 items)</p>
  </div>
</div>
</template>

<script>
export default({
  name: "PaginationTemplate",
  data(){
    return{
      grid: null,
    };
  },

  mounted(){
    console.log("pagination mounted");
  },
  
  methods:{
    btnNewest(){
      console.log("btnNewest");
    },

    btnPrev(){
      console.log("btnPrev");
    },

    btnNext(){
      console.log("btnNext");
      let grid = this.$el.closest(".e-inbox-display").ej2_instances;
      /////////////////////////////////
      console.log(grid);
      // this.$eventHub.$emit("page_next");
    },

    btnOldest(){
      console.log("btnOldest");
    }
  },

  created(){
    this.$eventHub.$on("pagination_test", (e)=>{
      console.log("at max page");
    });
  }
});
</script>
