</main> <!-- Closes the container opened in header.php -->

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileLinks = document.querySelectorAll('.profile-link');
    const userCache = {}; // Cache fetched profiles so we don't spam the API on re-hovers
    
    // Fallback image if PFP is null
    const getFallbackImg = () => `
        <div class="rounded-circle bg-secondary bg-opacity-25 border border-secondary shadow-sm d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 80px; height: 80px;">
            <span class="text-muted fs-1">?</span>
        </div>
    `;

    profileLinks.forEach(link => {
        const username = link.getAttribute('data-username');
        
        // Initialize the popover
        const popover = new bootstrap.Popover(link, {
            trigger: 'hover',
            placement: 'auto',
            html: true,
            sanitize: false,
            title: function() {
                return `<div class="fw-bold text-center">${username}</div>`;
            },
            content: function() {
                return userCache[username] || '<div class="text-center p-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';
            }
        });

        // Intercept mouse enter to fetch the actual data
        link.addEventListener('mouseenter', function () {
            // Already safely cached in our JS memory, the Popover config above will automatically read it
            if (userCache[username]) return;

            // Only fetch if it's the very first time we've hovered this specific user
            userCache[username] = '<div class="text-center p-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>'; // Prevent duplicate fetches

            fetch(`/api/user/${encodeURIComponent(username)}`)
                .then(response => response.json())
                .then(user => {
                    const roleBadge = user.role == 2 ? '<span class="badge bg-danger mt-1">Admin</span>' : '<span class="badge bg-primary mt-1">User</span>';
                    const pfpHtml = user.pfpfilename 
                        ? `<img src="<?= BASE_URL ?>/public/uploads/${user.pfpfilename}" class="rounded-circle border border-secondary shadow-sm mb-2" style="width: 80px; height: 80px; object-fit: cover;">` 
                        : getFallbackImg();
                    const bioText = user.bio ? `<div class="small text-light mt-2 text-start" style="white-space: pre-wrap; max-height: 100px; overflow-y: auto;">${user.bio}</div>` : '';
                    
                    const htmlContent = `
                        <div class="text-center" style="min-width: 150px;">
                            ${pfpHtml}
                            <div>${roleBadge}</div>
                            ${bioText}
                        </div>
                    `;

                    // Commit to cache so every other link pointing to this user works instantly
                    userCache[username] = htmlContent;
                    
                    // But we still need to forcefully inject it into *this* specific instance since it's already open and showing a spinner
                    const popoverInstance = bootstrap.Popover.getInstance(this);
                    if (popoverInstance && this.matches(':hover')) {
                        popoverInstance.setContent({
                            '.popover-header': `<div class="fw-bold text-center">${username}</div>`,
                            '.popover-body': htmlContent
                        });
                    }
                })
                .catch(err => {
                    console.error('Error fetching profile:', err);
                    delete userCache[username]; // Retry on next hover
                });
        });
    });

    // --- Image Hover Preview Plugin ---
    const previewContainer = document.createElement('div');
    previewContainer.id = 'image-hover-preview';
    previewContainer.style.position = 'fixed';
    previewContainer.style.display = 'none';
    previewContainer.style.zIndex = '9999';
    previewContainer.style.pointerEvents = 'none';
    
    const previewImg = document.createElement('img');
    // Ensure image does not exceed viewport bounds
    previewImg.style.maxWidth = (window.innerWidth - 40) + 'px';
    previewImg.style.maxHeight = (window.innerHeight - 40) + 'px';
    previewImg.style.objectFit = 'contain';
    previewImg.style.border = '2px solid #555';
    previewImg.style.backgroundColor = '#111';
    previewImg.className = 'shadow-lg rounded';
    
    previewContainer.appendChild(previewImg);
    document.body.appendChild(previewContainer);

    // Update max bounds on resize
    window.addEventListener('resize', () => {
        previewImg.style.maxWidth = (window.innerWidth - 40) + 'px';
        previewImg.style.maxHeight = (window.innerHeight - 40) + 'px';
    });

    document.querySelectorAll('.img-thumbnail').forEach(img => {
        img.addEventListener('mouseenter', function () {
            previewImg.src = this.src;
            previewContainer.style.display = 'block';
        });

        img.addEventListener('mousemove', function (e) {
            const offset = 20;
            let x = e.clientX + offset;
            let y = e.clientY + offset;

            // Dynamically shrink the image's max-width so it ALWAYS stays to the right of the cursor
            // without overflowing the right side of the browser viewport.
            let availableWidth = window.innerWidth - x - 20;
            previewImg.style.maxWidth = availableWidth + 'px';

            // Vertical collision detection (flip above cursor if it overflows the bottom)
            if (y + previewContainer.offsetHeight > window.innerHeight) {
                y = Math.max(0, e.clientY - previewContainer.offsetHeight - offset);
            }

            previewContainer.style.left = x + 'px';
            previewContainer.style.top = y + 'px';
        });

        img.addEventListener('mouseleave', function () {
            previewContainer.style.display = 'none';
            previewImg.src = '';
            // Reset to default on leave
            previewImg.style.maxWidth = (window.innerWidth - 40) + 'px';
        });
    });

    // --- Image Upload Preview ---
    const setupImagePreview = (inputId, containerId, imgId) => {
        const input = document.getElementById(inputId);
        const container = document.getElementById(containerId);
        const img = document.getElementById(imgId);
        
        if (input && container && img) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        container.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                } else {
                    img.src = '';
                    container.classList.add('d-none');
                }
            });
        }
    };
    
    setupImagePreview('boardAttachmentInput', 'boardImagePreviewContainer', 'boardImagePreview');
    setupImagePreview('replyAttachmentInput', 'replyImagePreviewContainer', 'replyImagePreview');

    // --- Post Inline Hover Preview ---
    const postPreviewContainer = document.createElement('div');
    postPreviewContainer.id = 'post-hover-preview';
    postPreviewContainer.style.position = 'fixed';
    postPreviewContainer.style.display = 'none';
    postPreviewContainer.style.zIndex = '9998'; // Just below image previews
    postPreviewContainer.style.pointerEvents = 'none';
    postPreviewContainer.style.maxWidth = '600px';
    postPreviewContainer.style.width = '100%';
    document.body.appendChild(postPreviewContainer);

    document.querySelectorAll('a[href^="#post-"]').forEach(link => {
        link.addEventListener('mouseenter', function (e) {
            const targetId = this.getAttribute('href').substring(1); // Remove the '#'
            const targetPost = document.getElementById(targetId);
            
            if (targetPost) {
                // Clone the post element
                const clone = targetPost.cloneNode(true);
                
                // Cleanup the clone to remove unnecessary elements for a preview
                clone.style.margin = '0';
                clone.className = 'card border-secondary shadow-lg bg-dark-subtle'; // Adjust classes for a floating preview
                
                // Remove interactive or clutter elements from the clone
                const elementsToRemove = clone.querySelectorAll('form[id^="delete-"], a[onclick*="submit()"], strong[onclick*="addReply"]');
                elementsToRemove.forEach(el => el.remove());

                // Remove the "No. ID" text block entirely for a cleaner look natively
                const noSpans = clone.querySelectorAll('.float-end');
                noSpans.forEach(span => {
                     if (span.textContent.includes('No.')) {
                         span.remove();
                     }
                });
                
                postPreviewContainer.innerHTML = '';
                postPreviewContainer.appendChild(clone);
                postPreviewContainer.style.display = 'block';
                
                // Initial positioning calculation
                let x = e.clientX + 15;
                let y = e.clientY + 15;
                
                // Ensure it doesn't bleed off the right edge
                if (x + postPreviewContainer.offsetWidth > window.innerWidth) {
                    x = e.clientX - postPreviewContainer.offsetWidth - 15;
                }
                
                // Ensure it doesn't bleed off the bottom edge
                if (y + postPreviewContainer.offsetHeight > window.innerHeight) {
                    y = e.clientY - postPreviewContainer.offsetHeight - 15;
                }
                
                postPreviewContainer.style.left = Math.max(0, x) + 'px';
                postPreviewContainer.style.top = Math.max(0, y) + 'px';
            }
        });

        link.addEventListener('mousemove', function (e) {
            if (postPreviewContainer.style.display === 'block') {
                let x = e.clientX + 15;
                let y = e.clientY + 15;

                if (x + postPreviewContainer.offsetWidth > window.innerWidth) {
                    x = e.clientX - postPreviewContainer.offsetWidth - 15;
                }

                if (y + postPreviewContainer.offsetHeight > window.innerHeight) {
                    y = e.clientY - postPreviewContainer.offsetHeight - 15;
                }

                postPreviewContainer.style.left = Math.max(0, x) + 'px';
                postPreviewContainer.style.top = Math.max(0, y) + 'px';
            }
        });

        link.addEventListener('mouseleave', function () {
            postPreviewContainer.style.display = 'none';
            postPreviewContainer.innerHTML = '';
        });
    });
});
</script>
</body>
</html>
