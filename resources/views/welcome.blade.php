@extends('layouts.app')

@section('content')
        <div class="flex-center full position-ref">
            <div class="content full flex-row-center">
                <div v-cloak class="content--nav flex-column-center basis--30 tran-05" :class="{'nav--active': activeReel}">
                    <ul v-el:link-box class="flex-column-center space-ard">
                        <li v-for="view in views" @click="seeView(view, $index)" class="hv">@{{view}}</li>
                    </ul>
                </div>
                <div class="content--component basis--100 tran-05" :class="{'content--active': activeReel}">
                    <showreel v-show="view == 'Showreel'" :active-view="view" :reel="filteredReelMaterials"></showreel>
                    <bio v-show="view == 'Bio'" :active-view="view"></bio>
                    <photos v-show="view == 'Photos'" :active-view="view"></photos>
                    <videos v-show="view == 'Videos'" :active-view="view"></videos>
                    <contact v-show="view == 'Contact'" :active-view="view"></contact>
                </div>
            </div>
        </div>
@endsection
