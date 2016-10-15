@extends('layouts.app')

@section('content')
        <div class="flex-center full position-ref">
            <div class="content full flex-row-center">
                <div class="content--nav flex-column-center basis--30 tran-05" :class="{'nav--inactive': activeReel}">
                    <ul v-el:link-box class="flex-column-center space-ard">
                        <li v-for="view in views" @click="seeView(view, $index)" @mouseenter="moveSpan($index)" @mouseleave="returnSpan" class="hv">@{{view}}</li>
                    </ul>
                </div>
                <div class="content--component basis--100 tran-05" :class="{'content--active': !activeReel}">
                    <background v-show="view == 'reel'" :active-view="view"></background>
                    <about v-show="view == 'About'" :active-view="view"></about>
                    <work v-show="view == 'Work'" :active-view="view"></work>
                    <contact v-show="view == 'Contact'" :active-view="view"></contact>
                    <writing v-show="view == 'Writing'" :active-view="view"></writing>
                </div>
            </div>
        </div>
@endsection
