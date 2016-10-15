<template>
    <div class="mVu container">
        <div class="content no-show">
            <div id="drop_zone" :class="{'file-opacity': isFileHover}">
                <h1 v-show="!isFileStaged">Drop files here</h1>
                <div class="load--bar" v-else>
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
            <output id="list" v-show="isFileStaged" transition="slideUp">
                <ul class="content flex-center">
                    <li class="file--data">
                        <span>{{fileName}}</span>
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
        ready(){
          this.checkStat();
        },
        data: function () {
            return{
                fileName: '',
                fileSize: '',
                fileModified: '',
                isFileHover: false,
                isFileStaged: false
            }
        },
        methods:{
            checkStat: function () {
                // Check for the various File API support.
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    // Great success! All the File APIs are supported.
                    this.initDragDrop();
                } else {
                    console.log('The File APIs are not fully supported in this browser.');
                }
            },
            initDragDrop: function (evt) {

                // Setup the dnd listeners.
                var dropZone = document.getElementById('drop_zone');
                dropZone.addEventListener('dragover', this.handleDragOver, false);
                dropZone.addEventListener('drop', this.handleFileSelect, false);
            },
            handleFileSelect: function (evt) {
                evt.stopPropagation();
                evt.preventDefault();

                this.isFileHover = false;

                var files = evt.dataTransfer.files; // FileList object.

                if(files[0].type == 'video/mp4'){
                    // files is a FileList of File objects. List some properties.
                    for (var i = 0, f; f = files[i]; i++) {
                        this.fileName = f.name;
                        this.fileSize = f.size;
                        this.fileModified = f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a'
                    }

                    this.isFileStaged = true;

                    return this.$dispatch('ready-material-video', files[0]);

                }

                alert('this file type is not supported here yepad');

            },
            handleDragOver: function (evt) {
                    evt.stopPropagation();
                    evt.preventDefault();
                    this.isFileHover = true;
                    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
            }
        }
    }
</script>
