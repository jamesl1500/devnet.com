@extends('layouts.logged')

@section('title', 'Dashboard Feed')

@php $pageTitle = 'Dashboard'; $pageBreadcrumb = 'Following'; $pageSubtitle = 'View posts from people you follow'; @endphp

@section('content')
    <div class="page page-dashboard">
        @include('pages.dashboard.includes.head')
        <div class="page-inner container">
            <div class="page-row row">
                <div class="page-row-column sidebar col">
                    @include('pages.dashboard.includes.left_side')
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
                            @if($posts->count() > 0)
                                @foreach($posts as $post)
                                    <x-post post="{{ $post }}" />
                                @endforeach
                                <div class="pagination-links">
                                    {{ $posts->links() }}
                                </div>
                            @else
                                <p>No posts found.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="page-row-column interactive col">
                    @include('pages.dashboard.includes.right_side')
                </div>
            </div>
        </div>
    </div>
@endsection