<template>
    <div class="content--scroll full flex-column-center">
        <div class="repository">
            <div class="grid">
                <div class="grid-sizer"></div>
                <div class="repository--material grid-item" v-for="material in repository">
                    <template v-if="material.type == 'image'">
                        <img :src="material.path">
                    </template>
                    <template v-else>
                        <img :src="material.credit">
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['active-view'],
        ready() {
            this.fetchWork();
        },
        data: function () {
            return {
                repository: '',
                masonryObject: ''
            }
        },
        methods: {
            fetchWork: function () {
                this.getHttp('/auth/materials/active', this.setWork);
            },
            setWork: function (results) {
              this.repository = results.data;
            },
            getHttp: function (url,callback) {
                const params = {
                    headers: {
                        'X-CSRF-TOKEN': this.token
                    }
                }
                this.$http.get(url, params).then(callback).catch(err => console.error(err));
            }
        }
    }
</script>
