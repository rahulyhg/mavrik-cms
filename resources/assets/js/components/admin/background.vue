<template>
    <div class="container">
        <div class="content">
            <div class="content--video-upload gutters">
                <video-upload></video-upload>
            </div>
        </div>
    </div>
</template>

<script>
    import videoUpload from '../elements/material-video-upload.vue'
    export default{
        props: ['token'],
        data(){
            return{
                msg:'fuck'
            }
        },
        components:{
            videoUpload
        },
        methods: {
            successVideoUpload: function (results) {
                console.log(results);
            },
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
            deleteHttp: function (url,data,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                }
                this.$http.delete(url, data,params).then(callback).catch(err => console.error(err));
            },
            updateHttp: function (url, data, callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    },
                    dataType: 'json'
                }

                this.$http.put(url, data, params).then(callback).catch(err => console.error(err));
            },
        },
        events: {
            'ready-material-video': function (file) {
                var formData = new FormData();
                formData.append('video', file);
                formData.append('type', 'video');
                this.sendHttp('/auth/material', formData, this.successVideoUpload);
            }
        }
    }
</script>
