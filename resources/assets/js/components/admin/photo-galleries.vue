<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="breadcrumb" @click="returnToMain">Photo Gallery</a>
                    <a href="#!" v-if="activeGallery" @click="returnToGallery" class="breadcrumb">{{filteredActiveGallery.name}}</a>
                    <a href="#!" v-show="isUploading" class="breadcrumb">New Gallery</a>
                    <a href="#!" v-show="isAddImageCard" class="breadcrumb">New Image</a>
                </div>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="#" @click="moveToUpload">Add Gallery</a></li>
                    <li v-show="isOpenGallery" @click="verifyDeleteGallery()"><a href="#">Delete Gallery</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <div class="repository--jumbo" :class="{'passive-jumbo': isUploading}" v-show="!isOpenGallery || isUploading || repository.length == 0">
                <img class="repository--icon icon-card" src="/image/svg/folder.svg" v-show="repository.length <= 0 && !isUploading">
                <div class="repository--search" v-show="repository.length > 0 && !isUploading" @mouseenter="isSearching = true" @mouseleave="isSearching = false">
                    <img class="repository--search-icon" src="/image/svg/search_icon.svg">
                    <div class="search--box">
                        <input type="text" v-show="isSearching || repositorySearch.length > 0" :class="{'no_result_input': repositorySearch.length > 0 && searchResults.length === 0, 'positive_result_input': repositorySearch.length > 0 && searchResults.length > 0}" v-model="repositorySearch">
                        <span v-else>Search...</span>
                    </div>
                </div>
            </div>
            <template v-if="repository.length > 0 && !firstUpload || isUploading">
                <div class="gallery--repository" :class="{'content': isOpenGallery, 'full--center': isUploading}">
                    <template v-if="isUploading">
                        <div class="upload-box image--upload">
                            <div class="upload--header">
                                <div class="row">
                                    <form class="col s12">
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <i class="material-icons prefix">account_circle</i>
                                                <input id="icon_prefix" type="text" class="validate" v-model="newGalleryName">
                                                <label for="icon_prefix">New Gallery Name</label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="upload--content">
                                <template v-if="isPreviewFile">
                                    <img id="previewFile" src="#">
                                </template>
                                <template v-else>
                                    <file-upload type="image" :feedback="isUpload" transition="fadeIn" upload="single"></file-upload>
                                </template>
                            </div>
                            <div class="upload--footer">
                                <button class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewGallery">Submit
                                    <i class="material-icons right">send</i>
                                </button>
                                <a class="waves-effect waves-teal btn-flat" @click="returnToMain">Cancel</a>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="isOpenGallery">
                            <open-gallery :gallery="filteredActiveGallery"></open-gallery>
                        </template>
                        <template v-else>
                            <div class="gallery--grid">
                                <div v-for="gallery in searchResults" @click="openGallery(gallery.id)" class="gallery--card hoverable waves-effect waves-light" :style="{ 'background-image': 'url(' + gallery.image + ')' }">
                                    <div class="card--overlay"></div>
                                    <span class="card--headline">{{gallery.name}}</span>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </template>
            <template v-else>
                <div class="empty--state" transition="fade">
                    <div class="empty">
                        <h1>Welcome to the art show!</h1>
                        <p>Photo Gallery lets you keep your photos organized how ever you like. <br> At anymoment you can add, edit, and delte photo galleries associated to your webpage.</p>
                        <a class="waves-effect waves-light btn red" @click="initialUpload"><i class="material-icons left">cloud</i>Create Gallery</a>
                    </div>
                </div>
            </template>
        </div>
        <!-- Modal Structure -->
        <div class="modal--container" v-show="isInitModal">
            <div v-el:upload class="modal" :class="{'modal--show': isModalShown}">
                <div class="modal-header" :class="{'salmon': isModalError}">
                    <h4>{{modal.title}}</h4>
                </div>
                <div class="callouts" v-show="modal.callouts.length > 0">
                    <span v-for="callout in modal.callouts">{{callout}}</span>
                </div>
                <div class="modal-content">
                    <p>{{modal.message}}</p>
                </div>
                <div class="modal-footer">
                    <a class="waves-effect waves-light btn salmon" @click="closeModal">button</a>
                    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat" @click="deleteGallery(filteredActiveGallery.id)" v-show="!isModalError">Agree</a>
                </div>
            </div>
            <div id="materialize-overlay" class="lean--overlay" :class="{'hide--overlay': !isOverlay}" transition="fadeOverlay"></div>
        </div>
    </div>
</template>
<script>
    import fileUpload from '../elements/material-file-upload.vue'
    import openGallery from '../elements/gallery-component.vue'
    export default{
        components:{
            fileUpload, openGallery
        },
        ready(){
          this.fetchGalleryRepository();
        },
        data(){
            return{
                timeOut: 0,
                repository: [],
                isModalShown: false,
                firstUpload: false,
                isUploading: false,
                isUpload: false,
                isImageUploaded: false,
                isOpenGallery: false,
                isSearching: false,
                // modal data
                isPreviewFile: false,
                isInitModal: false,
                isOverlay: false,
                isModalError: false,
                modal: {
                  title: null,
                    callouts: [],
                    message: null
                },
                modalErrors: {
                    'duplicate_image': {
                        'title': 'Duplicate Image',
                        'message': 'Opps...We found another image with the same name already saved in the database. If you are sure you want this image please delete the previous image or change the name of your new image.'
                    }
                },
                // form data
                repositorySearch: '',

                newImageCardText: '',
                newImageCredit: '',
                activeGallery: '',
                newGalleryName: '',
                newImageName: '',
                fileStage: '',
                imageCardStage: []
            }
        },
        computed: {
            filteredActiveGallery: function(){
                if(this.activeGallery){
                    return this.$options.filters.filterFor(this.repository, this.activeGallery, 'id')[0];
                }
                return null;
            },
            searchResults: function () {
              return this.$options.filters.filterInclude(this.repository, this.repositorySearch, 'name');
            },
            firstUpload: function () {
                if(this.repository.length <= 0){
                    return true;
                }
                return false;
            }
        },
        methods:{
            fetchGalleryRepository: function () {
              this.getHttp('gallery', this.setGalleries);
            },
            setGalleries: function (results) {
              if(results.data.length > 0){
                  return this.repository = results.data;
              }
            },
            moveToUpload: function () {
                this.isUploading = true;
                this.activeGallery = ''
            },
            initialUpload: function () {
                 this.showDock();
             },
            showDock: function () {
                this.isUploading = true;
            },
            initFileReader: function (file, location) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(location).attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            },
            submitNewGallery: function () {
                if(this.newGalleryName){
                    if(this.fileStage){
                        var formData = new FormData();
                        formData.append('image-0', this.fileStage[0]);
                        formData.append('name', this.newGalleryName);
                        formData.append('type', 'gallery');
                        formData.append('gallery_id', null);
                        formData.append('credit', null);
                        return this.sendHttp('gallery', formData, this.successGalleryUpload);
                    }
                    var data = {
                        'name': this.newGalleryName,
                        'type': 'gallery',
                        'gallery_id': null,
                        'credit': null,
                        'path': null
                    };

                    return this.sendHttp('gallery', data, this.successGalleryUpload);
                }

            },
            successGalleryUpload: function (results) {
                this.repository.push(results.data[0]);
                this.openGallery(results.data[0].id);
                this.resetInputs();
            },
            returnToMain: function () {
                this.isOpenGallery = false;
                this.isUploading = false;
                this.isAddImageCard = false;
                this.activeGallery = '';
                this.resetInputs();
            },
            returnToGallery: function () {
                this.isShowMain = true;
                this.isAddImageCard = false;
                this.resetInputs();
            },
            resetInputs: function () {
              this.newImageName = '';
                this.newGalleryName = '';
                this.newImageCredit = '';
                this.fileStage = '';
                this.supportMultiFile = false;
                this.isPreviewFile = false;
                this.isImageUploaded = false;
            },
            openGallery: function ($id) {
              this.activeGallery = $id;
                this.isOpenGallery = true;
                this.isUploading = false;
                this.isShowMain = true;
            },
            verifyDeleteGallery: function () {
                this.modal.title = 'Delete Gallery';
                this.modal.message = 'Deleting this gallery will remove all of the contents stored inside. Are you sure you want to delete this gallery?';
                this.toggleModal();
            },

            deleteGallery: function (gallery_id) {
              this.deleteHttp('gallery/' + gallery_id, this.successGalleryDelete);
            },
            successGalleryDelete: function (results) {
                this.closeModal();
                this.returnToMain();

                var gallery_index = this.findGalleryByID(results.data.material_id);
                this.repository.splice(gallery_index, 1);
            },

            toggleModal: function () {
                this.isInitModal = true;
                this.isOverlay = true;
              this.isModalShown = true;
            },
            closeModal: function () {
                this.isModalShown = false;
                this.isOverlay = false;
                this.isInitModal = false;
                this.isModalError = false;
                this.modal.title = null;
                this.modal.callouts = [];
                this.modal.message = null;
            },
            findMaterialById: function ($id) {
                for(var i = 0; i < this.filteredActiveGallery.materials.length; i++){
                    if($id == this.filteredActiveGallery.materials[i].id){
                        return i;
                    }
                }
            },
            findGalleryByID: function ($id) {
                for(var i = 0; i < this.repository.length; i++){
                    if($id == this.repository[i].id){
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
            }
        }
    }
</script>
