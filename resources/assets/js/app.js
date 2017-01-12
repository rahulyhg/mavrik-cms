
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

import showreel from './components/reel.vue';
import bio from './components/about.vue';
import photos from './components/work.vue';
import contact from './components/message.vue';
import social from './components/contacts.vue';
import videos from './components/writing.vue';


new Vue({
    el: 'body',
    components: {showreel,bio, photos, contact, videos, social},
    ready() {
        this.view = 'showreel';
        this.fetchMaterials();
    },
    data: {
        activeLink: 0,
        myTimeOut: 0,
        view: '',
        linkBoxWidth: '',
        spanWidth: '',
        materials: '',
        msnryObj: '',
        views: [
            'showreel', 'bio', 'media', 'contact', 'social'
        ],
        isTitle: false,
        isTag: false,
        isLinks: false,
        isCountDown: false,
        activeReel: false
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
            var self = this;
            this.view = view;
            switch (view){
                case 'showreel':
                        this.activeReel = false;
                        this.$broadcast('show-reel', true);
                    break;
                case 'media':
                    this.$broadcast('stop-reel', true);
                    this.$broadcast('transition-grid', true);
                    this.$nextTick(function () {
                        // DOM is now updated
                        self.masonry();
                        self.$broadcast('transition-grid', false);
                        clearTimeout(self.myTimeOut);
                        self.myTimeOut = setTimeout(function(){
                            self.activeReel = false;
                        }, 1000);
                    });

                    break;
                case 'contact':
                case 'social':
                case 'bio':
                    this.$broadcast('stop-reel', true);
                    break;
                default:
                    break;
            }

            this.activeLink = $index;
            this.$broadcast('change-view', view);
        },
        masonry: function () {
            var elem = document.querySelector('.grid');
            var self = this;
            this.msnryObj = new Masonry( elem, {
                // options
                itemSelector: '.grid-item',
                columnWidth: '.grid-sizer',
                percentagePosition: true
            });
            var posts = document.querySelectorAll('.grid-item');
            imagesLoaded( posts, function() {
                self.msnryObj.layout();
            });
        },
        toggleMenu: function () {
            var self = this;
            if(this.view == 'media'){
                this.$broadcast('transition-grid', true);
                clearTimeout(this.myTimeOut);
                return this.myTimeOut = setTimeout(function () {
                    self.activeReel = !self.activeReel;
                    self.$broadcast('transition-grid', false);
                }, 500);
            }

            this.activeReel = !this.activeReel;
        },
        getWidth: function (target) {
            return target.offsetWidth;
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
