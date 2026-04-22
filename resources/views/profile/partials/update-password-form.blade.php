<section>

    <!-- HEADER -->
    <div class="mb-4">
        <h5 class="fw-bold">
            {{ __('Update Password') }}
        </h5>

        <p class="text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <!-- FORM -->
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <!-- CURRENT PASSWORD -->
        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
                Current Password
            </label>

            <input id="update_password_current_password"
                   name="current_password"
                   type="password"
                   class="form-control"
                   autocomplete="current-password">

            @if ($errors->updatePassword->get('current_password'))
                <small class="text-danger">
                    {{ $errors->updatePassword->first('current_password') }}
                </small>
            @endif
        </div>

        <!-- NEW PASSWORD -->
        <div class="mb-3">
            <label for="update_password_password" class="form-label">
                New Password
            </label>

            <input id="update_password_password"
                   name="password"
                   type="password"
                   class="form-control"
                   autocomplete="new-password">

            @if ($errors->updatePassword->get('password'))
                <small class="text-danger">
                    {{ $errors->updatePassword->first('password') }}
                </small>
            @endif
        </div>

        <!-- CONFIRM PASSWORD -->
        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">
                Confirm Password
            </label>

            <input id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   class="form-control"
                   autocomplete="new-password">

            @if ($errors->updatePassword->get('password_confirmation'))
                <small class="text-danger">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </small>
            @endif
        </div>

        <!-- BUTTON -->
        <div class="d-flex align-items-center gap-3">

            <button type="submit" class="btn btn-primary">
                Save
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success">
                    ✔ Saved.
                </span>
            @endif

        </div>

    </form>

</section>