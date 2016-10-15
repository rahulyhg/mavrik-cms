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
                    <span class="title">Fabiana Formica</span>
                </div>
                <div class="nav--selects--container m-t-sm">
                    <ul class="nav--selects">
                        <li class="caps hv" v-for="view in views" @click="changeView(view)">
                            @{{view}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="component--block">
            <dashboard v-show="activeView=='dashboard'"></dashboard>
            <statistics v-show="activeView=='statistics'"></statistics>
            <background-video token='{{csrf_token()}}' v-show="activeView=='background'"></background-video>
        </div>
    </div>
</div>
@endsection
