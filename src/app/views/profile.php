<?php 
$title = "Your Profile - BORD";
require __DIR__ . '/header.php'; 
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
        <h1 class="display-5 text-center text-primary mb-4 fw-bold">Profile Settings</h1>
        
        <div class="card shadow border-secondary bg-dark-subtle">
            <div class="card-header bg-secondary text-white fw-bold">
                <?php echo htmlspecialchars($user['username']); ?>
            </div>
            <div class="card-body">
                <form method="POST" action="/profile/update" enctype="multipart/form-data">
                    
                    <!-- PFP Section -->
                    <div class="mb-4 text-center">
                        <?php if ($user['pfpfilename']): ?>
                            <div class="mb-3">
                                <img src="/public/uploads/<?php echo htmlspecialchars($user['pfpfilename']); ?>" class="rounded-circle border border-secondary" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                            </div>
                            <div class="form-check form-switch d-flex justify-content-center mb-3">
                                <input class="form-check-input me-2" type="checkbox" role="switch" id="remove_pfp" name="remove_pfp" value="1">
                                <label class="form-check-label text-light" for="remove_pfp">Remove Profile Picture</label>
                            </div>
                        <?php else: ?>
                            <div class="mb-3 d-flex align-items-center justify-content-center mx-auto rounded-circle bg-secondary bg-opacity-25 border border-secondary" style="width: 150px; height: 150px;">
                                <span class="text-muted fs-1">?</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-start">
                            <label class="form-label text-light fw-bold" id="pfpLabel">Upload New Avatar:</label>
                            <input class="form-control bg-dark text-light border-secondary" type="file" name="pfp" id="pfpInput" accept="image/png, image/jpeg, image/gif">
                            <input type="hidden" name="cropped_pfp" id="cropped_pfp">
                        </div>
                    </div>
                    
                    <hr class="border-secondary opacity-50 my-4">

                    <!-- Bio Section -->
                    <div class="mb-4">
                        <label class="form-label text-light fw-bold" for="bio">Profile Bio:</label>
                        <textarea class="form-control bg-dark text-light border-secondary" id="bio" name="bio" rows="4" placeholder="Tell everyone a bit about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        <div class="form-text text-muted mt-1" style="font-size: 0.85rem;">
                            Your bio will be displayed publicly on your profile page.
                        </div>
                    </div>

                    <hr class="border-secondary opacity-50 my-4">

                    <!-- Settings Section -->
                    <div class="mb-4">
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" role="switch" id="postasanon" name="postasanon" <?php echo $user['postasanon'] ? 'checked' : ''; ?>>
                            <label class="form-check-label text-light fw-bold ms-2" for="postasanon">Always Post as Anonymous</label>
                            <div class="form-text text-muted mt-1" style="font-size: 0.85rem;">
                                If checked, all your future posts and replies will be made under the name "Anonymous" and will not be linked to your account publicly.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Changes</button>
                    
                </form>
            </div>
            <div class="card-footer bg-dark-subtle border-top border-secondary text-center text-muted small py-3">
                Account Email: <?php echo htmlspecialchars($user['e-mail']); ?>
            </div>
        </div>
    </div>
</div>

<!-- Cropping Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-secondary">
      <div class="modal-header border-secondary bg-secondary">
        <h5 class="modal-title fw-bold" id="cropModalLabel">Crop Profile Picture</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="img-container w-100 bg-black" style="max-height: 60vh;">
            <img id="imageToCrop" src="" alt="Picture to crop" style="display: block; max-width: 100%;">
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary fw-bold" id="cropAndSaveBtn">Crop & Save</button>
      </div>
    </div>
  </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var pfpInput = document.getElementById('pfpInput');
    var imageToCrop = document.getElementById('imageToCrop');
    var cropModalElement = document.getElementById('cropModal');
    var cropModal = new bootstrap.Modal(cropModalElement);
    var cropper = null;

    pfpInput.addEventListener('change', function (e) {
        var files = e.target.files;
        if (files && files.length > 0) {
            var file = files[0];
            
            // Only handle images
            if (/^image\/\w+/.test(file.type)) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            }
        }
    });

    cropModalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // 1:1 square for PFPs
            viewMode: 2, // Restrict the crop box to not exceed the size of the canvas
            autoCropArea: 1,
            background: false
        });
    });

    cropModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // If they close without applying, reset the file input so they can trigger change event again if they want
        if (!document.getElementById('cropped_pfp').value) {
            pfpInput.value = '';
        }
    });

    document.getElementById('cropAndSaveBtn').addEventListener('click', function () {
        if (cropper) {
            var canvas = cropper.getCroppedCanvas({
                width: 256,
                height: 256
            });
            
            // Set the hidden input to the base64 string
            var croppedImageDataURL = canvas.toDataURL('image/jpeg', 0.9);
            document.getElementById('cropped_pfp').value = croppedImageDataURL;
            
            // Provide some visual feedback
            var oldLabel = document.getElementById('pfpLabel');
            if (oldLabel) oldLabel.innerHTML = 'Upload New Avatar: <span class="text-success ms-2">&#10003; Cropped and ready to proceed</span>';
            
            cropModal.hide();
        }
    });
});
</script>

<?php require __DIR__ . '/footer.php'; ?>
