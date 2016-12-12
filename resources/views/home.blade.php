@extends('layouts.admin')

@section('content')
<div class="container f-h">
    <div class="component--row f-h">
        <div class="component--nav position-ref f-h">
            <div class="nav-portal f-w">
                <div class="portal--icon"></div>
                <div class="portal--session">
                    <span class="hv">Logout</span>
                </div>
            </div>
            <div class="nav--greet f-h">
                <div class="nav--welcome">
                    <span class="sub-title">Welcome</span>
                    <span class="title">{{ Auth::user()->name}}</span>
                </div>
                <div class="nav--selects--container m-t-sm">
                    <ul class="collection">
                        <li v-for="view in views">
                            <a href="#!" class="collection-item caps" @click="changeView(view)">@{{view}}</a>
                        </li>
                        <li>
                            <a href="#!" class="no-padding">
                                <ul class="collapsible" data-collapsible="accordion">
                                    <li>
                                        <a href="#!" class="collapsible-header collection-item caps">Content</a>
                                        <div class="collapsible-body">
                                            <ul>
                                                <li><a href="#!" class="collection-item caps" @click="changeView('journal')">Biography</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </a>
                        </li>
                <li>
                    <a href="#!" class="no-padding">
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <a href="#!" class="collapsible-header collection-item caps">Contact Info</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="#!" class="collection-item caps" @click="changeView('contact')">Contact</a></li>
                                        <li><a href="#!" class="collection-item caps" @click="changeView('social media')">Social Media</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </a>
                </li>
            </ul>
                </div>
            </div>
        </div>
        <div class="component--block">
            <dashboard v-show="activeView=='dashboard'"></dashboard>
            <statistics v-show="activeView=='statistics'"></statistics>
            <background-video token='{{csrf_token()}}' v-show="activeView=='background'"></background-video>
            <photo-gallery token='{{csrf_token()}}' v-show="activeView=='photo galleries'"></photo-gallery>
            <social token='{{csrf_token()}}' v-show="activeView=='video gallery'"></social>
            <social-media token='{{csrf_token()}}' v-show="activeView=='social media'"></social-media>
            <contact token='{{csrf_token()}}' user="{{Auth::user()->id}}" v-show="activeView=='contact'"></contact>
            <journal token='{{csrf_token()}}' user="{{Auth::user()->id}}" v-show="activeView=='journal'"></journal>
        </div>
    </div>
</div>
@endsection
