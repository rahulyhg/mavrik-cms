<template>
    <div class="content--scroll full flex-column-center">
        <div class="repository">
            <div class="grid">
                <div class="grid-sizer"></div>
                <div class="repository--material grid-item" v-for="material in repository" @mouseenter="delayEnter($index)" @mouseleave="delayExit($index)" @click="showItem($index)">
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
                        <img v-show="activeHoverEnter == $index && material.type =='video' || activeHoverExit == $index && material.type =='video'" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                        <div class="callout-box callout-group">
                            <span class="group" v-show="activeHoverEnter == $index || activeHoverExit == $index" transition="callout">{{material.credit}}</span>
                        </div>
                        <div class="callout-box callout-name">
                            <span class="name" v-show="activeHoverEnter == $index || activeHoverExit == $index" transition="callout">{{material.name}}</span>
                        </div>
                        <div v-show="activeLine == $index" class="callout--line"></div>
                    </div>
                </div>
            </div>
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
                activeHoverEnter: 'none',
                activeHoverExit: 'none',
                activeLine: 'none',
                repository: ''
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
            showItem: function ($index) {
              this.masonry.layout();
            },
            delayEnter: function ($index) {
                var self = this;
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
                }, 500);
            },
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                }
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
            }
        }
    }
</script>
