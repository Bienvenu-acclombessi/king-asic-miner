// Shipping Methods Management
(function() {
    'use strict';

    // Helper function to show success message
    function showSuccess(message) {
        const alertDiv = document.getElementById('successAlert');
        const messageSpan = document.getElementById('successMessage');
        messageSpan.textContent = message;
        alertDiv.style.display = 'block';
        alertDiv.classList.add('show');

        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.style.display = 'none', 150);
        }, 3000);
    }

    // Helper function to clear form errors
    function clearFormErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    // Helper function to show form errors
    function showFormErrors(form, errors) {
        Object.keys(errors).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = errors[key][0];
                }
            }
        });
    }

    // Toggle price field based on price type
    function togglePriceField(typeSelect, priceGroup) {
        const type = typeSelect.value;
        const priceInput = priceGroup.querySelector('input[name="price"]');

        if (type === 'free') {
            priceGroup.style.display = 'none';
            priceInput.removeAttribute('required');
            priceInput.value = '0';
        } else {
            priceGroup.style.display = 'block';
            priceInput.setAttribute('required', 'required');
        }
    }

    // Create Shipping Method Form
    const createForm = document.getElementById('createShippingMethodForm');
    const createTypeSelect = createForm.querySelector('[name="price_type"]');
    const createPriceGroup = document.getElementById('priceGroup');

    createTypeSelect.addEventListener('change', () => {
        togglePriceField(createTypeSelect, createPriceGroup);
    });

    createForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = createForm.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        clearFormErrors(createForm);
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        try {
            const formData = new FormData(createForm);
            const response = await fetch(window.shippingMethodRoutes.store, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showSuccess(data.message);
                bootstrap.Modal.getInstance(document.getElementById('createShippingMethodModal')).hide();
                createForm.reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                if (data.errors) {
                    showFormErrors(createForm, data.errors);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    // Edit Shipping Method - Load data
    document.querySelectorAll('.edit-shipping-method-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const methodId = btn.dataset.id;

            try {
                const url = window.shippingMethodRoutes.edit.replace(':id', methodId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const method = await response.json();

                // Fill form fields
                document.getElementById('edit_shipping_method_id').value = method.id;
                document.getElementById('edit_name').value = method.name || '';
                document.getElementById('edit_description').value = method.description || '';
                document.getElementById('edit_price_type').value = method.price_type || 'fixed';
                document.getElementById('edit_price').value = method.price || '';
                document.getElementById('edit_min_order_amount').value = method.min_order_amount || '';
                document.getElementById('edit_max_order_amount').value = method.max_order_amount || '';
                document.getElementById('edit_max_weight').value = method.max_weight || '';
                document.getElementById('edit_estimated_days_min').value = method.estimated_days_min || '';
                document.getElementById('edit_estimated_days_max').value = method.estimated_days_max || '';
                document.getElementById('edit_display_order').value = method.display_order || '0';

                // Handle checkbox
                document.getElementById('edit_is_active').checked = Boolean(method.is_active);

                // Toggle price field
                const editTypeSelect = document.getElementById('edit_price_type');
                const editPriceGroup = document.getElementById('editPriceGroup');
                togglePriceField(editTypeSelect, editPriceGroup);

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load shipping method data.');
            }
        });
    });

    // Edit Shipping Method - Price type change handler
    const editForm = document.getElementById('editShippingMethodForm');
    const editTypeSelect = editForm.querySelector('[name="price_type"]');
    const editPriceGroup = document.getElementById('editPriceGroup');

    editTypeSelect.addEventListener('change', () => {
        togglePriceField(editTypeSelect, editPriceGroup);
    });

    // Edit Shipping Method Form Submit
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = editForm.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');
        const methodId = document.getElementById('edit_shipping_method_id').value;

        clearFormErrors(editForm);
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        try {
            const formData = new FormData(editForm);

            // Add method spoofing for Laravel
            formData.append('_method', 'PUT');

            const url = window.shippingMethodRoutes.update.replace(':id', methodId);
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showSuccess(data.message);
                bootstrap.Modal.getInstance(document.getElementById('editShippingMethodModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                if (data.errors) {
                    showFormErrors(editForm, data.errors);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        } finally {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    // Delete Shipping Method - Load data
    document.querySelectorAll('.delete-shipping-method-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const methodId = btn.dataset.id;
            const methodName = btn.dataset.name;

            document.getElementById('delete_shipping_method_id').value = methodId;
            document.getElementById('delete_shipping_method_name').textContent = methodName;
        });
    });

    // Delete Shipping Method - Confirm
    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        const btn = document.getElementById('confirmDeleteBtn');
        const spinner = btn.querySelector('.spinner-border');
        const methodId = document.getElementById('delete_shipping_method_id').value;

        btn.disabled = true;
        spinner.classList.remove('d-none');

        try {
            const url = window.shippingMethodRoutes.destroy.replace(':id', methodId);
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (response.ok) {
                showSuccess(data.message);
                bootstrap.Modal.getInstance(document.getElementById('deleteShippingMethodModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert('Failed to delete shipping method.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

})();
