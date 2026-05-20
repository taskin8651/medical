<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    if (!categorySelect || !subcategorySelect) {
        return;
    }

    function loadSubcategories(categoryId, selectedId = '') {
        subcategorySelect.innerHTML = '<option value="">Select subcategory</option>';

        if (!categoryId) {
            return;
        }

        fetch(`{{ url('admin/products') }}/${categoryId}/subcategories`, {
            headers: { 'Accept': 'application/json' }
        })
            .then(response => response.ok ? response.json() : Promise.reject())
            .then(items => {
                subcategorySelect.innerHTML = '<option value="">Select subcategory</option>';

                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    option.selected = String(item.id) === String(selectedId);
                    subcategorySelect.appendChild(option);
                });
            })
            .catch(() => {
                subcategorySelect.innerHTML = '<option value="">Select subcategory</option>';
            });
    }

    categorySelect.addEventListener('change', function () {
        loadSubcategories(this.value);
    });

    if (categorySelect.value && subcategorySelect.options.length <= 1) {
        loadSubcategories(categorySelect.value, subcategorySelect.dataset.selected);
    }
});
</script>
