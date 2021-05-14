<template>
<div class="control-section sidebar-default">
    <!-- sample level element  -->
    
    <!-- sample level element  -->
    <div id="wrapper">
        <div class="col-lg-12 col-sm-12 col-md-12">
            <!-- Sidebar element declaration -->
            <ejs-sidebar id="dockSidebar" ref="dockSidebar" class="mt-16 bg-white text-gray-700" :enableDock='enableDock' :position="position" :width='width' :dockSize='dockSize'>
                <div class="compose_btn_container">
                    <button class="compose_btn shadow-black pill">
                      <i class="fas fa-plus-circle text-lg"></i>
                      <span class="compose_text" v-show="toggled">Compose</span>
                    </button>
                </div>
                <div class="sidebar_list">
                    <a class="sidebar_items_selected" href="#">
                      <div class="sidebar_icons">
                        <i class="fas fa-inbox text-lg"></i>
                      </div>
                      <p class="sidebar_text" v-show="toggled">Inbox</p>
                    </a>
                    <a class="sidebar_items" href="#">
                      <div class="sidebar_icons">
                        <i class="far fa-star text-lg"></i>
                      </div>
                      <p class="sidebar_text" v-show="toggled">Starred</p>
                    </a>
                    <a class="sidebar_items" href="#">
                      <div class="sidebar_icons">
                        <i class="fas fa-thumbtack text-lg"></i>
                      </div>
                      <p class="sidebar_text" v-show="toggled">Important</p>
                    </a>
                    <a class="sidebar_items" href="#">
                      <div class="sidebar_icons">
                        <i class="far fa-paper-plane text-lg"></i>
                      </div>
                      <p class="sidebar_text" v-show="toggled">Sent</p>
                    </a>
                    <a class="sidebar_items" href="#">
                      <div class="sidebar_icons">
                        <i class="far fa-file text-lg"></i>
                      </div>
                      <p class="sidebar_text" v-show="toggled">Drafts</p>
                    </a>
                    <a class="sidebar_items" href="#">
                      <div class="sidebar_icons">
                        <i class="fas fa-tag text-lg"></i>
                      </div>
                      <p class="sidebar_text" v-show="toggled">Categories</p>
                    </a>
                </div>
            </ejs-sidebar>
            <!-- end of sidebar element -->
            <!-- main content declaration -->
            <div>
                <vue-grid-component/>
            </div>
        </div> 
    </div>

</div>
</template>

<script>
import Vue from "vue";
import { SidebarPlugin } from '@syncfusion/ej2-vue-navigations';
import { ButtonPlugin , RadioButtonPlugin } from '@syncfusion/ej2-vue-buttons';
import { enableRipple } from '@syncfusion/ej2-base';

Vue.use(SidebarPlugin, ButtonPlugin, RadioButtonPlugin);

export default Vue.extend({
    data: function() {
        return {
            enableDock:  true,
            dockSize : '72px',
            width : '16rem',
            position :'Left',
            toggled: true
        }
    },
    methods: {
        toggleClick :function() {
            this.$refs.dockSidebar.toggle();
            this.toggled = !this.toggled;
        },
        closeClick: function() {
            this.$refs.dockSidebar.hide();
        },
        openClick:function() {
            this.$refs.dockSidebar.show();
        }
    }
});
</script>


<style>

</style>