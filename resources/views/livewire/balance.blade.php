<div class="ml-auto">
    <div
        wire:click="setToken"
        id="d-balance"
        data-toggle="modal"
        data-target="#modal--withdraw"
        role="button"
        class="text-white"
    >
        @include('partials.logo')
        <span id="sp-balance">{{ $balance }}</span>
    </div>

    <x-modal id="modal--withdraw" wire:ignore>
        <x-slot name="title">Withdraw Token</x-slot>

        <div class="input-group position-relative">
            <svg
                wire:target="setToken" wire:loading
                id="withdraw-token--loader" version="1.1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                enable-background="new 0 0 0 0" xml:space="preserve">
                <circle fill="#424242" stroke="none" cx="6" cy="50" r="6">
                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite"
                             begin="0.1"></animate>
                </circle>
                <circle fill="#424242" stroke="none" cx="26" cy="50" r="6">
                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite"
                             begin="0.2"></animate>
                </circle>
                <circle fill="#424242" stroke="none" cx="46" cy="50" r="6">
                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite"
                             begin="0.3"></animate>
                </circle>
            </svg>

            <input
                wire:model="withdrawToken"
                wire:loading.class="text-hide"
                wire:target="setToken"
                id="withdraw-token--input"
                type="text"
                class="form-control text-center"
                readonly
            >

            <span
                id="withdraw-token--copy"
                class="input-group-prepend input-group-text"
                role="button">Copy</span>
        </div>
    </x-modal>
</div>

@push('body-scripts')
    <script>
        let $balance = document.getElementById('sp-balance'),
            $balanceWrapper = document.getElementById('d-balance'),
            $tokenInput = document.getElementById('withdraw-token--input'),
            $copyToken = document.getElementById('withdraw-token--copy');

        Livewire.on('increment-balance', _ => wobble($balanceWrapper));

        $copyToken.addEventListener('click', _ => {
            $tokenInput.select();
            $tokenInput.setSelectionRange(0, 99999); /*For mobile devices*/
            document.execCommand('copy');
            $copyToken.innerHTML = 'Copied!';
        });
    </script>
@endpush

@push('styles')
    <style>
        #withdraw-token--loader {
            width: 52px;
            position: absolute;
            bottom: -81px;
            z-index: 10;
            left: 187px;
        }
    </style>
@endpush
