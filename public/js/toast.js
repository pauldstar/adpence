const Toast = (_ => {
    let _$toast = document.getElementById('app-toast'),
        _$toastText = document.getElementById('app-toast-text'),
        _bsToast = new bootstrap.Toast(_$toast);

    function _show(msg, mode) {
        _$toast.classList.add('bg-' + mode);
        _$toastText.innerHTML = msg;

        _bsToast.show();

        let removeClass = _ => {
            _$toast.classList.remove('bg-' + mode);
            _$toast.removeEventListener('hidden.bs.toast', removeClass);
        };

        _$toast.addEventListener('hidden.bs.toast', removeClass);
    }

    return {
        success: msg => _show(msg, 'success'),
        error: msg => _show(msg, 'danger'),
        warning: msg => _show(msg, 'warning'),
        info: msg => _show(msg, 'info')
    };
})();
