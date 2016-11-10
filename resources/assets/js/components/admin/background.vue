<template>
    <div class="container">
        <div class="content background--content">
            <nav>
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="#!" class="breadcrumb">Video Reels</a>
                    </div>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="#">Add Reel</a></li>
                        <li><a href="#">Clear All</a></li>
                    </ul>
                </div>
            </nav>
            <div class="component--state">
                <div class="repository--jumbo passive-jumbo"></div>
                <div v-el:video-overlay class="upload-box video-upload" :class="{'upload--centered': videoRepository.length <= 0, 'top-box-margin': videoRepository.length > 0}">
                    <div class="video-upload--content">
                        <div class="upload--options">
                            <a class="btn-floating btn-large waves-effect waves-light red" @click="isOptionOpen = !isOptionOpen"><i class="material-icons">add</i></a>
                        </div>
                        <file-upload type="video" :feedback="isUpload" upload="single" v-show="isOptionOpen || isVideoReady || videoRepository.length <= 0" transition="fadeIn" :class="{'upload--processing': isVideoReady}"></file-upload>
                        <div class="video-overlay gutters--xs" v-show="activeVideo" transition="fade">
                            <div class="overlay--content">
                                <video id="vReel" v-el:video class="video-js vjs-default-skin">
                                    <source v-if="activeVideo" type="video/mp4" />
                                    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="video-repository gutters" v-show="videoRepository.length > 0" transition="fade">
                    <div class="repository--nav">
                        <div class="nav--namespace">
                            Video-Reel Archive
                        </div>
                        <div class="nav--search">
                            search bar
                        </div>
                    </div>
                    <ul class="video-repository--content">
                        <template v-if="videoRepository && videoRepository.length > 0">
                            <li class="repository--video waves-effect waves-red hoverable" v-for="video in videoRepository">
                                <div class="slide--content" :class="{'slide--left': isVerify && activeVerify == $index}" @click.self="toggleVideo($index)">
                                    <div class="video--callout">
                                        <div class="video--icon">
                                            <template v-if="activeIndex == $index">
                                                <img class="video-sprite--icon" src="/image/svg/ic_pause_circle_outline_black_24px.svg">
                                            </template>
                                            <template v-else>
                                                <div class="video-sprite--icon"></div>
                                            </template>
                                        </div>
                                        <div class="video--nameSpace">
                                            <span><strong>{{video.name}}</strong></span>
                                            <span>{{video.created_at}}</span>
                                        </div>
                                    </div>
                                    <div class="video--options">
                                        <div class="video--status" @click="updateActivity($index)">
                                            <a class="waves-effect btn-flat" :class="{'red': video.status == 'active'}">{{video.status}}</a>
                                        </div>
                                        <div class="video--delete" @click="verifyDelete($index)">
                                            <img src="/image/svg/ic_more_vert_black_24px.svg">
                                        </div>
                                    </div>
                                </div>
                                <div class="slide--verify" @click="callVerifyDelete($index)">
                                    <img class="svg-icon" src="/image/svg/ic_delete_white_24px.svg">
                                    <span>delete</span>
                                </div>
                            </li>
                        </template>
                        <template v-else>
                            <div class="emptyState emptyState--video">
                                <p>No videos saved in the archive.</p>
                            </div>
                        </template>
                    </ul>
                </div>
            </div>
            <!-- Modal Structure -->
            <div id="modal1" class="modal">
                <div class="modal-content">
                    <h4>Modal Header</h4>
                    <p>A bunch of text</p>
                </div>
                <div class="modal-footer">
                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import fileUpload from '../elements/material-file-upload.vue'
    export default{
        props: ['token'],
        ready(){
          this.fetchReels();
        },
        data(){
            return{
                myTimeOut: 0,
                activeIndex: 0,
                videoRepository: '',
                activeReel: '',
                vjsPlayer: '',
                activeVerify: '',
                isUpload: false,
                activeVideo: false,
                isOptionOpen: false,
                isVideoReady: false,
                isVerify: false
            }
        },
        components:{
            fileUpload
        },
        methods: {
            fetchReels: function () {
              this.getHttp('materials/reel', this.successFetch);
            },
            initReel: function () {
                var self = this;

                this.setWindow();

                var $element = document.getElementById('vReel');

                this.vjsPlayer = videojs($element, {"controls": false, "autoplay": true, "preload": "auto", "muted": false, "loop": true, "loadingSpinner": false });

                    this.vjsPlayer.ready(function () {
                        this.src(self.activeReel);
                            this.load();
                        this.play();
                            });
            },
            setWindow: function () {
                var w = window.innerWidth / 2;
                this.$els.video.style.width = (w + 200) + 'px';
                this.$els.video.style.height = 'auto';
            },
            successFetch: function (results) {
              this.videoRepository = results.data;

                if(this.videoRepository.length > 0){
                    for(var i = 0; i < this.videoRepository.length; i++){
                        if(this.videoRepository[i].status == 'active'){
                            this.activeReel = this.videoRepository[i].path;
                            this.activeIndex = i;
                        }
                    }
                    if(!this.activeReel){
                        this.activeReel = this.videoRepository[0].path;
                        this.activeIndex = 0;
                    }
                    this.activeVideo = true;
                    this.initReel();
                }
            },
            callVerifyDelete: function ($index) {
                var modal = document.getElementById('modal1');
                var jquerymodal = $(modal);  //convert to jQuery Element
//                jquerymodal.openModal();
                this.confirmDelete($index);
            },
            confirmDelete: function ($index) {
                var $videoID = this.videoRepository[$index].id;
              this.deleteHttp('materials/' +  $videoID, this.removeVideo);
            },
            removeVideo: function (results) {
              if(results.data.status == 'success'){
                  this.verifyDelete(results.data.material_id);

                  var $index = this.findIndex(results.data.material_id);

                  if(this.activeIndex == $index){
                      this.activeVideo = false;
                      this.activeReel = '';
                  }

                  clearTimeout(this.myTimeOut);
                  this.myTimeOut = setTimeout(function(){
                      this.videoRepository.splice($index, 1);
                      this.isOptionOpen = true;
                      if(this.activeIndex != 0){
                          this.activeIndex --;
                      }
                  }.bind(this), 1000);
              }
            },
            findIndex: function ($id) {
              for(var i = 0; i < this.videoRepository.length; i++){
                  if(this.videoRepository[i].id == $id){
                      return i;
                  }
              }
            },
            successVideoUpload: function (results) {
                this.isUpload = true;
                this.videoRepository.push(results.data);
                this.activeIndex = this.findIndex(results.data.id);
                this.activeReel = results.data.path;
                this.isVideoReady = false;
                clearTimeout(this.myTimeOut);
                this.myTimeOut = setTimeout(function(){
                    this.isUpload = false;
                    this.activeVideo = true;
                }.bind(this), 1000);
                this.initReel();
            },
            toggleVideo: function ($index) {
                if(this.activeIndex != $index){
                    this.activeVideo = false;

                    this.activeReel = this.videoRepository[$index].path;
                    this.activeIndex = $index;

                    clearTimeout(this.myTimeOut);
                    this.myTimeOut = setTimeout(function(){
                        this.initReel();
                        clearTimeout(this.myTimeOut);
                        this.myTimeOut = setTimeout(function(){
                            this.activeVideo = true;
                        }.bind(this), 1000);
                    }.bind(this), 500);
                }
            },
            verifyDelete: function ($index) {

                this.isVerify = !this.isVerify;

                if(!this.activeVerify){
                    return this.activeVerify = $index;
                }

                return this.activeVerify = '';
            },
            updateActivity: function ($index) {
                var status;

                var video = this.videoRepository[$index];
                if(video.status == "active"){
                    status = 'inactive'
                } else {
                    status = 'active'
                }
                var data = {
                  'status': status
                };
              this.updateHttp('materials/reel/' + video.id, data, this.successUpdate);
            },
            successUpdate: function (results) {

                for(var i = 0; i < results.data.deactivated.length; i++){
                    var $index = this.findIndex(results.data.deactivated[i].id);
                    this.videoRepository[$index].status = 'inactive';
                }


                var $newActiveIndex = this.findIndex(results.data.new_active.id);
                this.videoRepository[$newActiveIndex].status = results.data.new_active.status;

                if(this.activeIndex != $newActiveIndex && results.data.new_active.status == 'active'){
                    this.toggleVideo($newActiveIndex);
                }
            },
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                }
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
            },
            sendHttp: function (url, data, callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    },
                    dataType: 'json'
                }

                this.$http.post(url, data, params).then(callback).catch(err => console.error(err));
            },
            deleteHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                }
                this.$http.delete(url,params).then(callback).catch(err => console.error(err));
            },
            updateHttp: function (url, data, callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    },
                    dataType: 'json'
                }

                this.$http.put(url, data, params).then(callback).catch(err => console.error(err));
            }
        },
        events: {
            'ready-material': function (files) {
                this.isVideoReady = true;
                this.isOptionOpen = false;
                this.activeVideo = false;
                var formData = new FormData();
                formData.append('name', files[0].name);
                formData.append('video', files[0]);
                formData.append('gallery_id', null);
                formData.append('type', 'reel');
                formData.append('credit', null);
                this.sendHttp('materials', formData, this.successVideoUpload);
            }
        }
    }
</script>
