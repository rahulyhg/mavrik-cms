<template>
    <div class="content">
        <div class="video--reel full flex-column-center" v-show="isInitReel" transition="fade" :class="{'no-show': !isFadeIn}">
            <template v-if="isReel" transition="fade">
                <iframe id="vimeo" src="https://player.vimeo.com/video/190700532?autoplay=1" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                <span @click="controlReel('skip')" @mouseenter="isOutOfView = false" @mouseleave="isOutOfView = true" class="video--options skip" :class="{'opaque-view': isOutOfView}">Skip</span>
            </template>
            <template v-else>
                <video id="reel" v-el:video class="video-js vjs-default-skin">
                    <source v-if="reel" :src="reel[0].path" type="video/mp4"/>
                    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                </video>
                <img @click="controlReel('play')" class="video-sprite--icon" src="/image/svg/ic_play_circle_outline_white_24px.svg">
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
                isInitReel: false,
                isReel: false,
                isOutOfView: false
            }
        },
        methods: {
            controlReel: function (control) {
                switch (control){
                    case 'play':
                        this.switchSource(null);
                        this.$dispatch('control-reel', false);
                        break;
                    case 'skip':
                        this.switchSource(this.reel[0].path);
                        this.$dispatch('control-reel', true);
                        break;
                    default:
                        return;
                }
            },
            switchSource: function (source) {
                var self = this;
                this.isReel = !this.isReel;
                if(source){
                    clearTimeout(this.myTimeOut);
                    return this.myTimeOut = setTimeout(function () {
                        self.setVideo();
                    },500);
                }
                this.vjsPlayer.dispose();
                console.log(this.isReel);
            },
            setVideo: function () {
                //set the video display to the height and width of the window
                this.setWindow();

                var $element = this.$els.video;
                this.vjsPlayer = videojs($element, {"controls": false, "autoplay": true, "preload": "auto", "muted": false, "loop": true, "loadingSpinner": false });

                this.vjsPlayer.ready(function () {
                    this.src(self.activeReel);
                    this.play();
                });
            },
            initReel: function () {

                var self = this;

                clearTimeout(self.myTimeOut);
                self.myTimeOut = setTimeout(function(){
                    self.isFadeIn = true;
                    clearTimeout(self.myTimeOut);
                    self.myTimeOut = setTimeout(function(){
                        self.isLoadScreen = false;
                        self.isReel = true;
                        clearTimeout(self.myTimeOut);
                        self.myTimeOut = setTimeout(function () {
                            self.isOutOfView = true;
                        }, 1500);
                    }, 1000);
                }, 1000);
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
                    this.isPlay = true;
                   this.switchSource(null);
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
