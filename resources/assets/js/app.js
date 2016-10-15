
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
var Vue = require('vue');
var moment = require('moment');
moment().format();
Vue.config.debug = true;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

import background from './components/reel.vue';
import about from './components/about.vue';
import work from './components/work.vue';
import contact from './components/contacts.vue';
import writing from './components/writing.vue';


new Vue({
    el: 'body',
    components: {background,about, work, contact, writing},
    ready() {
        this.setHome();
        // this.incrementDate();
    },
    data: {
        now: Date.now(),
        activeLink: 0,
        myTimeOut: 0,
        countDown: 0,
        launch: '2016-10-06T20:03:55',
        view: '',
        linkBoxWidth: '',
        spanWidth: '',
        views: [
            'Home', 'About', 'Work', 'Writing', 'Contact'
        ],
        isTitle: false,
        isTag: false,
        isLinks: false,
        isCountDown: false,
        activeReel: true

    },
    methods:{
        seeView: function (view, $index) {
            switch (view){
                case 'Home':
                    this.view = 'reel';
                    break;
                default:
                    this.view = view;
                    break;
            }
            this.activeLink = $index;
            this.$broadcast('change-view', view);
        },
        setHome: function () {
            this.view = 'reel';
            // this.setLinkSpan();
            console.log('come set');
        },
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
        setLinkSpan: function () {
            var linkBox = this.$els.linkBox,
                views = this.views.length;

            this.linkBoxWidth = this.getWidth(linkBox),

            this.spanWidth = this.linkBoxWidth / views;

            this.$els.spanLink.style.width = this.spanWidth + 'px';
        },
        moveSpan: function (index) {
            clearTimeout(this.myTimeOut);
            this.$els.spanLink.style.left = this.spanWidth * index + 'px';
        },
        returnSpan: function () {
            var timeOut = 1000;
            this.myTimeOut = setTimeout(function(){
                this.$els.spanLink.style.left = this.spanWidth * this.activeLink + 'px';
            }.bind(this), timeOut);
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
        },
    },
    events: {
        'control-reel': function (control) {
            console.log(control);
                this.activeReel = control;
        }
    }

});
