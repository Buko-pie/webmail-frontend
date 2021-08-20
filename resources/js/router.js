import VueRouter from "vue-router";
import { createRouter, createWebHistory} from "vue-router";
import EmailFullView from "./components/EmailFullView.vue";

const routes = [
  {
    path: "/EmailFullView/:email_id",
    name: "EmailFullView",
    component: EmailFullView,
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

export default router;