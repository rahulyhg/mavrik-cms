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
                    <li><a href="#">Clear All</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <template v-if="repository.length > 0 && !firstUpload || isUploading">
                <div class="gallery--repository">
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
                                    <file-upload type="image" :feedback="isUpload" transition="fadeIn"></file-upload>
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
                            <div class="gallery--contents">
                                <div class="gallery--header" :style="{ 'background-image': 'url(' + filteredActiveGallery.image + ')' }">
                                    <div class="card--overlay"></div>
                                    <span class="card--headline">{{filteredActiveGallery.name}}</span>
                                    <span class="card--headline">{{filteredActiveGallery.updated_at}}</span>
                                    <a class="add--gallery btn-floating btn-large waves-effect waves-light red" @click="isAddImageCard = true, isShowMain = false"><i class="material-icons">add</i></a>
                                </div>
                                <h3 class="gallery--callout">Photos</h3>
                                <div class="gallery--main" :class="{'center-gallery': filteredActiveGallery.materials.length < 1, 'order-gallery': filteredActiveGallery.materials.length > 1}">
                                    <template v-if="filteredActiveGallery.materials.length > 0 && isShowMain">
                                        <div class="gallery--image-card card" v-for="material in filteredActiveGallery.materials">
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
                                                <a :id="'cardSave' + material.id" class="waves-effect waves-light btn-flat red hidden" @click="saveCardEdit($index)">Save Edit</a>
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
                                                            <div class="row">
                                                                <div class="input-field col s6">
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
                                                        <img id="previewFile" src="#">
                                                    </template>
                                                    <template v-else>
                                                        <file-upload type="image" :feedback="isUpload" transition="fadeIn" v-show="!isImageUploaded"></file-upload>
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
                                                    <button class="btn waves-effect waves-light" type="submit" name="action" @click="moveToDataUpload" v-show="!isImageUploaded">
                                                        Next
                                                    </button>
                                                    <button class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewImage(filteredActiveGallery.id)" v-else>Submit
                                                        <i class="material-icons right">send</i>
                                                    </button>
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
                        </template>
                        <template v-else>
                            <div class="gallery--grid">
                                <div v-for="gallery in repository" @click="openGallery(gallery.id)" class="gallery--card hoverable waves-effect waves-light" :style="{ 'background-image': 'url(' + gallery.image + ')' }">
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
                        <img src="/image/svg/folder.svg">
                        <h1>Welcome to the art show!</h1>
                        <p>Photo Gallery lets you keep your photos organized how ever you like. <br> At anymoment you can add, edit, and delte photo galleries associated to your webpage.</p>
                        <a class="waves-effect waves-light btn red" @click="initialUpload"><i class="material-icons left">cloud</i>Create Gallery</a>
                    </div>
                </div>
            </template>
        </div>
        <!-- Modal Structure -->
        <div v-el:upload class="modal" :class="{'modal--show': isModalShown }">
            <div class="modal-content">
                <h4>Modal Header</h4>
                <p>A bunch of text</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
            </div>
        </div>
        <div id="materialize-overlay" class="lean--overlay" :class="{'hide--overlay': !isOverlay}" transition="fadeOverlay"></div>
    </div>
</template>
<script>
    import fileUpload from '../elements/material-file-upload.vue'
    export default{
        components:{
            fileUpload
        },
        ready(){
          this.fetchGalleryRepository();
        },
        data(){
            return{
                timeOut: 0,
                repository: [],
                isShowMain: true,
                isPreviewFile: false,
                isOverlay: false,
                isModalShown: false,
                firstUpload: false,
                isUploading: false,
                isUpload: false,
                isImageUploaded: false,
                isOpenGallery: false,
                isAddImageCard: false,
                newImageCardText: '',
                newImageCredit: '',
                activeGallery: '',
                newGalleryName: '',
                newImageName: '',
                fileStage: ''
            }
        },
        computed: {
            filteredActiveGallery: function(){
                if(this.activeGallery){
                    return this.$options.filters.filterFor(this.repository, this.activeGallery)[0];
                }
                return null;
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
            initFileReader: function (file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#previewFile').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            },
            submitNewGallery: function () {
                if(this.newGalleryName){
                    if(this.fileStage){
                        var formData = new FormData();
                        for(var i = 0; i < this.fileStage.length; i++){
                            formData.append('image-' + i, this.fileStage[i]);
                        }
                        formData.append('name', this.newGalleryName);
                        formData.append('type', 'gallery');
                        formData.append('gallery_id', null);
                        formData.append('credit', null);
                        return this.sendHttp('gallery', formData, this.successGalleryUpload);
                    }
                    var data = {
                        'name': this.newGalleryName
                    };

                    return this.sendHttp('gallery', data, this.successGalleryUpload);
                }

            },
            successGalleryUpload: function (results) {
                this.repository.push(results.data);
                this.openGallery(results.data.id);
                this.resetInputs();
            },
            submitNewImage: function ($galleryId) {
                if(this.newImageName){
                    var formData = new FormData();
                    formData.append('image', this.fileStage[0]);
                    formData.append('name', this.newImageName);
                    formData.append('type', 'image');
                    formData.append('gallery_id', $galleryId);
                    formData.append('credit', this.newImageCredit);
                    formData.append('notes', this.newImageCardText);
                    return this.sendHttp('materials', formData, this.successCardUpload);
                }

                console.log('nope');
            },
            successCardUpload: function (results) {
                var gallery_index = this.findGalleryByID(results.data.gallery_id);
                this.repository[gallery_index].materials.push(results.data);

                this.returnToGallery();
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
                this.isPreviewFile = false;
                this.isImageUploaded = false;
            },
            openGallery: function ($id) {
              this.activeGallery = $id;
                this.isOpenGallery = true;
                this.isUploading = false;
                this.isShowMain = true;
            },
            moveToDataUpload: function () {
              if(this.fileStage){
                  this.isImageUploaded = true;
              }
            },

            revealSave: function ($id) {
              var element = document.getElementById('cardSave' + $id);

                element.style.display = 'inline-block';
            },

            saveCardStatus: function ($index) {
                var card = this.filteredActiveGallery.materials[$index];
                var toggle = this.filteredActiveGallery.materials[$index].status;
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
                    var gallery_index = this.findGalleryByID(this.filteredActiveGallery.id);

                console.log(gallery_index + '   ' + $index);

                this.repository[gallery_index].materials.splice($index, 1);
            },

            successCardActivityUpdate: function (results) {
                var $index = this.findMaterialById(results.data.id);
                this.filteredActiveGallery.materials[$index].status = results.data.status;

                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
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

            saveCardEdit: function ($index) {
                var data = this.filteredActiveGallery.materials[$index];
                this.updateHttp('materials/' + data.id, data, this.successUpdate);
            },

            successUpdate: function (results) {
              console.log(results);
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
                    this.fileStage = files;
                    this.initFileReader(files[0]);
                    this.isUpload = false;
                }.bind(this), 1000);
            }
        },
        filters: {
            filterFor: function ($array, filterBy) {
                var filtered = [];
                var filterlist = $array;
                var arrayLength = filterlist.length;
                for (var i = 0; i < arrayLength; i++) {
                    if(filterlist[i].id == filterBy){
                        filtered.push(filterlist[i]);
                    }
                }
                return filtered;
            }
        }
    }
</script>
