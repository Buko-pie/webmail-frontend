<template>
<div id="inbox_pagination" class="flex p-1">
  <ejs-tooltip content="Newest" position="BottomCenter">
    <ejs-button ref="btn_Newest" @click.native="btnNewest" iconCss="fas fa-angle-double-left" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <ejs-tooltip content="Previous" position="BottomCenter">
    <ejs-button ref="btn_Prev" @click.native="btnPrev" :disabled="btn_prev" iconCss="fas fa-angle-left" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <ejs-tooltip content="Next" position="BottomCenter">
    <ejs-button ref="btn_Next" @click.native="btnNext" :disabled="btn_next" iconCss="fas fa-angle-right" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <ejs-tooltip content="Oldest" position="BottomCenter">
    <ejs-button ref="btn_Oldest" @click.native="btnOldest" iconCss="fas fa-angle-double-right" cssClass="e-round shadow-none" ></ejs-button>
  </ejs-tooltip>
  <div class="flex items-center justify-center p-2 ml-auto">
    <!-- <p class="text-sm">1 - 50 ({{ inbox_items }})</p> -->
  </div>
</div>
</template>

<script>
export default({
  name: "PaginationTemplate",
  data(){
    return{
      btn_prev: true,
      btn_next: false,
    };
  },

  computed:{
    current_page(){
      return this.$store.state.current_page;
    },

    max_page(){
      return this.$store.state.max_page;
    },

    inbox_items(){
      return this.$store.state.inbox_items;
    }
  },

  mounted(){
    console.log("pagination mounted");
    console.log(this.current_page);
    console.log(this.max_page);
  },
  
  methods:{
    btnNewest(){
      console.log("btnNewest");
    },

    btnPrev(){
      console.log("btnPrev");
      this.$eventHub.$emit("page_prev");
    },

    btnNext(){
      console.log("btnNext");
      this.$eventHub.$emit("page_next");
    },

    btnOldest(){
      console.log("btnOldest");
    }
  },

  created(){
    this.$eventHub.$on("disable_nxtBtn", (e)=>{
      console.log(e);
      this.btn_next = e;
    });

    this.$eventHub.$on("disable_prevBtn", (e)=>{
      console.log(e);
      this.btn_prev = e;
    });
  }
});
</script>
