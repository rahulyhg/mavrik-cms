<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="#" @click="isAddImageCard = true, isShowMain = false;">Add Media</a></li>
                    <li v-show="isOpenGallery" @click="verifyDeleteGallery()"><a href="#">Delete Gallery</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <div class="repository--jumbo passive--jumbo"></div>
            <div class="gallery--main">
                <template v-if="icons.length > 0 && isShowMain">
                    <div class="gallery--image-card card" v-for="material in icons">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img class="activator" :src="material.image">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">{{material.name}}<i class="material-icons right">more_vert</i></span>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                            <div class="row row-close">
                                <div class="input-field col s6">
                                    <input :value="material.name" :id="'cardName' + material.id" type="text" class="validate" v-model="material.name" @keydown="revealSave(material.id)">
                                    <label class="active" :for="'cardName' + material.id">Name</label>
                                </div>
                            </div>
                            <div class="row row-close">
                                <div class="input-field col s6">
                                    <input :value="material.path" :id="'cardCredit' + material.id" type="text" class="validate" v-model="material.path" @keydown="revealSave(material.id)">
                                    <label class="active" :for="'cardCredit' + material.id">Url: </label>
                                </div>
                            </div>
                            <div class="row row-close">
                                <div class="input-field col s6">
                                    <input :value="material.position" :id="'cardCredit' + material.id" type="text" class="validate" v-model="material.position" @keydown="revealSave(material.id)">
                                    <label class="active" :for="'cardCredit' + material.id">Position: </label>
                                </div>
                            </div>
                            <a :id="'cardSave' + material.id" class="waves-effect waves-light btn-flat salmon hidden" @click="saveCardEdit($index)">Save Edit</a>
                        </div>
                        <div class="card-action">
                            <a class="waves-effect waves-red btn-flat" :class="{'red': material.status == 'active'}" @click="saveCardStatus($index)">{{material.status}}</a>
                            <a class="waves-effect waves-red btn-flat" @click="removeCard(material.id)">Remove</a>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="gallery--add-image" v-show="isAddImageCard">
                        <div class="upload-box image--upload">
                            <div class="upload--header">
                                <div class="row">
                                    <form class="col s12">
                                        <div class="row relative">
                                            <ol class="carousel-indicators" v-show="supportMultiFile && fileStage.length > 1">
                                                <li data-target="#myCarousel" v-for="file in fileStage" :data-slide-to="$index" :class="{'active': $index == 0}"></li>
                                            </ol>
                                            <div class="input-field col s6" v-else>
                                                <i class="material-icons prefix">account_circle</i>
                                                <input id="icon_prefix" type="text" class="validate" v-model="newIconName">
                                                <label for="icon_prefix">New Social Media</label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="upload--content">
                                <template v-if="isPreviewFile && !isImageUploaded">
                                    <template v-if="supportMultiFile">
                                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner" role="listbox">
                                                <div v-for="file in fileStage" class="item" :class="{'active': $index == 0}">
                                                    <div class="col s12 m7">
                                                        <div class="card horizontal">
                                                            <div class="card-image">
                                                                <img :id="'previewFile-' + $index" src="#">
                                                            </div>
                                                            <div class="card-stacked">
                                                                <div class="card-content">
                                                                    <div class="row">
                                                                        <div class="input-field col s6">
                                                                            <input id="last_name" type="text" class="validate" v-model="file.name">
                                                                            <label for="last_name">Name</label>
                                                                        </div>
                                                                        <div class="input-field col s6">
                                                                            <input id="last_name" type="text" class="validate" v-model="file.credit">
                                                                            <label for="last_name">Credit: </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <img id="previewFile" src="#">
                                    </template>
                                </template>
                                <template v-else>
                                    <template v-if="supportMultiFile">
                                        <file-upload type="image" :feedback="isUpload" transition="fadeIn" v-show="!isImageUploaded" upload="multiple"></file-upload>
                                    </template>
                                    <template v-else>
                                        <file-upload type="image" :feedback="isUpload" transition="fadeIn" v-show="!isImageUploaded" upload="single"></file-upload>
                                    </template>
                                    <div class="upload--data" v-else>
                                        <div class="upload--instructions">
                                            <h3>Additional Info</h3>
                                            <p>We require some additional information about your new upload. Please fill in the below form fields before submitting your new material.</p>
                                        </div>
                                        <div class="upload--credit-input">
                                            <div class="row">
                                                <form class="col s12">
                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <input id="credit" type="text" class="validate" v-model="newIconPath">
                                                            <label for="credit" data-error="wrong" data-success="right">Media_Url: </label>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="upload--footer">
                                <p class="check-box left-aligned">
                                    <input type="checkbox" class="filled-in" id="filled-in-box" @click="supportMultiFile = !supportMultiFile" />
                                    <label for="filled-in-box">Multiple File Upload</label>
                                </p>
                                <template v-if="!isSubmitReady">
                                    <button v-show="!supportMultiFile" class="btn waves-effect waves-light" type="submit" name="action" @click="moveToDataUpload" v-show="!isImageUploaded">
                                        Next
                                    </button>

                                    <button v-else class="btn waves-effect waves-light" @click="toggleImageCard()">Next Image</button>
                                </template>

                                <template v-else>

                                    <button class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewImage()">Submit
                                        <i class="material-icons right">send</i>
                                    </button>

                                </template>
                                <a class="waves-effect waves-teal btn-flat" @click="returnToGallery">cancel</a>
                            </div>
                        </div>
                    </div>
                    <div class="empty--state" transition="fade" v-else>
                        <div class="empty">
                            <img src="/image/svg/folder.svg">
                            <h1>Welcome to the art show!</h1>
                            <p>Photo Gallery lets you keep your photos organized how ever you like. <br> At anymoment you can add, edit, and delte photo galleries associated to your webpage.</p>
                            <a class="waves-effect waves-light btn red" @click="isAddImageCard = true"><i class="material-icons left">cloud</i>Add Photo</a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
<script>
    import fileUpload from '../elements/material-file-upload.vue'
    export default{
        components:{
            fileUpload
        },
        ready:function () {
            this.getHttp('/auth/social_media', this.setMedia);
        },
        data: function(){
            return{
                timeOut: 0,
                activeStageCard: 0,
                newImageCard: {

                },
                icons: '',
                fileStage: '',
                newIconName: '',
                newIconPath: '',
                isSubmitReady: '',
                isUpload: false,
                isPreviewFile: false,
                supportMultiFile: false,
                isShowMain: true,
                isShowSettings: false,
                isAddImageCard: false,
                isImageUploaded: false
            }
        },
        computed: {
            isSubmitReady: function () {
                if(this.supportMultiFile){
                    var failed = [];
                    for(var i = 0; i < this.fileStage.length; i++){
                        if(!this.fileStage[i].name || !this.fileStage[i].credit){
                            failed.push(this.fileStage[i]);
                        }
                    }

                    if(failed.length > 0 || this.fileStage.length <=  0){
                        return false;
                    }

                    return true;
                }

                if(this.newIconName && this.newIconPath){
                    return true;
                }
                return false;
            }
        },
        methods: {
            setMedia: function (results) {
              this.icons = results.data;
            },
            //action route functions
            revealSave: function ($id) {
                var element = document.getElementById('cardSave' + $id);

                element.style.display = 'inline-block';
            },
            moveToDataUpload: function () {
                if(this.fileStage){
                    this.isImageUploaded = true;
                }
            },
            toggleImageCard: function () {
                this.activeStageCard ++;
                var $element = $("#myCarousel");
                if(this.activeStageCard > this.fileStage.length){
                    this.activeStageCard = 0;
                    return $element.carousel(0);
                }
                $element.carousel(this.activeStageCard);
            },

            //preview file upload file reader
            initFileReader: function (file, location) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(location).attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            },

            //all of the new card submits and updates
            removeCard: function ($id) {
                this.deleteHttp('/auth/social_media/' + $id, this.successCardDelete);
            },
            successCardDelete: function (results) {
                var $index = this.findMaterialById(results.data.material_id);
                this.icons.splice($index, 1);
            },
            saveCardEdit: function ($index) {
                var data = this.icons[$index];
                this.updateHttp('/auth/social_media/' + data.id, data, this.successUpdate);
            },
            saveCardStatus: function ($index) {
                var card = this.icons[$index];
                var toggle = this.icons[$index].status;
                var status;

                if(toggle == 'active'){
                    status = 'inactive'
                } else {
                    status = 'active'
                }

                var data = {
                    'status': status
                }

                this.updateHttp('/auth/social_media/' + card.id, data, this.successCardActivityUpdate);
            },
            submitNewImage: function () {
                var self = this;
                var formData = new FormData();
                if(this.supportMultiFile){
                    for(var i = 0; i < this.fileStage.length; i++){
                        formData.append('image', this.fileStage[i].image);
                        formData.append('name', this.fileStage[i].name);
                        formData.append('path', this.fileStage[i].credit);
                        this.sendHttp('/auth/social_media', formData, this.successCardUpload);
                    }

                    clearTimeout(this.timeOut);
                    this.timeOut = setTimeout(function () {
                        self.returnToGallery();
                    }, 2000);

                } else {
                    if(this.newIconName){
                        formData.append('image', this.fileStage[0]);
                        formData.append('name', this.newIconName);
                        formData.append('path', this.newIconPath);
                        return this.sendHttp('/auth/social_media', formData, this.successCardUpload);
                    }
                }
            },

            //all of the gallery success functions
            successCardActivityUpdate: function (results) {
                var $index = this.findMaterialById(results.data.id);
                this.icons[$index].status = results.data.status;

                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
            },
            successCardUpload: function (results) {
                if(results.data.error){
                    this.isModalError = true;
                    this.modal.title = this.modalErrors.duplicate_image.title;
                    this.modal.callouts.push(results.data.image);
                    this.modal.message = this.modalErrors.duplicate_image.message;

                    return this.toggleModal();
                } else {
                    if(!this.icons){
                        this.icons = [
                            results.data
                        ];
                    } else {
                        this.icons.push(results.data);
                    }

                    if(!this.supportMultiFile){
                        return this.returnToGallery();
                    }
                }
            },
            successUpdate: function (results) {
                this.returnCardReveal(results.data.id);
            },

            //resets and return statements
            resetInputs: function () {
                this.newIconName = '';
                this.newIconPath = '';
                this.fileStage = '';
                this.supportMultiFile = false;
                this.isPreviewFile = false;
                this.isImageUploaded = false;
            },
            returnToGallery: function () {
                this.isShowMain = true;
                this.isAddImageCard = false;
                this.resetInputs();
            },
            returnCardReveal: function ($id) {
                var cardIndex = this.$options.filters.filterForIndex(this.icons, $id, 'id');
                document.getElementsByClassName('card-reveal')[cardIndex].getElementsByClassName("material-icons")[0].click();
            },
            //find methods
            findMaterialById: function ($id) {
                for(var i = 0; i < this.icons.length; i++){
                    if($id == this.icons[i].id){
                        return i;
                    }
                }
            },


            //vue resource methods
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
                //initiate fileReader
                clearTimeout(this.timeOut);
                this.timeOut = setTimeout(function () {
                    this.isPreviewFile = true;
                    if(this.supportMultiFile){
                        this.fileStage = [];
                        $('.carousel.carousel-slider').carousel({full_width: true});
                        for(var i = 0; i < files.length; i++){
                            var card = {
                                'image': files[i],
                                'name': '',
                                'type': 'image',
                                'gallery_id': this.activeGallery,
                                'credit': '',
                                'notes': ''
                            };
                            this.fileStage.push(card);
                            this.initFileReader(files[i], '#previewFile-' + i);
                        }
                    } else {
                        this.fileStage = files;
                        this.initFileReader(files[0], '#previewFile');
                    }
                    this.isUpload = false;
                }.bind(this), 1000);
            },
            'upload-error': function (error) {
                this.isModalError = true;
                this.modal.title = error.title;
                this.modal.message = error.message;

                this.toggleModal();
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
            filterInclude: function ($array, filterBy, filterIn) {
                var filtered = [];
                var filterlist = $array;
                var arrayLength = filterlist.length;
                for (var i = 0; i < arrayLength; i++) {
                    var string = filterlist[i][filterIn];
                    if(string.indexOf(filterBy) !== -1){
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
        }
    }
</script>
