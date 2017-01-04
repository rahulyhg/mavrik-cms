<template>
    <div class="gallery--contents">
        <div class="gallery--header" v-bind:style="{ 'background-image': 'url(' + gallery.image + ')' }">
            <div class="card--overlay"></div>
            <span class="card--headline">{{gallery.name}}</span>
            <span class="card--headline">{{gallery.updated_at}}</span>
            <a class="add--gallery btn-floating btn-large waves-effect waves-light red" @click="isAddImageCard = true, isShowMain = false"><i class="material-icons">add</i></a>
        </div>
        <div class="gallery--options">
            <div class="option option--settings">
                <img src="/image/svg/ic_settings_grey_24px.svg" @click="isShowSettings = true">
            </div>
            <div class="option option--search">
                <img class="search-icon" src="/image/svg/search_icon_grey.svg">
                <input type="text" :class="{'no_result_input': gallerySearch.length > 0 && galleryResults.length === 0, 'positive_result_input': gallerySearch.length > 0 && galleryResults.length > 0}" v-model="gallerySearch" placeholder="Photos">
            </div>
        </div>
        <div class="gallery--main">
            <template v-if="gallery.materials.length > 0 && isShowMain">
                <div class="gallery--image-card card" v-for="material in galleryResults">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" :src="material.path">
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{material.name}}<i class="material-icons right">more_vert</i></span>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                        <div class="row">
                            <div class="input-field col s6">
                                <input :value="material.name" :id="'cardName' + material.id" type="text" class="validate" v-model="material.name" @keydown="revealSave(material.id)">
                                <label class="active" :for="'cardName' + material.id">Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input :value="material.credit" :id="'cardCredit' + material.id" type="text" class="validate" v-model="material.credit" @keydown="revealSave(material.id)">
                                <label class="active" :for="'cardCredit' + material.id">Credits</label>
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
                <div class="gallery--settings" v-show="isShowSettings">
                    <div class="settings-header">
                        <h3>Gallery Settings</h3>
                        <p>Here you can adjust all gallery information, settings and data.</p>
                    </div>
                    <div class="settings-content">
                        
                    </div>
                </div>
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
                                            <input id="icon_prefix" type="text" class="validate" v-model="newImageName">
                                            <label for="icon_prefix">New Name</label>
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
                                                        <input id="credit" type="text" class="validate" v-model="newImageCredit">
                                                        <label for="credit" data-error="wrong" data-success="right">Credits: </label>
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

                                <button class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewImage(gallery.id)">Submit
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
                        <p>Photo Gallery lets you keep your photos organized how ever you like. <br> At anymoment you can add, edit, and delete photo galleries associated to your webpage.</p>
                        <a class="waves-effect waves-light btn red" @click="isAddImageCard = true"><i class="material-icons left">cloud</i>Add Photo</a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
    import fileUpload from '../elements/material-file-upload.vue'
    export default{
        props: ['gallery'],
        components:{
            fileUpload
        },
        data: function(){
            return{
                timeOut: 0,
                activeStageCard: 0,
                newImageCard: {

                },
                fileStage: '',
                gallerySearch: '',
                newImageName: '',
                newImageCredit: '',
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
            galleryResults: function () {
                return this.$options.filters.filterInclude(this.gallery.materials, this.gallerySearch, 'name');
            },
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

                if(this.newImageName && this.newImageCredit){
                    return true;
                }
                return false;
            }
        },
        methods: {
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
                this.deleteHttp('materials/' + $id, this.successCardDelete);
            },
            successCardDelete: function (results) {
                var $index = this.findMaterialById(results.data.material_id);
                this.gallery.materials.splice($index, 1);
            },
            saveCardEdit: function ($index) {
                var data = this.gallery.materials[$index];
                this.updateHttp('materials/' + data.id, data, this.successUpdate);
            },
            saveCardStatus: function ($index) {
                var card = this.gallery.materials[$index];
                var toggle = this.gallery.materials[$index].status;
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
            submitNewImage: function ($galleryId) {
                var self = this;
                var formData = new FormData();
                if(this.supportMultiFile){
                    for(var i = 0; i < this.fileStage.length; i++){
                        formData.append('image', this.fileStage[i].image);
                        formData.append('name', this.fileStage[i].name);
                        formData.append('type', 'image');
                        formData.append('gallery_id', $galleryId);
                        formData.append('credit', this.fileStage[i].credit);
                        formData.append('notes', '');

                        this.sendHttp('materials', formData, this.successCardUpload);
                    }

                    clearTimeout(this.timeOut);
                    this.timeOut = setTimeout(function () {
                        self.returnToGallery();
                    }, 2000);

                } else {
                    if(this.newImageName){
                        formData.append('image', this.fileStage[0]);
                        formData.append('name', this.newImageName);
                        formData.append('type', 'image');
                        formData.append('gallery_id', $galleryId);
                        formData.append('credit', this.newImageCredit);
                        formData.append('notes', this.newImageCardText);
                        return this.sendHttp('materials', formData, this.successCardUpload);
                    }
                }
            },

            //all of the gallery success functions
            successCardActivityUpdate: function (results) {
                var $index = this.findMaterialById(results.data.id);
                this.gallery.materials[$index].status = results.data.status;

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
                    if(!this.gallery.materials){
                        this.gallery.materials = [
                            results.data
                        ];
                    } else {
                        this.gallery.materials.push(results.data);
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
                this.newImageName = '';
                this.newGalleryName = '';
                this.newImageCredit = '';
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
                var cardIndex = this.$options.filters.filterForIndex(this.gallery.materials, $id, 'id');
                document.getElementsByClassName('card-reveal')[cardIndex].getElementsByClassName("material-icons")[0].click();
            },
            //find methods
            findMaterialById: function ($id) {
                for(var i = 0; i < this.gallery.materials.length; i++){
                    if($id == this.gallery.materials[i].id){
                        return i;
                    }
                }
            },
            findGalleryByID: function ($id) {
                for(var i = 0; i < this.gallery.length; i++){
                    if($id == this.gallery[i].id){
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
