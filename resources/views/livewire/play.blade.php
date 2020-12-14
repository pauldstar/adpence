<div>
    <svg
        id="btn--play"
        wire:click="play"
        version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px" y="0px" height="100px" width="100px" viewBox="0 0 100 100"
        enable-background="new 0 0 100 100" xml:space="preserve"
    >
        <path class="stroke-solid" fill="none" stroke="white"
              d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7 C97.3,23.7,75.7,2.3,49.9,2.5"/>
        <path class="stroke-dotted" fill="none" stroke="white"
              d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7 C97.3,23.7,75.7,2.3,49.9,2.5"/>
        <path class="icon" fill="white"
              d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z"/>
    </svg>

    <x-modal id="modal--video">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <!-- 16:9 aspect ratio -->
        <div class="embed-responsive embed-responsive-16by9">
            <iframe
                id="iframe--video"
                class="embed-responsive-item"
                allowscriptaccess="always"
                allowfullscreen="allowfullscreen"
                allow="autoplay"
            ></iframe>
        </div>
    </x-modal>
</div>

@push('body-scripts')
    <script>
        let $videoModal = document.getElementById('modal--video'),
            videoBsModal = new bootstrap.Modal($videoModal),
            videoIframe = document.getElementById('iframe--video');

        Livewire.on('play-video', url => {
            videoBsModal.show();
            videoIframe.setAttribute('src', url);
        });

        $videoModal.addEventListener('hidden.bs.modal', _=> {
            videoIframe.setAttribute('src', '');
            Livewire.emit('video-stopped');
        });

        Livewire.on('no-payment', _ => {
            Toast.error('No Payment.<br>Watch the video longer than 1 minute.');
        });

        Livewire.on('payment-received', amount => {
            Toast.success('Paid ' + amount + ' <span class="aether-font">9p</span>');
        });
    </script>
@endpush

@push('styles')
    <style>
        #modal--video .modal-dialog {
            max-width: 800px;
            margin: 30px auto;
        }

        #modal--video .modal-body {
            position: relative;
            padding: 0;
        }

        #modal--video .close {
            position: absolute;
            right: -30px;
            top: 0;
            z-index: 999;
            font-size: 2rem;
            font-weight: normal;
            color: #fff;
            opacity: 1;
            padding: 0;
            border: 0;
            background-color: transparent;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        #iframe--video {
            background: #000000;
        }

        .embed-responsive {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden;
        }

        .embed-responsive .embed-responsive-item, .embed-responsive embed, .embed-responsive iframe, .embed-responsive object, .embed-responsive video {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .embed-responsive-16by9::before {
            padding-top: 56.25%;
        }

        .embed-responsive::before {
            display: block;
            content: "";
        }

        #btn--play {
            cursor: pointer;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateY(-50%) translateX(-50%);
        }

        .stroke-dotted {
            opacity: 0;
            stroke-dasharray: 4, 5;
            stroke-width: 1px;
            transform-origin: 50% 50%;
            animation: spin 4s infinite linear;
            transition: opacity 1s ease,
            stroke-width 1s ease;
        }

        .stroke-solid {
            stroke-dashoffset: 0;
            stroke-dashArray: 300;
            stroke-width: 4px;
            transition: stroke-dashoffset 1s ease,
            opacity 1s ease;
        }

        #btn--play:hover .stroke-dotted {
            stroke-width: 4px;
            opacity: 1;
        }

        #btn--play:hover .stroke-solid {
            opacity: 0;
            stroke-dashoffset: 300;
        }

        #btn--play:hover .icon {
            transform: scale(1.05);
        }

        .icon {
            transform-origin: 50% 50%;
            transition: transform 200ms ease-out;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush()
