<template>
    <div class="content">
        <div class="video--reel full flex-column-center" v-show="isInitReel" transition="fade" :class="{'no-show': !isFadeIn}">
            <video id="reel" v-el:video class="video-js vjs-default-skin">
                <template v-if="reel">
                    <source :src="reel[0].path" type="video/mp4" />
                </template>
                <template v-else>
                    <source src="/local/video/promo-copy.mp4" type="video/mp4" />
                </template>
                <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
            </video>
            <template v-if="isPlay">
                <span @click="controlReel('skip')" class="video--options">Skip</span>
            </template>
            <template v-else>
                <span @click="controlReel('play')" class="video--options">
                    <img class="video-sprite--icon" src="/image/svg/ic_play_circle_outline_black_24px.svg">
                </span>
            </template>
        </div>
        <div class="content--load-screen" v-show="isLoadScreen">
            <h1>FABIANA</h1>
            <p>Loading...</p>
            <div class="progress-container">
                <div class="progress">
                    <div class="indeterminate"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props: ['reel'],
        data:function(){
            return{
                myTimeOut: 0,
                vjsPlayer: '',
                isFadeIn: false,
                isLoadScreen: true,
                isPlay: true,
                isInitReel: false
            }
        },
        methods: {
            controlReel: function (control) {
                switch (control){
                    case 'play':
                        this.isPlay = true;
                        this.$dispatch('control-reel', false);
                        break;
                    case 'skip':
                        this.isPlay = false;
                        this.$dispatch('control-reel', true);
                        break;
                    case 'pause':
                        this.isPlay = false;
                    default:
                        return;
                }
            },
            initReel: function () {

                var self = this;

                this.setWindow();

                var $element = document.getElementById('reel');
                this.vjsPlayer = videojs($element, {"controls": false, "autoplay": true, "preload": "auto", "muted": false, "loop": true, "loadingSpinner": false });

                this.vjsPlayer.ready(function () {
//                    this.src(self.activeReel);
                    this.play();
                    clearTimeout(self.myTimeOut);
                    self.myTimeOut = setTimeout(function(){
                        this.isFadeIn = true;
                        clearTimeout(self.myTimeOut);
                        self.myTimeOut = setTimeout(function(){
                            this.isLoadScreen = false;
                        }.bind(self), 1000);
                    }.bind(self), 1000);
                });
            },
            setWindow: function () {
                var w = window.innerWidth;
                var h = window.innerHeight;


                this.$els.video.style.width = (w + 200) + 'px';
                this.$els.video.style.height = h + 'px';
            }
        },
        events: {
            'init-reel': function (action) {
                    this.isInitReel = true;
                    clearTimeout(this.myTimeOut);
                    this.myTimeOut = setTimeout(function(){
                        this.initReel();
                    }.bind(this), 200);
            },
            'show-reel': function (control) {
                if(control){
                    this.isPlay = true;
                    this.vjsPlayer.currentTime(0); // 2 minutes into the video
                    this.vjsPlayer.pause();

                    clearTimeout(this.myTimeOut);
                    this.myTimeOut = setTimeout(function(){
                        this.vjsPlayer.play();
                    }.bind(this), 1000);
                }
            }
        }
    }
</script>
