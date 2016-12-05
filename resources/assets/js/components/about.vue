<template>
    <div class="content full flex-column-center">
        <div class="about--fab">
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
        methods: {
            fetchWork: function () {
                this.getHttp('/auth/contents/biography/1', this.setBio);
            },
            setBio: function (results) {
                var bio_data = JSON.parse(results.data[0].text);
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
        }
    }
</script>
