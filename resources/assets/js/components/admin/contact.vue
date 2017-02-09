<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="breadcrumb">Video Gallery</a>
                </div>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="#" @click="newContact">Add Contact</a></li>
                    <li><a href="#">Clear All</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <div class="repository--jumbo passive-jumbo"></div>
            <div v-for="contact in contacts" class="upload-box upload--centered contact-upload">
                <div class="upload--header">
                    <button v-show="contact.status == 'true'" class="btn waves-effect waves-light salmon delete-contact" name="action" @click="deleteContact($index)">
                        Delete
                    </button>
                    <div class="row">
                        <form class="col s12">
                            <div class="row">
                                <div class="input-field col s6">
                                    <i class="material-icons prefix">account_circle</i>
                                    <input id="icon_prefix" type="text" class="validate" v-model="contact.name">
                                    <label :class="{'active': contact.name}" for="icon_prefix">New Contact</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="upload--content">
                    <div class="upload--data upload-contact">
                        <div class="upload--instructions">
                            <h3>Contact Info</h3>
                            <p>We require some information about regarding your contact information. Please fill in the below form fields before submitting your new material.</p>
                        </div>
                        <div class=" contact-input">
                            <div class="row">
                                <form class="col s12 contact-info">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">person_pin</i>
                                            <input id="name" type="text" class="validate" v-model="contact.title">
                                            <label :class="{'active': contact.title}" for="name">Title: </label>
                                        </div>
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">email</i>
                                            <input id="email" type="email" class="validate" v-model="contact.email">
                                            <label :class="{'active': contact.email}" for="email" data-error="wrong" data-success="right">Email: </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">phone</i>
                                            <input id="icon_telephone" type="tel" class="validate" v-model="contact.telephone">
                                            <label :class="{'active': contact.telephone}" for="icon_telephone">Telephone: </label>
                                        </div>
                                        <div class="input-field col s12">
                                            <i class="material-icons prefix">language</i>
                                            <input id="credit" type="text" class="validate" v-model="contact.address">
                                            <label :class="{'active': contact.address}" for="credit" data-error="wrong" data-success="right">Address: </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="upload--footer">
                    <template v-if="contact.status =='true'">
                        <button class="btn waves-effect waves-light" type="submit" name="action" @click="updateContact($index)">Update
                            <i class="material-icons right">send</i>
                        </button>
                    </template>
                    <template v-else>
                        <button class="btn waves-effect waves-light" type="submit" name="action" @click="submitNewContact($index)">Submit
                            <i class="material-icons right">send</i>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        props: ['user'],
        data(){
            return{
                contacts: []
            }
        },
        ready (){
          this.fetchContacts();
        },
        methods:{
            newContact: function () {
                this.contacts.push(
                        {
                            'id': '',
                            'name': '',
                            'title': '',
                            'email': '',
                            'telephone': '',
                            'address': '',
                            'isSet': 'false'
                        }
                )
            },
            fetchContacts: function () {
              this.getHttp('/auth/contacts', this.setContacts);
            },
            setContacts: function (results) {
              if(results.data.length > 0){
                  return this.contacts = results.data;
              }
                this.newContact();
            },
            submitNewContact: function ($index) {
                var data = this.contacts[$index];
                data.id = this.user;
                this.sendHttp('/auth/contacts', data, this.successContact);
            },
            successContact: function (results) {
                var $newIndex = this.contacts.length - 1;

                this.contacts.splice($newIndex, 1);

                this.contacts.push(results.data[0]);

                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
            },
            deleteContact: function ($index) {
                var $id = this.contacts[$index].id;

                this.deleteHttp('/auth/contacts/' + $id, this.removeContact);
            },
            removeContact: function (results) {
                var filteredIndex = this.$options.filters.filterForIndex(this.contacts, results.data.material_id, 'id');

                this.contacts.splice(filteredIndex, 1);

                if(this.contacts.length <= 0){
                    this.newContact();
                }
            },
            updateContact: function ($index) {
                var $contact = this.contacts[$index];

                this.updateHttp('/auth/contacts/' + $contact.id, $contact, this.setUpdate);
            },
            setUpdate: function () {
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
        },
        filters: {
            filterForIndex: function ($array, filterBy, filterIn) {
                var filterlist = $array;
                var arrayLength = $array.length;
                for(var i = 0; i < arrayLength; i++){
                    if(filterlist[i][filterIn] == filterBy){
                        return i;
                    }
                }
            }
        }
    }
</script>
