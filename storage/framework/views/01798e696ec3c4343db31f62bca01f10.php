<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('hideNavbar', true); ?>
<?php $__env->startSection('hideFooter', true); ?>

<?php $__env->startSection('additionalCSS'); ?>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo e(asset('styles/auth.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="login-page-wrapper">

        <ul class="theme-picker">
            <li data-theme="barney" class="barney"></li>
            <li data-theme="firewatch" class="firewatch"></li>
            <li data-theme="citrus" class="citrus"></li>
            <li data-theme="marsh" class="marsh"></li>
            <li data-theme="frost" class="frost"></li>
            <li data-theme="slate" class="slate"></li>
            <li data-theme="candy" class="candy"></li>
        </ul>

        <form action="/login" method="POST" class="form auth-3d-form">
            <?php echo csrf_field(); ?>

            <div class="header-section">
                <img src="<?php echo e(asset('images/logo.jpeg')); ?>" alt="Logo" class="auth-logo">
                <h1>AndRPaid</h1>
                <p>Welcome Back!!!</p>
            </div>

            <?php if(session('errorLogin') || $errors->any()): ?>
                <div class="theme-alert">
                    <i class='bx bx-error-circle'></i>
                    <span><?php echo e(session('errorLogin') ?? 'Invalid credentials'); ?></span>
                </div>
            <?php endif; ?>

            <div class="input-wrapper">
                <input type="email" name="email" placeholder="Enter your email" required autocomplete="email"
                    value="<?php echo e(old('email')); ?>" />
                <i class="bx bxs-user-circle"></i>
            </div>

            <div class="input-wrapper">
                <input type="password" name="password" placeholder="Enter your password" required
                    autocomplete="current-password" />
                <i class="bx bx-key"></i>
            </div>

            <div class="form-links">
                <a href="/login/forgot-password">Forgot password?</a>
            </div>

            <div class="button-wrapper">
                <button type="submit">
                    Sign In
                    <i class="bx bx-right-arrow-alt"></i>
                </button>
            </div>

            <div class="form-footer">
                <p>New here? <a href="/register">Create an account</a></p>
            </div>
        </form>

        <?php if(session('success')): ?>
            <div class="modal fade custom-modal-backdrop" id="statusModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content custom-modal-content type-success text-center p-4">
                        <div class="modal-body px-4 py-4">
                            <div class="modal-icon-wrapper mb-4 mx-auto">
                                <i class="bi bi-check-lg custom-icon"></i>
                            </div>
                            <h4 class="fw-bold mb-3 heading-text">Success!</h4>
                            <p class="text-muted mb-4 fs-5"><?php echo e(session('success')); ?></p>
                            <button type="button" class="btn btn-custom w-100 py-3 fw-bold shadow-sm"
                                data-bs-dismiss="modal">
                                CONTINUE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const themeList = document.querySelector(".theme-picker");
                const defaultThemeItem = themeList.firstElementChild;
                const themeListItems = themeList.childNodes;

                const backgroundWrapper = document.querySelector('.login-page-wrapper');
                const form = document.querySelector(".form");

                const themeState = {
                    selected: null,
                    set: (t) => {
                        themeState.selected = t;
                    },
                    get: () => themeState.selected
                };

                const itemState = {
                    selected: null,
                    set: (i) => {
                        itemState.selected = i;
                    },
                    get: () => itemState.selected
                };

                function init() {
                    themeListItems.forEach(el => el.addEventListener("click", handleThemeChange));

                    if (defaultThemeItem) {
                        const defaultTheme = defaultThemeItem.dataset.theme;

                        setTheme(defaultTheme);

                        setSelectedThemeItem(defaultThemeItem);
                    }
                }

                function handleThemeChange(event) {
                    let selectedItem = event.target;
                    if (!selectedItem.dataset.theme) return;

                    let selectedTheme = selectedItem.dataset.theme;

                    if (!selectedItem.classList.contains("pressed") && !form.classList.contains("rotate")) {

                        form.classList.add("rotate");

                        setSelectedThemeItem(selectedItem);

                        setTimeout(() => {
                            setTheme(selectedTheme);
                        }, 600);

                        setTimeout(() => {
                            form.classList.remove("rotate");
                        }, 1200);
                    }
                }

                function setTheme(selectedTheme) {
                    if (themeState.get()) {
                        backgroundWrapper.classList.remove(themeState.get());
                    }
                    themeState.set(selectedTheme);
                    backgroundWrapper.classList.add(themeState.get());
                }

                function setSelectedThemeItem(selectedItem) {
                    const current = itemState.get();
                    if (current) current.classList.remove("pressed");

                    itemState.set(selectedItem);
                    selectedItem.classList.add("pressed");
                }

                init();

                if (window.bootstrap && document.getElementById('statusModal')) {
                    setTimeout(() => {
                        var myModal = new bootstrap.Modal(document.getElementById('statusModal'));
                        myModal.show();
                    }, 300);
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Data D\BINUS FILES\Web Programming\andrpaid\resources\views/pages/login.blade.php ENDPATH**/ ?>