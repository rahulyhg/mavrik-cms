<template>
    <div class="content">
        <div class="video--reel full flex-column-center" v-show="isInitReel" transition="fade" :class="{'no-show': !isFadeIn}">
            <video id="reel" v-el:video class="video-js vjs-default-skin">
                <source src="/video/Showreel Fabiana Formica 2016-HD.mp4" type="video/mp4"/>
                <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
            </video>
            <template v-if="isPlay">
                <span @click="controlReel('skip')" @mouseenter="isOutOfView = false" @mouseleave="isOutOfView = true" class="video--options skip" :class="{'opaque-view': isOutOfView}">Skip</span>
            </template>
            <template v-else>
                <span @click="controlReel('play')" class="video--options">
                    <img class="video-sprite--icon" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                </span>
            </template>
        </div>
        <div class="content--load-screen" v-show="isLoadScreen">
            <div class="load--callout">
                <h1>
                    <span>FABIANA FORMICA</span>
                    <span class="title-underline underline_white"></span>
                </h1>
                <span>Actress</span>
            </div>
            <p>Loading...</p>
        </div>
    </div>
</template>

<script>
    export default{
        props: ['reel'],
        data:function(){
            return{
                isLoop: true,
                myTimeOut: 0,
                vjsPlayer: '',
                isFadeIn: false,
                isLoadScreen: true,
                isPlay: true,
                isInitReel: false,
                isOutOfView: false
            }
        },
        methods: {
            controlReel: function (control) {
                switch (control){
                    case 'play':
                        this.switchSource('/video/Showreel Fabiana Formica 2016-HD.mp4');
                        this.isPlay = true;
                        this.$dispatch('control-reel', false);
                        break;
                    case 'skip':
                        this.switchSource(this.reel[0].path);
                        this.isPlay = false;
                        this.$dispatch('control-reel', true);
                        break;
                    case 'pause':
                        this.isPlay = false;
                    default:
                        return;
                }
            },
            switchSource: function (source) {
                var self = this;
                this.vjsPlayer.currentTime(0); // 2 minutes into the video
                this.vjsPlayer.pause();

                clearTimeout(this.myTimeOut);
                this.myTimeOut = setTimeout(function(){
                    self.vjsPlayer.src({type: 'video/mp4', src: source});
                    self.vjsPlayer.play();

                    //some how the controls reset to true after changing source; setting them back to false;
                    self.vjsPlayer.controls = false;
                }, 500);
            },
            initReel: function () {

                var self = this;
                //set the video display to the height and width of the window
                this.setWindow();

                var $element = document.getElementById('reel');
                this.vjsPlayer = videojs($element, {"controls": false, "autoplay": true, "preload": "auto", "muted": false, "loop": true, "loadingSpinner": false });

                this.vjsPlayer.ready(function () {
                    this.src(self.activeReel);
                    this.play();
                    clearTimeout(self.myTimeOut);
                    self.myTimeOut = setTimeout(function(){
                        this.isFadeIn = true;
                        clearTimeout(self.myTimeOut);
                        self.myTimeOut = setTimeout(function(){
                            this.isLoadScreen = false;
                            clearTimeout(self.myTimeOut);
                            self.myTimeOut = setTimeout(function () {
                                self.isOutOfView = true;
                            }, 1500);
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
                    }.bind(this), 1000);
            },
            'show-reel': function (control) {
                if(control){
                   this.switchSource('/video/Showreel Fabiana Formica 2016-HD.mp4');
                    this.isPlay = true;
                }
            },
            'stop-reel': function (action) {
                if(action){
                    this.vjsPlayer.pause();
                }
            }
        }
    }
</script>
