document.addEventListener("DOMContentLoaded", function () {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    function updateCartUI() {
        const cartContainer = document.getElementById("cart-items");
        const cartTotalPrice = document.getElementById("cart-total-price");
        cartContainer.innerHTML = "";

        cart.forEach(item => {
            const cartItem = document.createElement("div");
            cartItem.className = "cart-item";
            cartItem.dataset.cartItemId = item.id;
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h2>${item.name}</h2>
                <h3 class="product-price">$${(item.price * item.quantity).toFixed(2)}</h3>
                <div class="cart-item-actions">
                    <div class="add-one" data-action="add"><i class="fa-solid fa-plus"></i></div>
                    <div class="product-quantity">${item.quantity}</div>
                    <div class="remove-one" data-action="remove"><i class="fa-solid fa-minus"></i></div>
                </div>
            `;

            cartContainer.appendChild(cartItem);
        });

        const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        cartTotalPrice.textContent = `$${total.toFixed(2)}`;
        
        localStorage.setItem("cart", JSON.stringify(cart));
    }

    // معالجة أحداث السلة
    document.getElementById("cart-items").addEventListener("click", function (event) {
        const target = event.target;
        const cartItem = target.closest(".cart-item");
        if (!cartItem) return;

        const itemId = cartItem.dataset.cartItemId;
        const item = cart.find(item => item.id === itemId);

        if (item) {
            if (target.closest(".add-one")) {
                item.quantity++;
            } else if (target.closest(".remove-one")) {
                if (item.quantity > 1) {
                    item.quantity--;
                } else {
                    const itemIndex = cart.indexOf(item);
                    cart.splice(itemIndex, 1);
                }
            }
            updateCartUI();
        }
    });

    // معالجة المنتجات
    document.querySelectorAll(".product-card").forEach(productCard => {
        const addOneBtn = productCard.querySelector(".add-one");
        const removeOneBtn = productCard.querySelector(".remove-one");
        const addToCartBtn = productCard.querySelector(".add-to-cart");
        const quantityDisplay = productCard.querySelector(".product-quantity");

        let quantity = 1;

        addOneBtn.addEventListener("click", function () {
            quantity++;
            quantityDisplay.textContent = quantity;
        });

        removeOneBtn.addEventListener("click", function () {
            if (quantity > 1) {
                quantity--;
                quantityDisplay.textContent = quantity;
            }
        });

        addToCartBtn.addEventListener("click", function () {
            const productId = productCard.dataset.productId;
            const productName = productCard.querySelector("h2").textContent;
            const productPrice = parseFloat(productCard.querySelector(".product-price").textContent.replace("$", ""));
            const productImage = productCard.querySelector("img").src;

            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    quantity: quantity
                });
            }

            quantity = 1;
            quantityDisplay.textContent = quantity;
            updateCartUI();
        });
    });

    // معالجة زر Place Order
    document.querySelector('.checkout').addEventListener('click', function(e) {
        e.preventDefault();

        if (cart.length === 0) {
            alert("Cart is empty!");
            return;
        }

        const formData = new FormData();
        
        cart.forEach((item, index) => {
            formData.append(`ProductID[${index}]`, item.id);
            formData.append(`Quantity[${index}]`, item.quantity);
        });

        fetch('place_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    alert('Order placed successfully!');
                    cart = [];
                    localStorage.removeItem('cart');
                    updateCartUI();
                } else {
                    throw new Error(data.message || 'Failed to place order');
                }
            } catch (e) {
                console.error('Server response:', text);
                throw new Error('Failed to process order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message);
        });
    });

    updateCartUI();
});
