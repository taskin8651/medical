<style>
.media-modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:90;display:none;align-items:center;justify-content:center;padding:18px}
.media-modal-backdrop.is-open{display:flex}
.media-modal{width:min(920px,100%);max-height:90vh;overflow:auto;background:#fff;border-radius:14px;box-shadow:0 24px 70px rgba(15,23,42,.28)}
.media-modal-header,.media-modal-footer{padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;gap:12px}
.media-modal-footer{border-bottom:0;border-top:1px solid #e2e8f0;justify-content:flex-end}
.media-modal-body{padding:20px}
.media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px}
.media-card{border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;background:#f8fafc}
.media-card img{width:100%;height:132px;object-fit:cover;display:block}
.media-card-file{height:132px;display:flex;align-items:center;justify-content:center;text-align:center;padding:12px;color:#475569}
.media-card-body{padding:10px;display:grid;gap:8px}
.media-name{font-size:12px;color:#334155;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.media-actions{display:flex;gap:6px;flex-wrap:wrap}
.media-btn{display:inline-flex;align-items:center;gap:5px;border:1px solid #cbd5e1;background:#fff;color:#334155;border-radius:8px;padding:6px 8px;font-size:12px;font-weight:700;cursor:pointer;text-decoration:none}
.media-btn.danger{border-color:#fecaca;color:#b91c1c}
.media-btn.primary{border-color:color-mix(in srgb,var(--accent) 45%,#fff);color:var(--accent)}
.media-btn:disabled{opacity:.6;cursor:not-allowed}
</style>

<div class="media-modal-backdrop" id="mediaModal" aria-hidden="true">
    <div class="media-modal" role="dialog" aria-modal="true" aria-labelledby="mediaModalTitle">
        <div class="media-modal-header">
            <div>
                <h3 id="mediaModalTitle" style="font-size:16px;font-weight:800;color:#0f172a;margin:0">Manage Media</h3>
                <p style="font-size:12px;color:#64748b;margin:3px 0 0">Upload product images and PDF documents up to 20MB each.</p>
            </div>
            <button type="button" class="media-btn" onclick="closeMediaModal()">Close</button>
        </div>
        <div class="media-modal-body">
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:18px">
                <input type="file" id="mediaFiles" multiple accept="image/jpeg,image/png,image/webp,application/pdf,.pdf" class="field-input" style="max-width:420px;padding:8px 12px">
                <button type="button" class="btn-primary" id="uploadMediaBtn"><i class="fas fa-upload"></i> Upload</button>
            </div>
            <div id="mediaMessage" style="font-size:13px;margin-bottom:12px;color:#64748b"></div>
            <div class="media-grid" id="mediaGrid"></div>
        </div>
        <div class="media-modal-footer">
            <button type="button" class="btn-ghost" onclick="closeMediaModal()">Done</button>
        </div>
    </div>
</div>

<script>
let currentProductId = null;

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function openMediaModal(productId) {
    currentProductId = productId;
    document.getElementById('mediaModal').classList.add('is-open');
    document.getElementById('mediaModal').setAttribute('aria-hidden', 'false');
    loadMediaGrid();
}

function closeMediaModal() {
    document.getElementById('mediaModal').classList.remove('is-open');
    document.getElementById('mediaModal').setAttribute('aria-hidden', 'true');
}

function setMediaMessage(message, isError = false) {
    const el = document.getElementById('mediaMessage');
    el.textContent = message || '';
    el.style.color = isError ? '#b91c1c' : '#64748b';
}

function loadMediaGrid() {
    if (!currentProductId) return;

    const mediaGrid = document.getElementById('mediaGrid');
    mediaGrid.innerHTML = '<p style="font-size:13px;color:#64748b">Loading media...</p>';

    fetch(`/admin/products/${currentProductId}/media`, { headers: { 'Accept': 'application/json' } })
        .then(response => response.ok ? response.json() : Promise.reject())
        .then(data => {
            mediaGrid.innerHTML = '';
            const items = [
                ...(data.images || []).map(item => ({ ...item, type: 'image' })),
                ...(data.documents || []).map(item => ({ ...item, type: 'document' })),
            ];

            if (!items.length) {
                mediaGrid.innerHTML = '<p style="font-size:13px;color:#64748b">No media uploaded yet.</p>';
                return;
            }

            items.forEach(item => mediaGrid.appendChild(createMediaItem(item)));
        })
        .catch(() => {
            mediaGrid.innerHTML = '';
            setMediaMessage('Media load nahi ho paya. Page refresh karke try karein.', true);
        });
}

function createMediaItem(media) {
    const card = document.createElement('div');
    card.className = 'media-card';

    const isPrimary = media.custom_properties && media.custom_properties.is_primary;
    const preview = media.type === 'image'
        ? `<img src="${media.url}" alt="${escapeHtml(media.name)}">`
        : `<div class="media-card-file"><div><i class="fas fa-file-pdf" style="font-size:30px;color:#dc2626"></i><div class="media-name" style="margin-top:8px">${escapeHtml(media.name)}</div></div></div>`;

    card.innerHTML = `
        ${preview}
        <div class="media-card-body">
            <div class="media-name" title="${escapeHtml(media.name)}">${escapeHtml(media.name)}</div>
            <div class="media-actions">
                <a class="media-btn" href="${media.url}" target="_blank" rel="noopener">${media.type === 'image' ? 'Open' : 'Download'}</a>
                ${media.type === 'image' ? `<button type="button" class="media-btn primary" onclick="setAsPrimary(${media.id})" ${isPrimary ? 'disabled' : ''}>${isPrimary ? 'Primary' : 'Set Primary'}</button>` : ''}
                <button type="button" class="media-btn danger" onclick="deleteMediaFile(${media.id})">Delete</button>
            </div>
        </div>
    `;

    return card;
}

function setAsPrimary(mediaId) {
    fetch(`/admin/products/${currentProductId}/media/${mediaId}/set-primary`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
    })
        .then(response => response.ok ? response.json() : Promise.reject())
        .then(() => loadMediaGrid())
        .catch(() => setMediaMessage('Primary image set nahi ho payi.', true));
}

function deleteMediaFile(mediaId) {
    if (!confirm('Delete this file?')) return;

    fetch(`/admin/products/${currentProductId}/media/${mediaId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
    })
        .then(response => response.ok ? response.json() : Promise.reject())
        .then(() => loadMediaGrid())
        .catch(() => setMediaMessage('File delete nahi ho payi.', true));
}

document.getElementById('uploadMediaBtn').addEventListener('click', function () {
    const input = document.getElementById('mediaFiles');

    if (!input.files.length) {
        setMediaMessage('Please select at least one file.', true);
        return;
    }

    const formData = new FormData();
    Array.from(input.files).forEach(file => formData.append('files[]', file));
    setMediaMessage('Uploading...');

    fetch(`/admin/products/${currentProductId}/media/upload`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
        body: formData,
    })
        .then(response => response.ok ? response.json() : response.json().then(data => Promise.reject(data)))
        .then(() => {
            input.value = '';
            setMediaMessage('Upload complete.');
            loadMediaGrid();
        })
        .catch(error => {
            const message = error && error.message ? error.message : 'Upload failed. File type/size check karein.';
            setMediaMessage(message, true);
        });
});

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));
}
</script>
