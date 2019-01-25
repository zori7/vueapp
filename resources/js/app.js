require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
Vue.use(VueRouter);

/*const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));*/

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//

import posts from "./components/Posts.vue";
import users from "./components/Users.vue";
import home from "./components/Home.vue";
import user from "./components/User.vue";
import useredit from "./components/UserEdit.vue";
import post from "./components/Post.vue";
import postedit from "./components/PostEdit.vue";
import postcreate from "./components/PostCreate.vue";
import privatechat from "./components/PrivateChat.vue";
import globalchat from "./components/GlobalChat.vue";
import privatemessages from "./components/PrivateMessages.vue";

const routes = [
    // Tabs

    { path: '/', component: home },
    { path: '/posts', component: posts },
    { path: '/users', component: users },
    { path: '/messages', component: privatemessages },
    { path: '/global', component: globalchat },

    // Shows

    { path: '/post/:id', component: post, props: true },
    { path: '/user/:id', component: user, props: true },
    { path: '/pm/:id', component: privatechat, props: true },

    // Creates

    { path: '/create/post', component: postcreate},

    // Edits

    { path: '/edit/post/:id', component: postedit, props: true},
    { path: '/edit/user/:id', component: useredit, props: true},
];

const router = new VueRouter({
    routes
});

const app = new Vue({
    router
}).$mount('#app');
