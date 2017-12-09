import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';
import add from './add.vue';


Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {
            path: '/addsth',
            component: add,
        },
        {
            path: '/',
            component: App,
        },
    ]
});




new Vue({
    el: '#app',
    render: h => h(App),
    // router
})
