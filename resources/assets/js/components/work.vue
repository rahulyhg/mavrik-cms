<template>
    <div class="content--scroll full flex-column-center">
        <div class="repository">
            <div class="grid" :class="{'transition': isFrozen}">
                <div class="grid-sizer"></div>
                <div class="repository--material grid-item" v-for="material in repository | orderBy 'position'" @mouseenter="delayEnter($index)" @mouseleave="delayExit($index)" @click="showItem($index, material.id, material.gallery_id)">
                    <div class="item-image-group">
                        <div class="item-overlay tran-05" :class="{'force-opacity': activeHoverEnter == $index || activeHoverExit == $index}"></div>
                        <template v-if="material.type == 'image'">
                            <img :src="material.path">
                        </template>
                        <template v-else>
                            <img class="video-item--indicator" v-show="activeHoverEnter != $index && material.type =='video' && activeHoverExit != $index && material.type =='video'" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                            <img :src="material.credit">
                        </template>
                    </div>
                    <div class="item--callout">
                        <img v-show="activeHoverEnter == $index && material.type =='video' || activeHoverExit == $index && material.type =='video'" class="icon50p" transition="callout" src="/image/svg/ic_play_circle_outline_white_24px.svg">
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
            <div v-if="isGalleryActive" id="myCarousel" class="carousel slide" data-interval="false" transition="fade">
                <div v-if="isItemSelected" class="item-side">
                    <div class="side side-credit">
                        <template v-if="galleryType == 'image'">
                            <span>{{filteredActiveItem.credit}}</span>
                        </template>
                        <template v-else>
                            <span>Video Clip</span>
                        </template>
                    </div>
                    <div class="side side-name">
                        <span>{{filteredActiveItem.name}}</span>
                    </div>
                </div>
                <div class="close-gallery">
                    <img class="icon25p" @click="closeGallery" src="/image/svg/ic_highlight_off_white_18px.svg">
                </div>
                <div class="carousel-content" role="listbox">
                    <div v-for="material in filteredMaterials" class="item" :class="{'active': $index == startItem}">
                        <div class="item--card">
                            <template v-if="galleryType == 'video'">
                                <iframe height="100%" width="100%" :src="material.path" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </template>
                            <template v-else>
                                <img :src="material.path">
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev" @click="incrementSlider('down')">
                    <img class="icon-50p" src="/image/svg/ic_keyboard_arrow_left_white_24px.svg" alt="previous">
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next" @click="incrementSlider('up')">
                    <img class="icon-50p" src="/image/svg/ic_keyboard_arrow_right_white_24px.svg" alt="next">
                </a>
            </div>
            <div v-if="isGalleryActive" class="carousel-backdrop" transition="fadeDrop"></div>
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
                filterBy: '',
                startItem: '',
                activeItem: '',
                repository: '',
                filterThrough: '',
                galleryType: '',
                activeHoverEnter: 'none',
                activeHoverExit: 'none',
                activeLine: 'none',
                isItemSelected: false,
                isGalleryActive: false,
                isFrozen: false
            }
        },
        computed: {
          filteredMaterials: function () {
              return this.$options.filters.filterFor(this.repository, this.filterBy, this.filterThrough);
          },
            filteredActiveItem: function () {
                if(this.activeItem){
                    return this.filteredMaterials[this.activeItem];
                }

                if(this.activeItem == 0){
                    return this.filteredMaterials[0];
                }
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
            showItem: function ($index, $material_id, $gallery_id) {
              this.masonry.layout();
                if(!$gallery_id){
                    if(this.repository[$index].type == 'video'){
                        this.galleryType = 'video';
                        this.filterBy = 'video';
                        this.filterThrough = 'type';
                    }
                } else {
                    this.galleryType = 'image';
                    this.filterBy = $gallery_id;
                    this.filterThrough = 'gallery_id';
                }
                var filteredIndex = this.$options.filters.filterForIndex(this.filteredMaterials, $material_id, 'id');
                this.startItem = filteredIndex;
                this.activeItem = filteredIndex;
                this.isItemSelected = true;
                this.isGalleryActive = true;
            },
            closeGallery: function () {
                this.isGalleryActive = false;
                this.isItemSelected = false;
                this.activeItem = '';
                this.startItem = '';
                this.filterBy = '';
                this.filterThrough = '';
                this.galleryType = '';
            },
            incrementSlider: function (direction) {
                if(direction == 'up'){
                    var nextItem = this.activeItem + 1;
                    if(nextItem > this.filteredMaterials.length - 1){
                        return this.activeItem = 0;
                    }
                    return this.activeItem++;
                }
                var prevItem = this.activeItem - 1;
                if(prevItem < 0){
                    return this.activeItem = this.filteredMaterials.length - 1;
                }
                return this.activeItem = this.activeItem - 1;
            },
            delayEnter: function ($index) {
                if(!this.isFrozen){
                    var self = this;
                    clearTimeout(this.inactiveTimer);
                    this.activeHoverEnter = $index;
                    this.msTimeout = setTimeout(function () {
                        self.activeLine = $index;
                    }, 1000);
                }
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
                };
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
            },
            filterForIndex: function ($array, filterBy, filterIn) {
                var filterlist = $array;
                var arrayLength = $array.length;
                for(var i = 0; i < arrayLength; i++){
                    if(filterlist[i][filterIn] == filterBy){
                        return i;
                    }
                }
            }
        },
        events: {
            'transition-grid': function (action) {
                var self = this;
                if(action){
                    return this.isFrozen = true;
                }
                clearTimeout(this.msTimeout);
                this.msTimeout = setTimeout(function () {
                    self.isFrozen = false;
                }, 1200);
            }
        }
    }
</script>
