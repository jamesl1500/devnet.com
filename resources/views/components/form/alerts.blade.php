<div class="alert-container alert-{{ $type }}">
    @if ($type === 'success' && $message)
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @elseif ($type === 'danger' && $message)
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @endif

    @if ($type === 'danger' && count($messages) > 1)
        <div class="alert alert-danger">
            <ul>
                @foreach ($messages as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>