<?php
// Settings header include
use App\Models\Files;
if(auth()->user()->cover_id) {
    $cover = Files::find(auth()->user()->cover_id);
    $header_style = 'background-image: url(' . asset('storage/' . $cover->file_path) . ');';
} else {
    $header_style = '';
}
?>
<header class="settings-header" style="{{ $header_style }}">
    <div class="settings-header-inner">
        <div class="container">
            <h2>{{ $pageTitle }}</h2>
            <p>{{ $pageSubtitle }}</p>
            @if (!empty($pageBreadcrumb))
                <nav aria-label="breadcrumb" class="breadcrumbs">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageBreadcrumb }}</li>
                    </ol>
                </nav>
            @endif
        </div>
    </div>
</header>