@props([
    'id' => 'app-toast',
    'mode'
])

<div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x mt-5">
    <div
        class="
            toast
            d-flex align-items-center
            border-0
            text-white
            font-monospace
            @isset($mode) bg-{{ $mode }} @endisset
        "
        id="{{ $id }}"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
    >
        <div class="toast-body" id="{{ $id . '-text' }}">
            {{ $slot }}
        </div>

        <button
            type="button"
            class="btn-close btn-close-white ms-auto me-2"
            data-bs-dismiss="toast"
            aria-label="Close"
        ></button>
    </div>
</div>
