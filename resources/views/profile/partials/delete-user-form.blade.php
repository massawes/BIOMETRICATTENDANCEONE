<section>

    <!-- HEADER -->
    <div class="mb-4">
        <h5 class="fw-bold">
            {{ __('Delete Account') }}
        </h5>

        <p class="text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>

    <!-- BUTTON -->
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
        {{ __('Delete Account') }}
    </button>

    <!-- MODAL -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <!-- HEADER -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Are you sure you want to delete your account?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <p class="text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
                        </p>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Enter password">

                            @if ($errors->userDeletion->get('password'))
                                <small class="text-danger">
                                    {{ $errors->userDeletion->first('password') }}
                                </small>
                            @endif
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Delete Account
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</section>