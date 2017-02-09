@extends('layouts.app')

@section('content')
        <div id="fabiana" class="flex-center full position-ref ">
            <div class="content full flex-row-center">
                <div v-cloak class="content--nav basis--30 tran-05" :class="{'nav--active': activeReel}">
                    <div class="nav-callout">
                        <h1>
                            <span>FABIANA FORMICA</span>
                            <span class="title-underline underline_grey"></span>
                        </h1>
                        <span>Actress</span>
                    </div>
                    <ul v-el:link-box class="nav-list">
                        <li v-for="view in views" @click="seeView(view, $index)" class="hv">@{{view}}</li>
                    </ul>
                </div>
                <div class="content--component basis--100 tran-05" :class="{'content--active': activeReel}">
                    <div class="menu--toggle" v-show="view == 'bio' || view == 'media' || view == 'contact'">
                        <img src="/image/svg/ic_menu_white_24px.svg" alt="" @click="toggleMenu">
                    </div>
                    <showreel v-show="view == 'showreel'" :active-view="view" :reel="filteredReelMaterials" transition="fade"></showreel>
                    <bio v-show="view == 'bio'" :active-view="view" transition="fade"></bio>
                    <photos v-show="view == 'media'" :masonry="msnryObj" :active-view="view" :side-menu="activeReel" transition="fade"></photos>
                    <contact v-show="view == 'contact'" :active-view="view" transition="fade"></contact>
                </div>
            </div>
        </div>
@endsection
