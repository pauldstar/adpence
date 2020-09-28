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

        <div id="d-balance" class="text-white ml-auto" role="button" title="Withdraw Credits">
            @include('partials.logo')
            <span id="sp-balance">{{ $balance }}</span>
        </div>
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
            let $username = document.getElementById('p-username')

            new bootstrap.Popover($username);

            $username.addEventListener('shown.bs.popover', function () {
                this.href = this.dataset.href;
            });

            $username.addEventListener('hide.bs.popover', function () {
                this.href = '#';
            });
        </script>
    @endif

    <script>
        let $balance = document.getElementById('sp-balance'),
            $balanceWrapper = document.getElementById('d-balance');

        Livewire.on('balance-increment', balance => {
            $balance.innerText = balance;
            $balanceWrapper.classList.add('wobble');
            setTimeout(_ => $balanceWrapper.classList.remove('wobble'), 800);
        })
    </script>
@endpush

@push('styles')
    <style>
        #p-username:focus {
            outline: none;
            box-shadow: none;
        }

        .wobble {
            -webkit-animation-name: wobble;
            animation-name: wobble;
            -webkit-animation-duration: 0.8s;
            -webkit-animation-iteration-count: infinite;
            -webkit-animation-timing-function: linear;
            -webkit-transform-origin: 50% 100%;
        }

        @-webkit-keyframes wobble {
            0% {
                -webkit-transform: none;
                transform: none;
            }
            15% {
                -webkit-transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
                transform: translate3d(-25%, 0, 0) rotate3d(0, 0, 1, -5deg);
            }
            30% {
                -webkit-transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
                transform: translate3d(20%, 0, 0) rotate3d(0, 0, 1, 3deg);
            }
            45% {
                -webkit-transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
                transform: translate3d(-15%, 0, 0) rotate3d(0, 0, 1, -3deg);
            }
            60% {
                -webkit-transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
                transform: translate3d(10%, 0, 0) rotate3d(0, 0, 1, 2deg);
            }
            75% {
                -webkit-transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
                transform: translate3d(-5%, 0, 0) rotate3d(0, 0, 1, -1deg);
            }
            100% {
                -webkit-transform: none;
                transform: none;
            }
        }
    </style>
@endpush
