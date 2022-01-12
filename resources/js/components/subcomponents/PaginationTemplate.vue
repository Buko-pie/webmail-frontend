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
  <div class="flex items-center justify-center p-2 ml-auto" :inbox_items="inbox_items">
    <p class="text-sm">{{ first_item }} - {{ last_item }} ({{ inbox_total }})</p>
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
      first_item: 1,
      last_item: 0,
      last_num: 0,
      op: null,
    };
  },

  computed:{
    // start(){
    //   this.last_item = this.$store.state.inbox_items;
    //   console.log('ssssstart');
    // },

    current_page(){
      return this.$store.state.current_page;
    },

    max_page(){
      return this.$store.state.max_page;
    },

    inbox_items(){
      return this.$store.state.inbox_items;
    },

    inbox_total(){
      return this.$store.state.inbox_total;
    }
  },

  mounted(){
    console.log("pagination mounted");
  },
  
  methods:{
    btnNewest(){
      this.$eventHub.$emit("refresh_inbox");
    },

    btnPrev(){
      this.op = "page_prev";
      this.$eventHub.$emit("page_prev");
    },

    btnNext(){
      this.op = "page_next";
      this.$eventHub.$emit("page_next");
    },

    btnOldest(){
      console.log("btnOldest");
    }
  },

  created(){
    this.$eventHub.$on("disable_nxtBtn", (e)=>{
      this.btn_next = e;
    });

    this.$eventHub.$on("disable_prevBtn", (e)=>{
      this.btn_prev = e;
    });

    this.$eventHub.$on("page_change", (e)=>{
      if(e === "page_next"){
        this.first_item = this.last_item + 1;
        this.last_item += this.inbox_items;
        this.last_num = this.inbox_items;
      }else if(e === "page_prev"){
        this.first_item -= this.inbox_items;
        this.last_item -= this.last_num;
      }else{
        this.last_item = this.inbox_items;
      }
    });
  }
});
</script>
