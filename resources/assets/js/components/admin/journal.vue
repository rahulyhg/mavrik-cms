<template>
    <div class="content">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="breadcrumb">Content</a>
                    <a href="#!" class="breadcrumb">Biography</a>
                </div>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li @click="toggleModal"><a href="#">Add Bio</a></li>
                    <li><a href="#">Clear All</a></li>
                </ul>
            </div>
        </nav>
        <div class="component--state">
            <div class="entry--card">
                <div class="submit--header">
                    <button v-show="isReadySubmit" class="btn waves-effect waves-light" type="submit" name="action" @click="updateEntry">Update
                        <i class="material-icons right">send</i>
                    </button>
                    <div class="language--container">
                        <!-- Dropdown Trigger -->
                        <a class='dropdown-button btn' href='#' @click="dropActive = true;">{{activeLanguage}}</a>

                        <!-- Dropdown Structure -->
                        <ul class='dropdown-content' :class="{'drop-active': dropActive}">
                            <li v-for="bio in biographies" @click="switchBio(bio.language, bio.id)"><a href="#!">{{bio.language}}</a></li>
                        </ul>
                    </div>
                    <a class="waves-effect waves-teal btn-flat" @click="deleteBio">Delete</a>
                </div>
                <div id="editor"></div>
            </div>
        </div>
        <!-- Modal Structure -->
        <div class="modal--container" v-show="isInitModal">
            <div v-el:upload class="modal modal-language" :class="{'modal--show': isModalShown}">
                <div class="modal-header" :class="{'salmon': isModalError}">
                    <h4>Add New Biography</h4>
                </div>
                <div class="modal-content">
                    <div class="row">
                        <form class="col s12">
                            <div class="row modal-row">
                                <div class="input-field col s6">
                                    <i class="material-icons prefix"><img src="/image/svg/ic_add_circle_black_24px.svg" alt=""></i>
                                    <input id="icon_prefix" type="text" class="validate" v-model="new_language" autocomplete="off" @keyup="initSearch">
                                    <label for="icon_prefix">New Bio Language</label>
                                    <ul class="dropdown-content dropdown-modal" :class="{'drop-active': isSearch}">
                                        <li class="language" v-for="language in languageResults" @click="selectLanguage(language.name)">
                                            <span>{{language.name}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="waves-effect waves-teal btn-flat" @click="toggleModal">cancel</a>
                    <a class="waves-effect waves-light btn salmon" v-show="pickedLanguage" @click="submitNewEntry">button</a>
                </div>
            </div>
            <div id="materialize-overlay" class="lean--overlay" :class="{'hide--overlay': !isOverlay}" transition="fadeOverlay"></div>
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
                biographies: [],
                active: '',
                activeLanguage: '',
                new_language: '',
                isoLangs: [
                {
                    "name":"Abkhaz",
                    "nativeName":"аҧсуа"
                },
                {
                    "name":"Afar",
                    "nativeName":"Afaraf"
                },
                {
                    "name":"Afrikaans",
                    "nativeName":"Afrikaans"
                },
                {
                    "name":"Akan",
                    "nativeName":"Akan"
                },
                {
                    "name":"Albanian",
                    "nativeName":"Shqip"
                },
                {
                    "name":"Amharic",
                    "nativeName":"አማርኛ"
                },
                {
                    "name":"Arabic",
                    "nativeName":"العربية"
                },
                {
                    "name":"Aragonese",
                    "nativeName":"Aragonés"
                },
                {
                    "name":"Armenian",
                    "nativeName":"Հայերեն"
                },
                {
                    "name":"Assamese",
                    "nativeName":"অসমীয়া"
                },
                {
                    "name":"Avaric",
                    "nativeName":"авар мацӀ, магӀарул мацӀ"
                },
                {
                    "name":"Avestan",
                    "nativeName":"avesta"
                },
                {
                    "name":"Aymara",
                    "nativeName":"aymar aru"
                },
                {
                    "name":"Azerbaijani",
                    "nativeName":"azərbaycan dili"
                },
                {
                    "name":"Bambara",
                    "nativeName":"bamanankan"
                },
                {
                    "name":"Bashkir",
                    "nativeName":"башҡорт теле"
                },
                {
                    "name":"Basque",
                    "nativeName":"euskara, euskera"
                },
                {
                    "name":"Belarusian",
                    "nativeName":"Беларуская"
                },
                {
                    "name":"Bengali",
                    "nativeName":"বাংলা"
                },
                {
                    "name":"Bihari",
                    "nativeName":"भोजपुरी"
                },
                {
                    "name":"Bislama",
                    "nativeName":"Bislama"
                },
                {
                    "name":"Bosnian",
                    "nativeName":"bosanski jezik"
                },
                {
                    "name":"Breton",
                    "nativeName":"brezhoneg"
                },
                {
                    "name":"Bulgarian",
                    "nativeName":"български език"
                },
                {
                    "name":"Burmese",
                    "nativeName":"ဗမာစာ"
                },
                {
                    "name":"Catalan",
                    "nativeName":"Català"
                },
                {
                    "name":"Chamorro",
                    "nativeName":"Chamoru"
                },
                {
                    "name":"Chechen",
                    "nativeName":"нохчийн мотт"
                },
                {
                    "name":"Chichewa",
                    "nativeName":"chiCheŵa, chinyanja"
                },
                {
                    "name":"Chinese",
                    "nativeName":"中文 (Zhōngwén), 汉语, 漢語"
                },
                {
                    "name":"Chuvash",
                    "nativeName":"чӑваш чӗлхи"
                },
                {
                    "name":"Cornish",
                    "nativeName":"Kernewek"
                },
                {
                    "name":"Corsican",
                    "nativeName":"corsu, lingua corsa"
                },
                {
                    "name":"Cree",
                    "nativeName":"ᓀᐦᐃᔭᐍᐏᐣ"
                },
                {
                    "name":"Croatian",
                    "nativeName":"hrvatski"
                },
                {
                    "name":"Czech",
                    "nativeName":"česky, čeština"
                },
                {
                    "name":"Danish",
                    "nativeName":"dansk"
                },
                {
                    "name":"Divehi",
                    "nativeName":"ދިވެހި"
                },
                {
                    "name":"Dutch",
                    "nativeName":"Nederlands, Vlaams"
                },
                {
                    "name":"English",
                    "nativeName":"English"
                },
                {
                    "name":"Esperanto",
                    "nativeName":"Esperanto"
                },
                {
                    "name":"Estonian",
                    "nativeName":"eesti, eesti keel"
                },
                {
                    "name":"Ewe",
                    "nativeName":"Eʋegbe"
                },
                {
                    "name":"Faroese",
                    "nativeName":"føroyskt"
                },
                {
                    "name":"Fijian",
                    "nativeName":"vosa Vakaviti"
                },
                {
                    "name":"Finnish",
                    "nativeName":"suomi, suomen kieli"
                },
                {
                    "name":"French",
                    "nativeName":"français, langue française"
                },
                {
                    "name":"Fula",
                    "nativeName":"Fulfulde, Pulaar, Pular"
                },
                {
                    "name":"Galician",
                    "nativeName":"Galego"
                },
                {
                    "name":"Georgian",
                    "nativeName":"ქართული"
                },
                {
                    "name":"German",
                    "nativeName":"Deutsch"
                },
                {
                    "name":"Greek, Modern",
                    "nativeName":"Ελληνικά"
                },
                {
                    "name":"Guaraní",
                    "nativeName":"Avañeẽ"
                },
                {
                    "name":"Gujarati",
                    "nativeName":"ગુજરાતી"
                },
                {
                    "name":"Haitian",
                    "nativeName":"Kreyòl ayisyen"
                },
                {
                    "name":"Hausa",
                    "nativeName":"Hausa, هَوُسَ"
                },
                {
                    "name":"Hebrew (modern)",
                    "nativeName":"עברית"
                },
                {
                    "name":"Herero",
                    "nativeName":"Otjiherero"
                },
                {
                    "name":"Hindi",
                    "nativeName":"हिन्दी, हिंदी"
                },
                {
                    "name":"Hiri Motu",
                    "nativeName":"Hiri Motu"
                },
                {
                    "name":"Hungarian",
                    "nativeName":"Magyar"
                },
                {
                    "name":"Interlingua",
                    "nativeName":"Interlingua"
                },
                {
                    "name":"Indonesian",
                    "nativeName":"Bahasa Indonesia"
                },
                {
                    "name":"Interlingue",
                    "nativeName":"Originally called Occidental; then Interlingue after WWII"
                },
                {
                    "name":"Irish",
                    "nativeName":"Gaeilge"
                },
                {
                    "name":"Igbo",
                    "nativeName":"Asụsụ Igbo"
                },
                {
                    "name":"Inupiaq",
                    "nativeName":"Iñupiaq, Iñupiatun"
                },
                {
                    "name":"Ido",
                    "nativeName":"Ido"
                },
                {
                    "name":"Icelandic",
                    "nativeName":"Íslenska"
                },
                {
                    "name":"Italian",
                    "nativeName":"Italiano"
                },
                {
                    "name":"Inuktitut",
                    "nativeName":"ᐃᓄᒃᑎᑐᑦ"
                },
                {
                    "name":"Japanese",
                    "nativeName":"日本語 (にほんご／にっぽんご)"
                },
                {
                    "name":"Javanese",
                    "nativeName":"basa Jawa"
                },
                {
                    "name":"Kalaallisut, Greenlandic",
                    "nativeName":"kalaallisut, kalaallit oqaasii"
                },
                {
                    "name":"Kannada",
                    "nativeName":"ಕನ್ನಡ"
                },
                {
                    "name":"Kanuri",
                    "nativeName":"Kanuri"
                },
                {
                    "name":"Kashmiri",
                    "nativeName":"कश्मीरी, كشميري‎"
                },
                {
                    "name":"Kazakh",
                    "nativeName":"Қазақ тілі"
                },
                {
                    "name":"Khmer",
                    "nativeName":"ភាសាខ្មែរ"
                },
                {
                    "name":"Kikuyu, Gikuyu",
                    "nativeName":"Gĩkũyũ"
                },
                {
                    "name":"Kinyarwanda",
                    "nativeName":"Ikinyarwanda"
                },
                {
                    "name":"Kirghiz, Kyrgyz",
                    "nativeName":"кыргыз тили"
                },
                {
                    "name":"Komi",
                    "nativeName":"коми кыв"
                },
                {
                    "name":"Kongo",
                    "nativeName":"KiKongo"
                },
                {
                    "name":"Korean",
                    "nativeName":"한국어 (韓國語), 조선말 (朝鮮語)"
                },
                {
                    "name":"Kurdish",
                    "nativeName":"Kurdî, كوردی‎"
                },
                {
                    "name":"Kwanyama, Kuanyama",
                    "nativeName":"Kuanyama"
                },
                {
                    "name":"Latin",
                    "nativeName":"latine, lingua latina"
                },
                {
                    "name":"Luxembourgish, Letzeburgesch",
                    "nativeName":"Lëtzebuergesch"
                },
                {
                    "name":"Luganda",
                    "nativeName":"Luganda"
                },
                {
                    "name":"Limburgish, Limburgan, Limburger",
                    "nativeName":"Limburgs"
                },
                {
                    "name":"Lingala",
                    "nativeName":"Lingála"
                },
                {
                    "name":"Lao",
                    "nativeName":"ພາສາລາວ"
                },
                {
                    "name":"Lithuanian",
                    "nativeName":"lietuvių kalba"
                },
                {
                    "name":"Luba-Katanga",
                    "nativeName":""
                },
                {
                    "name":"Latvian",
                    "nativeName":"latviešu valoda"
                },
                {
                    "name":"Manx",
                    "nativeName":"Gaelg, Gailck"
                },
                {
                    "name":"Macedonian",
                    "nativeName":"македонски јазик"
                },
                {
                    "name":"Malagasy",
                    "nativeName":"Malagasy fiteny"
                },
                {
                    "name":"Malay",
                    "nativeName":"bahasa Melayu, بهاس ملايو‎"
                },
                {
                    "name":"Malayalam",
                    "nativeName":"മലയാളം"
                },
                {
                    "name":"Maltese",
                    "nativeName":"Malti"
                },
                {
                    "name":"Māori",
                    "nativeName":"te reo Māori"
                },
                {
                    "name":"Marathi (Marāṭhī)",
                    "nativeName":"मराठी"
                },
                {
                    "name":"Marshallese",
                    "nativeName":"Kajin M̧ajeļ"
                },
                {
                    "name":"Mongolian",
                    "nativeName":"монгол"
                },
                {
                    "name":"Nauru",
                    "nativeName":"Ekakairũ Naoero"
                },
                {
                    "name":"Navajo, Navaho",
                    "nativeName":"Diné bizaad, Dinékʼehǰí"
                },
                {
                    "name":"Norwegian Bokmål",
                    "nativeName":"Norsk bokmål"
                },
                {
                    "name":"North Ndebele",
                    "nativeName":"isiNdebele"
                },
                {
                    "name":"Nepali",
                    "nativeName":"नेपाली"
                },
                {
                    "name":"Ndonga",
                    "nativeName":"Owambo"
                },
                {
                    "name":"Norwegian Nynorsk",
                    "nativeName":"Norsk nynorsk"
                },
                {
                    "name":"Norwegian",
                    "nativeName":"Norsk"
                },
                {
                    "name":"Nuosu",
                    "nativeName":"ꆈꌠ꒿ Nuosuhxop"
                },
                {
                    "name":"South Ndebele",
                    "nativeName":"isiNdebele"
                },
                {
                    "name":"Occitan",
                    "nativeName":"Occitan"
                },
                {
                    "name":"Ojibwe, Ojibwa",
                    "nativeName":"ᐊᓂᔑᓈᐯᒧᐎᓐ"
                },
                {
                    "name":"Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic",
                    "nativeName":"ѩзыкъ словѣньскъ"
                },
                {
                    "name":"Oromo",
                    "nativeName":"Afaan Oromoo"
                },
                {
                    "name":"Oriya",
                    "nativeName":"ଓଡ଼ିଆ"
                },
                {
                    "name":"Ossetian, Ossetic",
                    "nativeName":"ирон æвзаг"
                },
                {
                    "name":"Panjabi, Punjabi",
                    "nativeName":"ਪੰਜਾਬੀ, پنجابی‎"
                },
                {
                    "name":"Pāli",
                    "nativeName":"पाऴि"
                },
                {
                    "name":"Persian",
                    "nativeName":"فارسی"
                },
                {
                    "name":"Polish",
                    "nativeName":"polski"
                },
                {
                    "name":"Pashto, Pushto",
                    "nativeName":"پښتو"
                },
                {
                    "name":"Portuguese",
                    "nativeName":"Português"
                },
                {
                    "name":"Quechua",
                    "nativeName":"Runa Simi, Kichwa"
                },
                {
                    "name":"Romansh",
                    "nativeName":"rumantsch grischun"
                },
                {
                    "name":"Kirundi",
                    "nativeName":"kiRundi"
                },
                {
                    "name":"Romanian, Moldavian, Moldovan",
                    "nativeName":"română"
                },
                {
                    "name":"Russian",
                    "nativeName":"русский язык"
                },
                {
                    "name":"Sanskrit (Saṁskṛta)",
                    "nativeName":"संस्कृतम्"
                },
                {
                    "name":"Sardinian",
                    "nativeName":"sardu"
                },
                {
                    "name":"Sindhi",
                    "nativeName":"सिन्धी, سنڌي، سندھی‎"
                },
                {
                    "name":"Northern Sami",
                    "nativeName":"Davvisámegiella"
                },
                {
                    "name":"Samoan",
                    "nativeName":"gagana faa Samoa"
                },
                {
                    "name":"Sango",
                    "nativeName":"yângâ tî sängö"
                },
                {
                    "name":"Serbian",
                    "nativeName":"српски језик"
                },
                {
                    "name":"Gaelic",
                    "nativeName":"Gàidhlig"
                },
                {
                    "name":"Shona",
                    "nativeName":"chiShona"
                },
                {
                    "name":"Sinhala, Sinhalese",
                    "nativeName":"සිංහල"
                },
                {
                    "name":"Slovak",
                    "nativeName":"slovenčina"
                },
                {
                    "name":"Slovene",
                    "nativeName":"slovenščina"
                },
                {
                    "name":"Somali",
                    "nativeName":"Soomaaliga, af Soomaali"
                },
                {
                    "name":"Southern Sotho",
                    "nativeName":"Sesotho"
                },
                {
                    "name":"Spanish",
                    "nativeName":"español, castellano"
                },
                {
                    "name":"Sundanese",
                    "nativeName":"Basa Sunda"
                },
                {
                    "name":"Swahili",
                    "nativeName":"Kiswahili"
                },
                {
                    "name":"Swati",
                    "nativeName":"SiSwati"
                },
                {
                    "name":"Swedish",
                    "nativeName":"svenska"
                },
                {
                    "name":"Tamil",
                    "nativeName":"தமிழ்"
                },
                {
                    "name":"Telugu",
                    "nativeName":"తెలుగు"
                },
                {
                    "name":"Tajik",
                    "nativeName":"тоҷикӣ, toğikī, تاجیکی‎"
                },
                {
                    "name":"Thai",
                    "nativeName":"ไทย"
                },
                {
                    "name":"Tigrinya",
                    "nativeName":"ትግርኛ"
                },
                {
                    "name":"Tibetan Standard, Tibetan, Central",
                    "nativeName":"བོད་ཡིག"
                },
                {
                    "name":"Turkmen",
                    "nativeName":"Türkmen, Түркмен"
                },
                {
                    "name":"Tagalog",
                    "nativeName":"Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔"
                },
                {
                    "name":"Tswana",
                    "nativeName":"Setswana"
                },
                {
                    "name":"Tonga (Tonga Islands)",
                    "nativeName":"faka Tonga"
                },
                {
                    "name":"Turkish",
                    "nativeName":"Türkçe"
                },
                {
                    "name":"Tsonga",
                    "nativeName":"Xitsonga"
                },
                {
                    "name":"Tatar",
                    "nativeName":"татарча, tatarça, تاتارچا‎"
                },
                {
                    "name":"Twi",
                    "nativeName":"Twi"
                },
                {
                    "name":"Tahitian",
                    "nativeName":"Reo Tahiti"
                },
                {
                    "name":"Uighur, Uyghur",
                    "nativeName":"Uyƣurqə, ئۇيغۇرچە‎"
                },
                {
                    "name":"Ukrainian",
                    "nativeName":"українська"
                },
                {
                    "name":"Urdu",
                    "nativeName":"اردو"
                },
                {
                    "name":"Uzbek",
                    "nativeName":"zbek, Ўзбек, أۇزبېك‎"
                },
                {
                    "name":"Venda",
                    "nativeName":"Tshivenḓa"
                },
                {
                    "name":"Vietnamese",
                    "nativeName":"Tiếng Việt"
                },
                {
                    "name":"Volapük",
                    "nativeName":"Volapük"
                },
                {
                    "name":"Walloon",
                    "nativeName":"Walon"
                },
                {
                    "name":"Welsh",
                    "nativeName":"Cymraeg"
                },
                {
                    "name":"Wolof",
                    "nativeName":"Wollof"
                },
                {
                    "name":"Western Frisian",
                    "nativeName":"Frysk"
                },
                {
                    "name":"Xhosa",
                    "nativeName":"isiXhosa"
                },
                {
                    "name":"Yiddish",
                    "nativeName":"ייִדיש"
                },
                {
                    "name":"Yoruba",
                    "nativeName":"Yorùbá"
                },
                {
                    "name":"Zhuang, Chuang",
                    "nativeName":"Saɯ cueŋƅ, Saw cuengh"
                }
            ],
                isSearch: false,
                pickedLanguage: false,
                dropActive: false,
                isReadySubmit: false,
                isInitModal: false,
                isOverlay: false,
                isModalShown: false
            }
        },
        computed: {
            activeBio: function () {
                if(this.active){
                    return this.$options.filters.filterFor(this.biographies, this.active, 'id')[0];
                }
                return null;
            },
            languageResults: function () {
                var format_search = this.$options.filters.toTitleCase(this.new_language);
                return this.$options.filters.filterInclude(this.isoLangs, format_search, 'name');
            },
        },
        ready(){
            var self = this;
            this.entry = new Quill('#editor', {
                theme: 'snow'
            });
            this.getHttp('/auth/contents/biography', this.setBio);
            this.entry.on('text-change', function(delta, oldDelta, source) {
                if (source == 'user') {
                    return self.isReadySubmit = true;
                }
            });
        },
        methods:{
            setBio: function (results) {
                if(results.data.length <= 0){
                    return this.toggleModal();
                }
                this.active = results.data[0].id;
                this.fillEntry(results.data[0].text);
                this.activeLanguage = results.data[0].language;
                this.biographies = results.data;
            },
            deleteBio: function () {
                this.deleteHttp('/auth/contents/' + this.activeBio.id, this.removeBio);
            },
            removeBio: function (results) {
                var index = this.$options.filters.returnIndex(this.biographies, results.data.content_id, 'id')[0];
                this.biographies.splice(index, 1);
                if(this.biographies.length > 0){
                    this.switchBio(this.biographies[0].language, this.biographies[0].id);
                } else{
                    this.fillEntry('');
                    this.toggleModal();
                }
            },
            switchBio: function (language, id) {
                this.activeLanguage = language;
                this.active = id;
                this.fillEntry(this.activeBio.text);
                this.dropActive = false;
            },
            fillEntry:function (text) {
                if(text){
                    var bio_data = JSON.parse(text);
                    return this.entry.setContents(bio_data);
                }
                this.entry.setContents(' ');
            },
            toggleModal: function () {
                this.isInitModal = !this.isInitModal;
                this.isOverlay = !this.isOverlay;
                this.isModalShown = !this.isModalShown;

                if(!this.isInitModal){
                    this.new_language = '';
                    this.isSearch = false;
                    if(!this.activeBio){
                        this.toggleModal();
                    }
                }
            },
            updateEntry: function () {
                var delta = this.entry.getContents();

                var delta_data = JSON.stringify(delta);

                var data = this.activeBio;

                data.text = delta_data;

                this.updateHttp('/auth/contents/' + data.id, data, this.successUpdate);
            },

            successUpdate: function (results) {
                this.isReadySubmit = false;
                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
            },
            submitNewEntry: function () {
                var data = {
                    'id': this.user,
                    'name': '',
                    'text': '',
                    'type': 'biography',
                    'release_date': '',
                    'status': '',
                    'language': this.new_language
                };

                this.sendHttp('/auth/contents', data, this.successEntry);
            },

            successEntry: function (results) {
                var $toastContent = $('<span>I am toast content</span>');
                Materialize.toast($toastContent, 5000);
                var bio=results.data[0];
                this.biographies.push(bio);

                this.switchBio(bio.language, bio.id);
                this.toggleModal();
            },

            initSearch: function () {
                if(this.new_language.length > 0){
                    return this.isSearch = true;
                }
                this.isSearch = false;
                this.pickedLanguage = false;
            },

            selectLanguage: function (language) {
              this.new_language = language;
                this.isSearch = false;
                this.pickedLanguage = true;
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
            filterInclude: function ($array, filterBy, filterIn) {
                var filtered = [];
                var filterlist = $array;
                var arrayLength = filterlist.length;
                for (var i = 0; i < arrayLength; i++) {
                    var string = filterlist[i][filterIn];
                    if(string.indexOf(filterBy) !== -1){
                        filtered.push(filterlist[i]);
                    }
                }
                return filtered;
            },
            toTitleCase: function(str)
            {
                return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
            },
        }
    }
</script>
