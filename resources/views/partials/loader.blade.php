<div id="aip-loader" class="aip-loader">
    <img src="{{ asset('assets/img/logoaip.svg') }}" alt="AIP Logo" class="aip-loader__logo">
</div>

<script>
    (function () {
        const loader = document.getElementById('aip-loader');

        if (loader && localStorage.getItem('theme') === 'dark') {
            loader.classList.add('aip-loader--dark');
        }
    })();
</script>

<style>
    .aip-loader {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body.dark-mode .aip-loader,
    .aip-loader.aip-loader--dark {
        background: #121212;
    }

    .aip-loader.fade-out {
        opacity: 0;
        pointer-events: none;
    }

    .aip-loader__logo {
        width: 140px;
        height: auto;
        transition: filter 0.25s ease;
    }

    body.dark-mode .aip-loader__logo,
    .aip-loader.aip-loader--dark .aip-loader__logo {
        filter: drop-shadow(0 10px 26px rgba(255, 255, 255, 0.16));
    }
</style>

<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById('aip-loader');

        if (!loader) {
            return;
        }

        loader.classList.add('fade-out');

        setTimeout(function () {
            loader.style.display = 'none';
        }, 700);
    });
</script>
