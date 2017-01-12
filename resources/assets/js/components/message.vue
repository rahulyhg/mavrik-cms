<template>
    <div class="content full flex-column-center">
        <div class="message--form" v-show="isNewMessage" transition="fade">
            <div class="contact-form">
                <input type="text" v-model="message.name" placeholder="Your Name">
                <input type="email" v-model="message.email" placeholder="Your Email">
                <input type="email" v-model="message.subject" placeholder="Subject">
                <textarea name="message" cols="30" rows="5" v-model="message.text" placeholder="Write a Message..."></textarea>
            </div>
            <div class="send-form">
                <span v-show="isFormReady" @click="sendForm" transition="fade">Send</span>
            </div>
        </div>
        <div class="message--response" v-show="successMessage" transition="fade">
            <h1>Thank you for visiting my site!</h1>
            <p>I'll get back to you as soon as I can.</p>
        </div>
        <social></social>
    </div>
</template>
<script>
    import social from '../components/elements/contacts.vue';
    export default{
        components: {social},
        data(){
            return{
                timer: 0,
                message: {
                    'name':'',
                    'email': '',
                    'subject': '',
                    'text': '',
                },
                isNewMessage: true,
                successMessage: false
            }
        },
        computed: {
            isFormReady: function () {
                if(this.message.name && this.message.email && this.message.text){
                    return true;
                }
                return false;
            }
        },
        methods: {
            sendForm: function () {
                this.sendHttp('/auth/notify/email', this.message, this.thankYou);
            },
            thankYou: function (results) {
                if(results.data.status == 'success'){
                    var self = this;
                    this.isNewMessage = false;
                    clearTimeout(this.timer);
                    this.timer = setTimeout(function () {
                        self.successMessage = true;
                    }, 1000);
                }
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
        }
    }
</script>
