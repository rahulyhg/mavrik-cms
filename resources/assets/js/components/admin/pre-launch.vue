<template>
    <div class="container f-h of-sc">
        <div v-if="isPreMessage" class="pre-launch f-h">
            <img class="working-svg" src="/image/svg/rocket.svg" alt="working--svg">
            <div class="not--message t-c">
                <h1>
                    <span>3.2.1...</span>
                    Pre-Launch!
                </h1>
                <p>
                    I have made you a custom pre-launch web placeholder. As we work together on your project, we will
                    </br>
                    redirect all traffic to the pre-launch page with basic information about you and your projects.
                    <br>
                    Now everything is filled with dummy text, but you can change that!
                    </br>
                    When you are ready, edit the pre-launch info.
                </p>
                <span @click="removeMessage" class="hv">Edit</span>
            </div>
        </div>
        <div v-else class="pre-launch--edit">
            <div class="edit--header">
                <span class="accent">Pre-Launch</span>
                <p>
                    I have made you a custom pre-launch web placeholder. As we work together on your project, we will
                    </br>
                    redirect all traffic to the pre-launch page with basic information about you and your projects.
                    <br>
                    Now everything is filled with dummy text, but you can change that!
                    </br>
                    When you are ready, edit the pre-launch info.
                </p>
            </div>
            <div class="panel--container">
                <div class="panel--main">
                    <div class="panel position-ref">
                        <div class="panel--sideBar"></div>
                        <div class="panel--header accent">
                            About
                        </div>
                        <div class="panel--content m-t-xs">
                            <textarea name="about" id="aboutMe" cols="30" rows="10" v-model="about">{{dummyText}}</textarea>
                        </div>
                        <div class="panel--footer">
                            <button @click="addMaterial('about')">save</button>
                        </div>
                    </div>
                    <div class="panel position-ref">
                        <div class="panel--sideBar"></div>
                        <div class="panel--header accent">
                            Work
                        </div>
                        <div class="panel--content m-t-xs">
                            <textarea name="work" id="workMe" cols="30" rows="10" v-model="work">{{dummyText}}</textarea>
                        </div>
                        <div class="panel--footer">
                            <button @click="addMaterial('work')">save</button>
                        </div>
                    </div>
                    <div class="contact--panel">
                        <div class="panel position-ref">
                            <div class="panel--sideBar"></div>
                            <div class="panel--header accent">
                                Phone
                            </div>
                            <div class="panel--content m-t-xs">
                                <div class="phone--repository">
                                    <ul>
                                        <li v-for="mobile in mobileRepository">
                                            {{mobile}}
                                        </li>
                                    </ul>
                                </div>
                                <input type="text" v-model="mobile" placeholder="678-543-210">
                                <button @click="addMaterial('mobile')">save</button>
                            </div>
                        </div>
                        <div class="panel position-ref">
                            <div class="panel--sideBar"></div>
                            <div class="panel--header accent">
                                Email
                            </div>
                            <div class="panel--content m-t-xs">
                                <div class="email--repository">
                                    <ul>
                                        <li v-for="email in emailRepository">
                                            {{email}}
                                        </li>
                                    </ul>
                                </div>
                                <input type="text" v-model="email" placeholder="test@gmail.com...">
                                <button @click="addMaterial('email')">add</button>
                            </div>
                        </div>
                        <div class="panel position-ref">
                            <div class="panel--sideBar"></div>
                            <div class="panel--header accent">
                                Social Media
                            </div>
                            <div class="panel--content m-t-xs">
                                <div class="social--repository">
                                    <ul>
                                        <li v-for="social in socialRepository">
                                            <a href="{{social.link}}">
                                                {{social.name}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <input type="text" v-model="socialName" placeholder="Facebook, Twitter...">
                                <input type="text" v-model="socialLink" placeholder="Enter full link to the social platform...">
                                <button @click="addMaterial('social')">add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                isPreMessage: true,
                dummyText: 'Lorem ipsum dolor sit amet, movet imperdiet philosophia sea et, harum vocibus conclusionemque ut vim, omnium constituam per no. Pri an soleat corpora. Ut tation causae pro, an quo nihil munere phaedrum, pro ex nihil zril fabellas. Libris efficiantur his ei. Aliquam commune sed ea, et per saperet accusamus signiferumque. Eam ea quot tempor philosophia. Te mel deleniti adipiscing. His virtute tractatos ex, odio diam mollis id mel. Zril percipit nec et, has id postea legendos, eu sit nostrud fabellas. Mollis delectus prodesset ex nec. Minimum noluisse oportere his cu, sit ad aeque detraxit. Mea atqui tantas menandri ei, vix dolores assueverit id.',
                about: '',
                work: '',
                mobile: '',
                email: '',
                socialName: '',
                socialLink: '',
                emailRepository: [],
                mobileRepository: [],
                socialRepository: [],

            }
        },
        methods: {
            removeMessage: function () {
                this.isPreMessage = false;
            },
            addMaterial: function (material) {
                if(this.validate(material)){
                    var data = this.setData(material);
                    if(material == 'social'){
                        this[material + 'Repository'].push(this.createLinkObject(this.socialName, this.socialLink));
                        this.clear('socialLink')
                        return this.clear('socialName');
                    }
                    this.sendHttp('/auth/pre_launch', data, this.successLaunch);
                    return this.clear(material);
                }
            },
            setData: function (material) {
                var data;
              switch (material){
                  case 'work':
                      data = {
                          'position': material,
                          'text': this.work
                      }
                  break;
                  case 'about':
                      data = {
                          'position': material,
                          'text': this.about
                      }
                      break;
                  case 'mobile':
                      data = {
                          'position': material,
                          'mobile': this.mobile
                      }
                      break;
                  case 'social':
                      data = {
                          'position': material,
                          'text': this.socialName,
                          'link': this.socialLink
                      }
                      break;
                  case 'email':
                      data = {
                          'position': material,
                          'text': this.email
                      }
                      break;
                  case 'launch':
                      data = {
                          'position': material,
                          'launch': this.launch
                      }
                      break;
                  default:
                      return;

              }
                return data;
            },
            successLaunch: function (results) {
                if(results.position == 'email' || results.position == 'social' || results.position == 'mobile'){
                    this[results.position + 'Repository'].push(this[results.position]);
                }
            },
            clear: function (point) {
                return this[point] = '';
            },
            validate: function (material) {
                switch (material)
                {
                    case 'work':
                        return this.checkText(this.work);
                    break;
                    case 'about':
                        return this.checkText(this.about);
                    break;
                    case 'email':
                        return this.checkEmail(this.email);
                    break;
                    case 'mobile':
                        return this.checkMobile(this.mobile);
                    break;
                    case 'social':
                        return this.checkSocial(this.socialName, this.socialLink);
                    break;
                    default:
                        return false;
                    break;
                }
            },
            checkText: function (text) {
                if(text.length > 0){
                    return true;
                }
                return false;
            },
            checkEmail: function (email) {
                var at = email.indexOf('@'),
                        com = email.indexOf('.com');
                if(at > -1 && com > -1){
                    return true;
                }
                return false;
            },
            checkMobile: function (mobile) {
                if(mobile.length > 0){
                    var chars = mobile.split('');
                    for(var i = 0; i < chars.length; i++){
                        if(isNaN(chars[i])){
                            if(chars[i] != '-'){
                                return false;
                            }
                        }
                    }
                    return true;
                }
                return false;
            },
            checkSocial: function (name, link) {
                if(name.length > 0 && link.length > 0){
                    return this.checkLink(link);
                }
                return false;
            },
            checkLink: function (link) {
              var htTp = link.indexOf('http://'),
                      htTps = link.indexOf('https://'),
                      com = link.indexOf('.com');

                if(com > -1 && htTp > -1 || com && htTps > -1){
                    return true;
                }

                return false;
            },
            createLinkObject: function (p1, p2) {
                return {
                    'name': p1,
                    'link': p2
                }
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

                var self = this;

                this.$http.post(url, data, params).then(callback).catch(err => self.errorLog(err));
            },
        }
    }
</script>
