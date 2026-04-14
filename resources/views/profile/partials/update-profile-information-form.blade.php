<section>

    <!-- HEADER -->
    <div class="mb-4">
        <h5 class="fw-bold">
            {{ __('Profile Information') }}
        </h5>

        <p class="text-muted">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </div>

    <!-- VERIFY EMAIL FORM -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- MAIN FORM -->
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <!-- NAME -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>

            <input id="name" name="name" type="text"
                   class="form-control"
                   value="{{ old('name', $user->name) }}"
                   required autofocus>

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- EMAIL -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>

            <input id="email" name="email" type="email"
                   class="form-control"
                   value="{{ old('email', $user->email) }}"
                   required>

            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">

                    <p class="text-warning">
                        {{ __('Your email address is unverified.') }}
                    </p>

                    <button form="send-verification"
                            class="btn btn-sm btn-outline-primary">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif

                </div>
            @endif
        </div>

        <!-- BUTTON -->
        <div class="d-flex align-items-center gap-3">

            <button type="submit" class="btn btn-primary">
                Save
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success">
                    ✔ Saved.
                </span>
            @endif

        </div>

    </form>

</section>