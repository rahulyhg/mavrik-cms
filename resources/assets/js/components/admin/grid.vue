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
                <div class="grid">
                    <div class="grid-sizer"></div>
                    <div class="repository--material grid-item" v-for="material in items">
                        <div class="item-image-group">
                            <template v-if="material.type == 'image'">
                                <img :src="material.path">
                            </template>
                            <template v-else>
                                <img class="video-item--indicator" v-show="activeHoverEnter != $index && material.type =='video' && activeHoverExit != $index && material.type =='video'" src="/image/svg/ic_play_circle_outline_white_24px.svg">
                                <img :src="material.credit">
                            </template>
                        </div>
                        <div class="item--callout">
                        </div>
                    </div>
                    <!--<div class="grid-sizer"></div>-->
                    <!--<div class="repository&#45;&#45;material grid-item" v-for="item in items">-->
                        <!--<div class="item-image-group">-->
                            <!--<img :src="item.path">-->
                        <!--</div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        props: ['view'],
        ready(){
            this.fetchWork();
        },
        data: function () {
            return {
                timeOut: 0,
                msnryObj: '',
                items: ['w','l','r','f','d','s','a']
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
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                };
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
            }
        }
    }
</script>