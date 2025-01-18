// فتح نافذة إضافة مستخدم
function openCart() {
    document.getElementById('Addproductmodal').style.display = 'flex';
}

// إغلاق نافذة إضافة مستخدم
function closeCart() {
    document.getElementById('Addproductmodal').style.display = 'none';
}

// فتح نافذة تعديل المستخدم
function openEditModal(userId, userName, userRoomNumber, userImage) {
    document.getElementById('edit_user_id').value = userId;
    document.getElementById('edit_user_name').value = userName;
    document.getElementById('edit_user_room_number').value = userRoomNumber;
    document.getElementById('edit_user_image_preview').src = userImage;

    document.getElementById('EdituserModal').style.display = 'flex';
}

// إغلاق نافذة تعديل المستخدم
function closeEditModal() {
    document.getElementById('EdituserModal').style.display = 'none';
}

// إضافة حدث النقر على أيقونة التعديل
document.querySelectorAll('.fa-edit').forEach(icon => {
    icon.addEventListener('click', function (e) {
        e.preventDefault();
        const userCard = this.closest('.product-card');
        const userId = userCard.dataset.productId;
        const userName = userCard.querySelector('h2').textContent;
        const userRoomNumber = userCard.querySelector('.Roomnumber').textContent.replace('Room Number: ', '');
        const userImage = userCard.querySelector('img').src;

        openEditModal(userId, userName, userRoomNumber, userImage);
        console.log("Edit icon clicked for user with ID:", userId);
    });
});

// إغلاق النافذة عند النقر خارجها
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});
