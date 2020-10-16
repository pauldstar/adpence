<div class="input-group position-relative">
    <svg wire:loading id="withdraw-token--loader" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" enable-background="new 0 0 0 0" xml:space="preserve">
        <circle fill="#424242" stroke="none" cx="6" cy="50" r="6">
            <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1"></animate>
        </circle>
        <circle fill="#424242" stroke="none" cx="26" cy="50" r="6">
            <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2"></animate>
        </circle>
        <circle fill="#424242" stroke="none" cx="46" cy="50" r="6">
            <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3"></animate>
        </circle>
    </svg>

    <input type="text" class="form-control text-center" disabled>

    <span
        tabindex="0"
        data-toggle="popover"
        data-trigger="focus"
        data-placement="right"
        data-content="Copied"
        class="input-group-prepend input-group-text material-icons"
        role="button">content_paste</span>
</div>
