// Coupons Management
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

    // Toggle discount value field based on type
    function toggleDiscountValueField(typeSelect, valueGroup) {
        const type = typeSelect.value;
        if (type === 'free_shipping') {
            valueGroup.style.display = 'none';
            valueGroup.querySelector('input').removeAttribute('required');
        } else {
            valueGroup.style.display = 'block';
            valueGroup.querySelector('input').setAttribute('required', 'required');
        }
    }

    // Create Coupon Form
    const createForm = document.getElementById('createCouponForm');
    const createTypeSelect = createForm.querySelector('[name="type"]');
    const createValueGroup = document.getElementById('discountValueGroup');

    createTypeSelect.addEventListener('change', () => {
        toggleDiscountValueField(createTypeSelect, createValueGroup);
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
            const response = await fetch(window.couponRoutes.store, {
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
                bootstrap.Modal.getInstance(document.getElementById('createCouponModal')).hide();
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

    // Edit Coupon - Load data
    document.querySelectorAll('.edit-coupon-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const couponId = btn.dataset.id;

            try {
                const url = window.couponRoutes.edit.replace(':id', couponId);
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const coupon = await response.json();

                // Fill form fields
                document.getElementById('edit_coupon_id').value = coupon.id;
                document.getElementById('edit_name').value = coupon.name || '';
                document.getElementById('edit_coupon').value = coupon.coupon || '';
                document.getElementById('edit_type').value = coupon.type || 'percentage';
                document.getElementById('edit_discount_value').value = coupon.discount_value || '';
                document.getElementById('edit_description').value = coupon.description || '';
                document.getElementById('edit_min_order_amount').value = coupon.min_order_amount || '';
                document.getElementById('edit_max_order_amount').value = coupon.max_order_amount || '';
                document.getElementById('edit_min_qty').value = coupon.min_qty || '';
                document.getElementById('edit_max_discount_amount').value = coupon.max_discount_amount || '';
                document.getElementById('edit_max_uses').value = coupon.max_uses || '';
                document.getElementById('edit_max_uses_per_user').value = coupon.max_uses_per_user || '';

                // Handle datetime fields
                if (coupon.starts_at) {
                    const startsAt = new Date(coupon.starts_at);
                    document.getElementById('edit_starts_at').value = startsAt.toISOString().slice(0, 16);
                }
                if (coupon.ends_at) {
                    const endsAt = new Date(coupon.ends_at);
                    document.getElementById('edit_ends_at').value = endsAt.toISOString().slice(0, 16);
                }

                // Handle checkboxes - Convert to boolean explicitly
                document.getElementById('edit_is_active').checked = Boolean(coupon.is_active);
                document.getElementById('edit_individual_use').checked = Boolean(coupon.individual_use);
                document.getElementById('edit_exclude_sale_items').checked = Boolean(coupon.exclude_sale_items);
                document.getElementById('edit_free_shipping').checked = Boolean(coupon.free_shipping);

                console.log('Coupon loaded:', {
                    is_active: coupon.is_active,
                    checkbox_checked: document.getElementById('edit_is_active').checked
                });

                // Show usage info
                const maxUses = coupon.max_uses || '∞';
                document.getElementById('edit_usage_info').textContent = `${coupon.uses || 0} / ${maxUses} times used`;

                // Toggle discount value field
                const editTypeSelect = document.getElementById('edit_type');
                const editValueGroup = document.getElementById('editDiscountValueGroup');
                toggleDiscountValueField(editTypeSelect, editValueGroup);

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to load coupon data.');
            }
        });
    });

    // Edit Coupon - Type change handler
    const editForm = document.getElementById('editCouponForm');
    const editTypeSelect = editForm.querySelector('[name="type"]');
    const editValueGroup = document.getElementById('editDiscountValueGroup');

    editTypeSelect.addEventListener('change', () => {
        toggleDiscountValueField(editTypeSelect, editValueGroup);
    });

    // Edit Coupon Form Submit
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = editForm.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');
        const couponId = document.getElementById('edit_coupon_id').value;

        clearFormErrors(editForm);
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        try {
            const formData = new FormData(editForm);

            // Log form data for debugging
            console.log('Submitting update with data:', {
                is_active_checkbox: document.getElementById('edit_is_active').checked,
                has_is_active_in_formdata: formData.has('is_active'),
                is_active_value: formData.get('is_active')
            });

            const url = window.couponRoutes.update.replace(':id', couponId);
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
                bootstrap.Modal.getInstance(document.getElementById('editCouponModal')).hide();
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

    // Delete Coupon - Load data
    document.querySelectorAll('.delete-coupon-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const couponId = btn.dataset.id;
            const couponCode = btn.dataset.code;

            document.getElementById('delete_coupon_id').value = couponId;
            document.getElementById('delete_coupon_code').textContent = couponCode;
        });
    });

    // Delete Coupon - Confirm
    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        const btn = document.getElementById('confirmDeleteBtn');
        const spinner = btn.querySelector('.spinner-border');
        const couponId = document.getElementById('delete_coupon_id').value;

        btn.disabled = true;
        spinner.classList.remove('d-none');

        try {
            const url = window.couponRoutes.destroy.replace(':id', couponId);
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
                bootstrap.Modal.getInstance(document.getElementById('deleteCouponModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert('Failed to delete coupon.');
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
