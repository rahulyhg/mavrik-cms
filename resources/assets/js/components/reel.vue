<template>
    <div class="content">
        <div class="video--reel full flex-column-center">
            <video id="reel" v-el:video class="video-js vjs-default-skin">
                <source src="/video/promo.mp4" type="video/mp4" />
                <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
            </video>
            <template v-if="isPlay">
                <span @click="controlReel('skip')" class="video--options">Skip</span>
            </template>
            <template v-else>
                <span @click="controlReel('play')" class="video--options">Play</span>
            </template>
        </div>
    </div>
</template>

<script>
    export default{
        ready(){
          this.initReel();
        },
        data:function(){
            return{
                isPlay: true,
                myTimeOut: 0
            }
        },
        methods: {
            controlReel: function (control) {
                switch (control){
                    case 'play':
                        this.isPlay = true;
                        this.$dispatch('control-reel', true);
                        break;
                    case 'skip':
                        this.isPlay = false;
                        this.$dispatch('control-reel', false);
                        break;
                    default:
                        return;
                }
            },
            initReel: function () {
                var self = this;

                this.setWindow();

                videojs(document.getElementById("reel"), { "controls": false, "autoplay": true, "preload": "auto", "muted": false, "loop": true }, function(){
                    var video = this;
                    // Start playing the video.

                    clearTimeout(self.myTimeOut);
                    self.myTimeOut = setTimeout(function(){
                        video.play();
                    }.bind(self), 2000);

                });
            },
            setWindow: function () {
                var w = window.innerWidth;
                var h = window.innerHeight;


                this.$els.video.style.width = (w + 200) + 'px';
                this.$els.video.style.height = h + 'px';
            }
        }
    }
</script>
