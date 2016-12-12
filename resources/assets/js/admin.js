
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
var Vue = require('vue');
Vue.use(require('vue-resource'));
var moment = require('moment');
moment().format();
Vue.config.debug = true;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

import dashboard from './components/admin/dashboard.vue';
import backgroundVideo from './components/admin/background.vue';
import photoGallery from './components/admin/photo-galleries.vue';
import socialMedia from './components/admin/socials.vue';
import statistics from './components/admin/statistics.vue';
import social from './components/admin/social_videos.vue';
import contact from './components/admin/contact.vue';
import journal from './components/admin/journal.vue';


new Vue({
    el: 'body',
    components: {dashboard,'background-video':backgroundVideo,statistics, 'photo-gallery': photoGallery,social, contact, journal, 'social-media': socialMedia},
    ready() {
        // this.incrementDate();
    },
    data: {
        now: Date.now(),
        myTimeOut: 0,
        activeView: 'dashboard',
        launch: '2016-10-06T20:03:55',
        views: [
            'dashboard', 'statistics', 'background', 'photo galleries', 'video gallery'
        ]
    },
    methods:{
        incrementDate: function () {
            clearTimeout(this.countDown);
            this.countDown = setTimeout(function(){
                this.now = this.now + 1000;
                this.incrementDate();
            }.bind(this), 1000);
        },
        getWidth: function (target) {
            return target.offsetWidth;
        },
        changeView: function (view) {
            this.activeView = view;
        }
    },
    filters: {
        seconds: function (now) {
            var a = moment(now);//now
            return (a.diff(this.launch, 'seconds') - (a.diff(this.launch, 'minutes') * 60)) * -1;
        },
        minuets: function (now) {
            var a = moment(now);//now
            return (a.diff(this.launch, 'minutes') - (a.diff(this.launch, 'hours') * 60)) * -1;
        },
        hours: function (now) {
            var a = moment(now);//now
            return (a.diff(this.launch, 'hours') - (a.diff(this.launch, 'days') * 24)) * -1;
        },
        days: function (now) {
            var a = moment(now);//now
            return (a.diff(this.launch, 'days')) * -1;
        }
    }

});
