<!-- Media Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalLabel">Manage Media</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Upload Section -->
                <div class="mb-4">
                    <h6 class="font-weight-bold mb-3">Upload New Files</h6>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="mediaFiles" multiple accept="image/*,.pdf">
                        <label class="custom-file-label" for="mediaFiles">Choose files...</label>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="uploadBtn">
                        <i class="fas fa-upload"></i> Upload Files
                    </button>
                    <small class="form-text text-muted">Images: JPEG, PNG, WebP (max 2MB). Documents: PDF (max 5MB)</small>
                </div>

                <!-- Existing Media -->
                <div>
                    <h6 class="font-weight-bold mb-3">Existing Media</h6>
                    <div class="row" id="mediaGrid">
                        <!-- Media items will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveMediaBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentProductId = null;
let selectedMedia = [];

function openMediaModal(productId) {
    currentProductId = productId;
    selectedMedia = [];

    // Load existing media
    loadMediaGrid();

    $('#mediaModal').modal('show');
}

function loadMediaGrid() {
    if (!currentProductId) return;

    fetch(`/admin/products/${currentProductId}/media`)
        .then(response => response.json())
        .then(data => {
            const mediaGrid = document.getElementById('mediaGrid');
            mediaGrid.innerHTML = '';

            if (data.images && data.images.length > 0) {
                data.images.forEach(media => {
                    const mediaItem = createMediaItem(media, 'image');
                    mediaGrid.appendChild(mediaItem);
                });
            }

            if (data.documents && data.documents.length > 0) {
                data.documents.forEach(media => {
                    const mediaItem = createMediaItem(media, 'document');
                    mediaGrid.appendChild(mediaItem);
                });
            }

            if (data.images.length === 0 && data.documents.length === 0) {
                mediaGrid.innerHTML = '<div class="col-12"><p class="text-muted text-center">No media files found.</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading media:', error);
        });
}

function createMediaItem(media, type) {
    const col = document.createElement('div');
    col.className = 'col-md-3 col-sm-6 mb-3';

    const card = document.createElement('div');
    card.className = 'card media-item';
    card.dataset.mediaId = media.id;

    let mediaContent = '';
    if (type === 'image') {
        mediaContent = `<img src="${media.url}" class="card-img-top" style="height: 120px; object-fit: cover;">`;
    } else {
        mediaContent = `
            <div class="card-body d-flex align-items-center justify-content-center" style="height: 120px;">
                <div class="text-center">
                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                    <p class="mb-0 mt-2 small">${media.name}</p>
                </div>
            </div>
        `;
    }

    const isPrimary = media.custom_properties && media.custom_properties.is_primary;

    card.innerHTML = `
        ${mediaContent}
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input media-checkbox" type="checkbox"
                           value="${media.id}" id="media_${media.id}">
                    <label class="form-check-label small" for="media_${media.id}">
                        Select
                    </label>
                </div>
                <div class="btn-group btn-group-sm">
                    ${type === 'image' ? `
                        <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="setAsPrimary(${media.id})"
                                ${isPrimary ? 'disabled' : ''}>
                            ${isPrimary ? 'Primary' : 'Set Primary'}
                        </button>
                    ` : ''}
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            onclick="deleteMediaFile(${media.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    col.appendChild(card);
    return col;
}

function setAsPrimary(mediaId) {
    if (!currentProductId) return;

    fetch(`/admin/products/${currentProductId}/media/${mediaId}/set-primary`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMediaGrid(); // Reload to show updated primary status
        }
    })
    .catch(error => {
        console.error('Error setting primary:', error);
    });
}

function deleteMediaFile(mediaId) {
    if (!currentProductId) return;

    if (!confirm('Are you sure you want to delete this file?')) return;

    fetch(`/admin/products/${currentProductId}/media/${mediaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMediaGrid(); // Reload to remove deleted item
        }
    })
    .catch(error => {
        console.error('Error deleting media:', error);
    });
}

// Upload functionality
document.getElementById('uploadBtn').addEventListener('click', function() {
    const files = document.getElementById('mediaFiles').files;
    if (files.length === 0) {
        alert('Please select files to upload');
        return;
    }

    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }

    fetch(`/admin/products/${currentProductId}/media/upload`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMediaGrid(); // Reload to show new uploads
            document.getElementById('mediaFiles').value = ''; // Clear file input
        } else {
            alert('Upload failed: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Upload failed');
    });
});

// Save selected media (if needed for future features)
document.getElementById('saveMediaBtn').addEventListener('click', function() {
    selectedMedia = [];
    document.querySelectorAll('.media-checkbox:checked').forEach(checkbox => {
        selectedMedia.push(checkbox.value);
    });

    $('#mediaModal').modal('hide');
    // Here you could trigger any actions with selected media
});
</script>