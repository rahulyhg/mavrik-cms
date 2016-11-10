<template>
    <div class="mVu container">
        <div class="content no-show">
            <div v-el:upload :class="{'file-opacity': isFileHover}" class="dropzone">
                <template v-if="!isFileStaged">
                    <template v-if="type == 'image'">
                        <img class="icon" src="/image/svg/camera.svg">
                    </template>
                    <template v-else>
                        <img class="icon" src="/image/svg/clapboard.svg">
                    </template>
                    <p><strong>Click</strong> or <strong>Drag and Drop</strong> <br> to upload video files here... </p>
                </template>
                <div class="load--bar" v-show="isFileStaged && !state">
                    <div class="preloader-wrapper big active">
                        <div class="spinner-layer spinner-blue">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                            <div class="circle"></div>
                        </div><div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                        </div>

                        <div class="spinner-layer spinner-red">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                            <div class="circle"></div>
                        </div><div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                        </div>

                        <div class="spinner-layer spinner-yellow">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                            <div class="circle"></div>
                        </div><div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                        </div>

                        <div class="spinner-layer spinner-green">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                            <div class="circle"></div>
                        </div><div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                        </div>
                    </div>
            </div>
            <output id="list" v-if="isFileStaged && !state" transition="slideUp">
                <ul class="content flex-center">
                    <li class="file--data">
                        <span>{{fileName | fileList}}</span>
                        <span>{{fileSize}}</span>
                        <span>last modified: {{fileModified}}</span>
                    </li>
                </ul>
            </output>
        </div>
    </div>
</template>

<script>
    export default{
        props: ['feedback', 'type', 'upload'],
        ready(){
            this.createId(this.type);
        },
        data: function () {
            return{
                id: '',
                myTimeOut: 0,
                fileName: '',
                fileSize: '',
                fileModified: '',
                isFileHover: false,
                isFileStaged: false
            }
        },
        computed: {
            state: function () {
                if(this.feedback){
                    clearTimeout(this.myTimeOut);
                    this.myTimeOut = setTimeout(function(){
                        this.isFileStaged = false;
                    }.bind(this), 2000);
                    return true;
                }
                return false;
            }
        },
        methods:{
            createId: function (type) {
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

                    for( var i=0; i < 5; i++ )
                        text += possible.charAt(Math.floor(Math.random() * possible.length));

                    this.id = type + '-' + text;

                return this.checkStat();
            },
            checkStat: function () {
                // Check for the various File API support.
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    // Great success! All the File APIs are supported.
                    if(this.id){
                        this.initDragDrop();
                    }
                } else {
                    console.log('The File APIs are not fully supported in this browser.');
                }
            },
            initDragDrop: function (evt) {

                // Setup the dnd listeners.
                var dropZone = this.$els.upload;
                dropZone.addEventListener('dragover', this.handleDragOver, false);
                dropZone.addEventListener('drop', this.handleFileSelect, false);
            },
            handleFileSelect: function (evt) {
                evt.stopPropagation();
                evt.preventDefault();

                this.isFileHover = false;

                var files = evt.dataTransfer.files; // FileList object.

                if(this.upload == 'single' && files.length > 1){
                    return this.$dispatch('upload-error', {
                        type: 'upload-amount-error',
                        title: 'Upload Error!',
                        message: 'Multiple file upload is not supported in this location'
                    });
                }

                this.uploadFiles(files);

            },
            uploadFiles: function (files) {
                var notSupported = [];
                    this.fileName = [];
                    this.fileSize = 0;
                    // files is a FileList of File objects. List some properties.
                    for (var i = 0, f; f = files[i]; i++) {
                        var fileType = this.findFileType(f.type);
                        if(fileType != this.type){
                            notSupported.push(f.name);
                        } else {
                            this.fileName.push(f.name);
                            this.fileSize =+ f.size;
                            this.fileModified = f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a'
                        }
                    }

                if(notSupported.length > 0){
                    return this.$dispatch('upload-error', {
                        type: 'upload-type-error',
                        title: 'Upload Error!',
                        message: 'This file type is not supported in this location. Please try to upload the correct file type.'
                    });
                }
                this.isFileStaged = true;
                return this.$dispatch('ready-material', files);
            },
            findFileType: function (type) {
              var split = type.split('/');
                return split[0];
            },
            handleDragOver: function (evt) {
                    evt.stopPropagation();
                    evt.preventDefault();
                    this.isFileHover = true;
                    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
            }
        },
        filters: {
            fileList: function (list) {
                return list.join(', ');
            }
        }

    }
</script>
