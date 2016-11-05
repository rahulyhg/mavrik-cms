<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="breadcrumb">Video Gallery</a>
                </div>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="#" @click="isUploading = true">Add Video</a></li>
                    <li><a href="#">Clear All</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <div class="repository--jumbo" :class="{'passive-jumbo': isUploading}">
                <img class="repository--icon icon-card" src="/image/svg/folder.svg" v-show="repository.length <= 0 && !isUploading">
            </div>
            <div class="link--repositiory repository" :class="{'flex-center f-h': isUploading}" v-if="repository && repository.length > 0 || isUploading">
                <template v-if="isUploading">
                    <div class="upload-box">
                        <div class="upload--header">
                            <div class="row">
                                <form class="col s12">
                                    <div class="row">
                                        <div class="input-field col s6">
                                            <i class="material-icons prefix">account_circle</i>
                                            <input id="icon_prefix" type="text" class="validate" v-model="newLinkName">
                                            <label for="icon_prefix">New Link Name</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="upload--content">
                            <div class="upload--data">
                                <div class="row">
                                    <form class="col s12">
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <span>Select media type: </span>
                                            </div>
                                            <div class="input-field col s12">
                                                <img class="media-icon" src="/image/svg/vimeo-grey.svg" @click="mediaActive = 'vimeo'" v-show="mediaActive != 'vimeo'">
                                                <img class="media-icon" src="/image/svg/vimeo-red.svg" @click="mediaActive = ''" v-show="mediaActive == 'vimeo'">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row link-input">
                                    <form class="col s12">
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input id="url" type="text" class="validate" v-model="newLinkUrl">
                                                <label for="url">Link Url: </label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="upload--footer">
                            <button class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewLink">Submit
                                <i class="material-icons right">send</i>
                            </button>
                            <a class="waves-effect waves-teal btn-flat" @click="isUploading = false">Cancel</a>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="gallery--grid">
                        <div v-for="video in repository" class="video--card card">
                            <div class="card-video">
                                <iframe height="100%" width="100%" :src="video.path" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                            <div class="card-action">
                                <a class="waves-effect waves-red btn-flat" :class="{'red': video.status == 'active'}" @click="saveCardStatus($index)">{{video.status}}</a>
                                <a class="waves-effect waves-red btn-flat" @click="removeCard(video.id)">Remove</a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="empty--state" transition="fade" v-else>
                <div class="empty">
                    <h1>Welcome to the art show!</h1>
                    <p>Photo Gallery lets you keep your photos organized how ever you like. <br> At anymoment you can add, edit, and delte photo galleries associated to your webpage.</p>
                    <a class="waves-effect waves-light btn red" @click="isUploading = true"><i class="material-icons left">cloud</i>Add Video Link</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        ready(){
            this.fetchVideoLinks();
        },
        data(){
            return{
                repository: '',
                newLinkName: '',
                newLinkUrl: '',
                mediaActive: '',
                isUploading: false
            }
        },
        methods: {
            fetchVideoLinks: function () {
                this.getHttp('materials/video', this.successFetch);
            },
            successFetch: function (results) {
                this.repository = results.data;
            },
            saveCardStatus: function ($index) {
                var card = this.repository[$index];
                var toggle = this.repository[$index].status;
                var status;

                if(toggle == 'active'){
                    status = 'inactive'
                } else {
                    status = 'active'
                }

                var data = {
                    'status': status
                }

                this.updateHttp('materials/' + card.id, data, this.successCardActivityUpdate);
            },
            removeCard: function ($id) {
                this.deleteHttp('materials/' + $id, this.successCardDelete);
            },
            successCardDelete: function (results) {

                var $index = this.findMaterialById(results.data.material_id);
                this.repository.splice($index, 1);
            },
            findMaterialById: function ($id) {
                for(var i = 0; i < this.repository.length; i++){
                    if($id == this.repository[i].id){
                        return i;
                    }
                }
            },
            successCardActivityUpdate: function (results) {
                var $index = this.findMaterialById(results.data.id);
                this.repository[$index].status = results.data.status;

                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
            },
            submitNewLink: function () {
                console.log('yep');
                if(this.newLinkUrl && this.newLinkName && this.mediaActive){
                    var data = {
                        'name': this.newLinkName,
                        'type': 'video-link',
                        'gallery_id': null,
                        'path': this.newLinkUrl,
                        'media': this.mediaActive,
                        'credit': null
                    };
                    this.sendHttp('materials', data, this.successLink);
                }
            },
            // handle callback for adding new link
            successLink: function (results) {
                this.repository.push(results.data);
                this.isUploading = false;
                this.resetInputs();
            },
            resetInputs: function () {
              this.newLinkName = '';
                this.newLinkUrl = '';
                this.mediaActive = '';
            },
            // vue resource methods
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
        }
    }
</script>
