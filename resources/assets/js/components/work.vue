<template>
    <div class="content--scroll full flex-column-center">
        <div class="repository">
            <div class="grid">
                <div class="grid-sizer"></div>
                <div class="repository--material grid-item" v-for="material in repository" @mouseenter="delayEnter($index)" @mouseleave="delayExit($index)" @click="showItem(material.id, material.gallery_id)">
                    <div class="item-image-group">
                        <div class="item-overlay tran-05" :class="{'force-opacity': activeHoverEnter == $index || activeHoverExit == $index}"></div>
                        <template v-if="material.type == 'image'">
                            <img :src="material.path">
                        </template>
                        <template v-else>
                            <img class="video-item--indicator" v-show="activeHoverEnter != $index || activeHoverExit != $index" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                            <img :src="material.credit">
                        </template>
                    </div>
                    <div class="item--callout">
                        <img v-show="activeHoverEnter == $index && material.type =='video' || activeHoverExit == $index && material.type =='video'" class="icon-50p" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                        <div class="callout-box callout-group">
                            <div class="line-group">
                                <span class="group" v-show="activeHoverEnter == $index || activeHoverExit == $index" transition="callout">
                                {{material.credit}}
                                 </span>
                                <span class="callout--line line-thin">
                                        <span v-show="activeHoverEnter == $index || activeHoverExit == $index" class="line lw" transition="fadeIn"></span>
                                </span>
                            </div>
                        </div>
                        <div class="callout-box callout-name">
                            <span class="name" v-show="activeHoverEnter == $index || activeHoverExit == $index" transition="callout">{{material.name}}</span>
                        </div>
                        <div class="callout--line line-thick">
                            <div v-show="activeLine == $index" class="line" transition="lineReveal"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="activeGallery" id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div v-for="material in filteredMaterials" class="item" :class="{'active': material.id == carouselStart}">
                        <div class="item--card">
                            <img :src="material.path">
                        </div>
                    </div>
                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div v-if="activeGallery" class="carousel-backdrop"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['active-view', 'masonry', 'side-menu'],
        ready() {
            this.fetchWork();
        },
        data: function () {
            return {
                timeOut: 0,
                msTimeout: 0,
                inactiveTimer: 0,
                activeGallery: '',
                carouselStart: '',
                activeHoverEnter: 'none',
                activeHoverExit: 'none',
                activeLine: 'none',
                repository: ''
            }
        },
        computed: {
          filteredMaterials: function () {
              return this.$options.filters.filterFor(this.repository, this.activeGallery, 'gallery_id');
          }
        },
        watch: {
            'sideMenu': function (val) {
                var self = this;
                if(this.masonry){
                    clearTimeout(this.timeOut);
                    this.timeOut = setTimeout(function(){
                        self.masonry.layout();
                    }, 500);
                }
            }
        },
        methods: {
            fetchWork: function () {
                this.getHttp('/auth/materials/active', this.setWork);
            },
            setWork: function (results) {
              this.repository = results.data;
            },
            showItem: function ($id, $gallery_id) {
              this.masonry.layout();
                this.carouselStart = $id;
                this.activeGallery = $gallery_id;
            },
            delayEnter: function ($index) {
                var self = this;
                clearTimeout(this.inactiveTimer);
                this.activeHoverEnter = $index;
                this.msTimeout = setTimeout(function () {
                    self.activeLine = $index;
                }, 1000);
            },
            delayExit: function ($index) {
                var self = this;
              this.activeHoverExit = $index;
                clearTimeout(this.timeOut);
                clearTimeout(this.msTimeout);
                this.timeOut = setTimeout(function () {
                    self.activeHoverExit = 'none';
                    self.activeLine = 'none';
                }, 1000);
                this.inactiveTimer = setTimeout(function () {
                    self.activeHoverEnter = 'none';
                }, 1000);
            },
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                }
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
            }
        },
        filters: {
            filterFor: function ($array, filterBy, filterIn) {
                var filtered = [];
                var filterlist = $array;
                var arrayLength = filterlist.length;
                for (var i = 0; i < arrayLength; i++) {
                    if(filterlist[i][filterIn] == filterBy){
                        filtered.push(filterlist[i]);
                    }
                }
                return filtered;
            }
        }
    }
</script>
