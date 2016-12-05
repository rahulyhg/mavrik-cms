<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="breadcrumb">Content</a>
                    <a href="#!" class="breadcrumb">Biography</a>
                </div>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="#">Add Contact</a></li>
                    <li><a href="#">Clear All</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <div class="entry--card">
                <div class="submit--header">
                    <button v-show="isReadySubmit && isSet" class="btn waves-effect waves-light" type="submit" name="action" @click="updateEntry">Update
                        <i class="material-icons right">send</i>
                    </button>
                    <button v-show="isReadySubmit && !isSet" class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewEntry">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
                <div id="editor"></div>
            </div>
        </div>
    </div>
</template>
<script>
    var Quill = require('quill');
    export default{
        props:['token', 'user'],
        data(){
            return{
                entry:'',
                bio: '',
                isSet: '',
                isReadySubmit: false
            }
        },
        ready(){
            var self = this;
            this.entry = new Quill('#editor', {
                theme: 'snow'
            });
            this.getHttp('/auth/contents/biography/' + this.user, this.setBio);
            this.entry.on('text-change', function(delta, oldDelta, source) {
                if (source == 'user') {
                    return self.isReadySubmit = true;
                }
            });
        },
        methods:{
            setBio: function (results) {
                var bio_data = JSON.parse(results.data[0].text);
                this.entry.setContents(bio_data);
                this.bio = results.data[0];
                this.isSet = true;

            },
            updateEntry: function () {
                var delta = this.entry.getContents();

                var delta_data = JSON.stringify(delta);

                var data = this.bio;

                data.text = delta_data;

                this.updateHttp('/auth/contents/' + data.id, data, this.successUpdate);
            },

            successUpdate: function (results) {
                this.isReadySubmit = false;
                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
            },
            submitNewEntry: function () {
                var delta = this.entry.getContents();

                var delta_data = JSON.stringify(delta);
                var data = {
                    'id': this.user,
                    'name': '',
                    'text': delta_data,
                    'type': 'biography',
                    'release_date': '',
                    'status': ''
                };

                this.sendHttp('/auth/contents', data, this.successEntry);
            },

            successEntry: function () {
                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
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
        }
    }
</script>
