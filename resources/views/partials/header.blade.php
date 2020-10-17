<div class="text-white p-3">
    <div class="container d-flex h5">
        @if(auth()->guest())
            <p
                title="Save Ad Credits"
                role="button"
                data-toggle="modal"
                data-target="#modal--login"
            >Login</p>
        @else
            <a
                id="p-username"
                class="text-decoration-none text-white"
                tabindex="0"
                role="button"
                data-href="{{ route('logout') }}"
                data-toggle="popover"
                data-trigger="focus"
                data-placement="bottom"
                data-content="Click again to logout"
            >{{ auth()->user()->name }}</a>
        @endif

        @livewire('balance')
    </div>
</div>

@if(auth()->guest())
    <x-modal id="modal--login">
        <x-slot name="title">Login</x-slot>

        <div class="d-flex justify-content-center">
            <a href="{{ route('login', ['driver' => 'facebook']) }}">
                <ion-icon name="logo-facebook" class="mr-4 text-primary font-size-64"></ion-icon>
            </a>

            <a href="{{ route('login', ['driver' => 'google']) }}">
                <ion-icon name="logo-google" class="text-danger font-size-64"></ion-icon>
            </a>
        </div>
    </x-modal>
@endif

@push('body-scripts')
    @if(auth()->guest())
        <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
    @else
        <script>
            let $username = document.getElementById('p-username');

            $username.addEventListener('shown.bs.popover', function () {
                this.href = this.dataset.href;
            });

            $username.addEventListener('hide.bs.popover', function () {
                this.href = '#';
            });
        </script>
    @endif
@endpush

@push('styles')
    <style>
        #p-username:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
@endpush
