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
                        ? `<img src="/public/uploads/${user.pfpfilename}" class="rounded-circle border border-secondary shadow-sm mb-2" style="width: 80px; height: 80px; object-fit: cover;">` 
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
});
</script>
</body>
</html>
