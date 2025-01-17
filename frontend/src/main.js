import Vue from 'vue';
import App from './App.vue';
import VueRouter from 'vue-router';
import PostList from "./components/Post/List";
import PostForm from "./components/Post/Form";
import Login from "./components/User/Login";

Vue.use(VueRouter);

const routes = [
  { path: '/', name: 'posts-list', component: PostList },
  { path: '/posts/create', name: 'posts-form', component: PostForm },
  { path: '/login', name: 'login', component: Login },
];

const router = new VueRouter({
  mode: 'history',
  routes,
});

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  router,
}).$mount('#app')
