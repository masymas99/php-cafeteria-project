function openCart() {
    document.getElementById('Addproductmodal').style.display = 'flex';
}

function closeCart() {
    document.getElementById('Addproductmodal').style.display = 'none';
}
function openEditModal(productId, productName, productPrice, productDescription) {
    // ملء البيانات في الحقول
    document.getElementById('edit_product_id').value = productId;
    document.getElementById('edit_product_name').value = productName;
    document.getElementById('edit_product_price').value = productPrice;
    document.getElementById('edit_product_description').value = productDescription;

    // عرض المودال
    document.getElementById('Editproductmodal').style.display = 'flex';
}

function closeEditModal() {
    // إخفاء المودال
    document.getElementById('Editproductmodal').style.display = 'none';
}
function openEditModal(productId, productName, productPrice, productDescription) {
    // ملء البيانات في الحقول
    document.getElementById('edit_product_id').value = productId;
    document.getElementById('edit_product_name').value = productName;
    document.getElementById('edit_product_price').value = productPrice;
    document.getElementById('edit_product_description').value = productDescription;

    // عرض المودال
    document.getElementById('Editproductmodal').style.display = 'flex';
}

function closeEditModal() {
    // إخفاء المودال
    document.getElementById('Editproductmodal').style.display = 'none';
}

// إضافة حدث النقر على أيقونة التعديل
document.querySelectorAll('.fa-edit').forEach(icon => {
    icon.addEventListener('click', function () {
        const productCard = this.closest('.product-card');
        const productId = productCard.dataset.productId;
        const productName = productCard.querySelector('h2').textContent;
        const productPrice = productCard.querySelector('.product-price').textContent.replace('$', '').trim();
        const productDescription = productCard.querySelector('.product-description').textContent;

        // فتح نافذة التعديل مع تعبئة البيانات
        openEditModal(productId, productName, productPrice, productDescription);
    });
});

// إغلاق النافذة عند النقر خارجها
// document.getElementById('Editproductmodal').addEventListener('click', function (e) {
//     if (e.target === this) {
//         closeEditModal();
//     }
// });

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});