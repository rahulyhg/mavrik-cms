
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

import showreel from './components/reel.vue';
import bio from './components/about.vue';
import photos from './components/work.vue';
import contact from './components/contacts.vue';
import videos from './components/writing.vue';


new Vue({
    el: 'body',
    components: {showreel,bio, photos, contact, videos},
    ready() {
        this.setHome();
        this.fetchMaterials();
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
        materials: '',
        views: [
            'Showreel', 'Bio', 'Photos', 'Videos', 'Contact'
        ],
        isTitle: false,
        isTag: false,
        isLinks: false,
        isCountDown: false,
        activeReel: false,

    },
    computed: {
        filteredReelMaterials: function(){
            if(this.materials){
                return this.$options.filters.filterFor(this.materials, 'reel');
            }
            return null;
        },
        filteredImageMaterials: function(){
            if(this.materials){
                return this.$options.filters.filterFor(this.materials, 'image');
            }
        },
        filteredVideoMaterials: function () {
            if(this.materials){
                return this.$options.filters.filterFor(this.materials, 'video');
            }
        }
    },
    methods:{
        seeView: function (view, $index) {
            switch (view){
                case 'Showreel':
                    if(this.view != 'Showreel'){
                        this.view = 'Showreel';
                        clearTimeout(this.myTimeOut);
                        this.myTimeOut = setTimeout(function(){
                            this.activeReel = false;
                            this.$broadcast('show-reel', true);
                        }.bind(this), 1000);
                        break;
                    } else{
                        this.activeReel = false;
                        this.$broadcast('show-reel', true);
                    }
                default:
                    this.view = view;
                    break;
            }
            this.activeLink = $index;
            this.$broadcast('change-view', view);
        },
        setHome: function () {
            this.view = 'Showreel';
            // this.setLinkSpan();
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
        },
        fetchMaterials: function () {
            this.getHttp('/auth/materials', this.sortMaterials);
        },
        sortMaterials: function (results) {
          this.materials = results.data;

            this.$broadcast('init-reel', true);
        },
        getHttp: function (url,callback) {
            const params = {
                headers: {
                    'X-CSRF-TOKEN': this.token
                }
            }
            this.$http.get(url, params).then(callback).catch(err => console.error(err));
        },
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
        filterFor: function ($array, filterBy) {
            var filtered = [];
            var filterlist = $array;
            var arrayLength = filterlist.length;
            for (var i = 0; i < arrayLength; i++) {
                if(filterlist[i].type == filterBy){
                    filtered.push(filterlist[i]);
                }
            }
            return filtered;
        }
    },
    events: {
        'control-reel': function (control) {
                this.activeReel = control;
        }
    }

});
