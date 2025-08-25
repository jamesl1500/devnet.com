@extends('layouts.logged')

@section('title', 'Dashboard Feed')

@php $pageTitle = 'Dashboard'; $pageBreadcrumb = 'Following'; $pageSubtitle = 'View posts from people you follow'; @endphp

@section('content')
    <div class="page page-dashboard">
        <div class="page-inner container">
            <div class="page-row row">
                <div class="page-row-column sidebar col">
                    left sidebar
                </div>
                <div class="page-row-column main col">
                    <div class="post-creation">
                        <x-dashboard.post-creation />
                    </div>
                    <div class="feed">
                        <div class="feed-navigation">
                            <x-dashboard.feed-navigation />
                        </div>
                        <div class="feed-content">

                        </div>
                    </div>
                </div>
                <div class="page-row-column interactive col">
                    right sidebar
                </div>
            </div>
        </div>
    </div>
@endsection