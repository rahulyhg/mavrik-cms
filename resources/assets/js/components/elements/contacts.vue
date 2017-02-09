<template>
    <div class="contact-section flex-column-center">
        <div class="contact--fab">
            <div class="contact-cards">
                <div class="outline contact-divider"></div>
                <div class="contact" v-for="contact in contacts">
                    <div class="contact--content">
                        <span>{{contact.title}}</span>
                        <span>{{contact.address}}</span>
                        <span>{{contact.telephone}}</span>
                        <span>{{contact.name}}</span>
                        <span>{{contact.email}}</span>
                    </div>
                </div>
                <div class="outline contact-liner"></div>
            </div>
            <div class="social_media">
                <div class="shelf top_shelf">
                    <a v-for="media in socials | orderBy 'position' | limitBy 3" class="social" :href="media.path">
                        <img class="social_icons" :src="media.image" :alt="media.name">
                    </a>
                </div>
                <div class="shelf bottom_shelf">
                    <a v-for="media in socials | orderBy 'position' | limitBy 6 3" class="social" :href="media.path">
                        <img class="social_icons" :src="media.image" :alt="media.name">
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                contacts:'',
                socials: ''
            }
        },
        ready (){
            this.fetchContacts();
        },
        methods: {
            //vue resource methods
            fetchContacts: function () {
                this.getHttp('/auth/contacts', this.setContacts);
                this.getHttp('/auth/social_media', this.setMedia);
            },
            setContacts: function (results) {
                return this.contacts = results.data;
            },
            setMedia: function (results) {
              this.socials = results.data;
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
