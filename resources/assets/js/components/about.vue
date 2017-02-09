<template>
    <div class="content full full-fab flex-column-center">
        <div class="about--fab">
            <ul class="lang_options">
                <li v-for="entry in entries" @click="switchText($index)" :class="{'active': activeIndex == $index}">
                    {{entry.language}}
                </li>
            </ul>
            <div id="editor"></div>
        </div>
    </div>
</template>

<script>
    var Quill = require('quill');
    export default {
        props: ['active-view'],
        data: function () {
          return{
              activeIndex: '',
              active: '',
              entries: '',
              biography: ''
          }
        },
        ready() {
            this.biography = new Quill('#editor', {
                readOnly: true,
                theme: 'bubble'
            });
            this.fetchWork();
        },
        computed: {
            activeBio: function () {
                if(this.active){
                    return this.$options.filters.filterFor(this.entries, this.active, 'id')[0];
                }
                return null;
            },
        },
        methods: {
            fetchWork: function () {
                this.getHttp('/auth/contents/biography', this.setBio);
            },
            setBio: function (results) {
                this.entries = results.data;
                this.active = this.entries[0].id;
                this.setText(this.activeBio.text);
            },
            switchText: function ($index) {
                this.activeIndex = $index;
                        this.setText(this.entries[$index].text);
            },
            setText: function (text) {
                var bio_data = JSON.parse(text);
                this.biography.setContents(bio_data);
            },
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                };
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
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
            returnIndex: function ($array, filterBy, filterIn) {
                var filterlist = $array;
                var arrayLength = filterlist.length;
                for (var i = 0; i < arrayLength; i++) {
                    if(filterlist[i][filterIn] == filterBy){
                        return i;
                    }
                }
            },
        }
    }
</script>
