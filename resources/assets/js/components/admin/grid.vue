<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="breadcrumb">Content</a>
                    <a href="#!" class="breadcrumb">Grid</a>
                </div>
            </div>
        </nav>
        <div class="component--state">
            <div class="repository-grid">
                <div class="loading--grid" v-show="isReOrder" transition="fade">
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
                <div class="grid" v-show="isGrid" transition="fade">
                    <div class="grid-sizer"></div>
                    <div class="repository--material grid-item" v-for="material in items | orderBy 'position'" @click="arrangeMaterial(material.id)" :class="{'active-item': moveItem.id == material.id || itemDestination.id == material.id}">
                        <div class="item-image-group">
                            <template v-if="material.type == 'image'">
                                <img :src="material.path">
                            </template>
                            <template v-else>
                                <img class="video-item--indicator" v-show="activeHoverEnter != $index && material.type =='video' && activeHoverExit != $index && material.type =='video'" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                                <img :src="material.credit">
                            </template>
                        </div>
                    </div>
                </div v>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        props: ['view'],
        ready(){
            this.fetchWork();
            alert('we have been changed');
        },
        data: function () {
            return {
                timeOut: 0,
                tick: 0,
                isReOrder: true,
                isGrid: false,
                msnryObj: '',
                moveItem: '',
                itemDestination: '',
                items: ''
            }
        },
        watch: {
            'view': function (val) {
                var self = this;
                this.$nextTick(function () {
                    // DOM is now updated
                    self.masonry();
                    if(val == 'grid'){
                        clearTimeout(this.timeOut);
                        this.timeOut = setTimeout(function(){
                            self.msnryObj.layout();
                        }, 800);
                    }
                });

            }
        },
        methods: {
            masonry: function () {
                this.isReOrder = false;
                var elem = document.querySelector('.grid');
                var self = this;
                this.msnryObj = new Masonry( elem, {
                    // options
                    itemSelector: '.grid-item',
                    columnWidth: '.grid-sizer',
                    percentagePosition: true
                });
                var posts = document.querySelectorAll('.grid-item');
                if(this.items){
                    this.moveItem = '';
                    this.itemDestination = '';
                    this.isGrid = true;
                    return imagesLoaded( posts, function() {
                        self.msnryObj.layout();
                    });
                }
                this.msnryObj.layout();
            },
            fetchWork: function () {
                this.getHttp('/auth/materials/active', this.setWork);
            },
            setWork: function (results) {
                this.items = results.data;
            },
            arrangeMaterial: function ($id) {
                var $index = this.findIndex($id);
              if(this.moveItem){
                  this.itemDestination = this.items[$index];
                  return this.updatePosition();
              }

              this.moveItem = this.items[$index];
                this.msnryObj.layout();
            },
            updatePosition: function () {
                var replacement = this.moveItem.position;
                this.moveItem.position = this.itemDestination.position;
                this.itemDestination.position = replacement;

                this.isGrid = false;
                this.isReOrder = true;

                this.updateHttp('/auth/materials/' + this.moveItem.id, this.moveItem ,this.updateGrid);
                this.updateHttp('/auth/materials/' + this.itemDestination.id, this.itemDestination,this.updateGrid);
            },
            updateGrid: function (results) {
                var $index = this.findIndex(results.data.id);
                this.items[$index] = results.data;

                    if(this.tick < 1){
                        return this.tick++;
                    }
                    this.resetGrid()
            },
            resetGrid: function () {
                this.msnryObj='';
                this.masonry();
            },
            findIndex: function ($id) {
                for(var i = 0; i < this.items.length; i++){
                    if(this.items[i].id === $id){
                        return i;
                    }
                }
            },
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                };
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
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