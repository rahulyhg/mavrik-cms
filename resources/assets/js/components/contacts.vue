<template>
    <div class="content full flex-column-center">
        <div class="title m-b-md">
            <h1>
                <span>FABIANA FORMICA</span>
                <span class="title-underline"></span>
            </h1>
            <span>CONTACT</span>
        </div>
        <div class="about--fab">
            <div class="contact-cards">
                <div class="contact" v-for="contact in contacts">
                    <span>{{contact.title}}</span>
                    <span>{{contact.address}}</span>
                    <span>{{contact.telephone}}</span>
                    <span>{{contact.name}}</span>
                    <span>{{contact.email}}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                contacts:''
            }
        },
        ready (){
            this.fetchContacts();
        },
        methods: {
            //vue resource methods
            fetchContacts: function () {
                this.getHttp('/auth/contacts/1', this.setContacts);
            },
            setContacts: function (results) {
                return this.contacts = results.data;
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
