
document.addEventListener("DOMContentLoaded", function () {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];

    function updateCartUI() {
        const cartContainer = document.getElementById("cart-items");
        const cartTotalPrice = document.getElementById("cart-total-price");
        cartContainer.innerHTML = "";

        cart.forEach(item => {
            const cartItem = document.createElement("div");
            cartItem.className = "cart-item";
            cartItem.dataset.cartItemId = item.id; // Add the product ID as a data attribute
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h2>${item.name}</h2>
                <h3 class="product-price">$${item.price.toFixed(2)}</h3>
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
    }

    function saveCartToLocalStorage() {
        localStorage.setItem("cart", JSON.stringify(cart));
    }

    // Event delegation for cart actions
    document.getElementById("cart-items").addEventListener("click", function (event) {
        const target = event.target;

        if (target.closest(".add-one") || target.closest(".remove-one")) {
            const cartItem = target.closest(".cart-item");
            const itemId = cartItem.dataset.cartItemId;
            const item = cart.find(item => item.id === itemId);

            if (item) {
                if (target.closest(".add-one")) {
                    item.quantity++;
                } else if (target.closest(".remove-one")) {
                    if (item.quantity > 1) {
                        item.quantity--;
                    } else {
                        // Remove the item from the cart if quantity becomes 0
                        const itemIndex = cart.indexOf(item);
                        cart.splice(itemIndex, 1);
                    }
                }

                saveCartToLocalStorage();
                updateCartUI();
            }
        }
    });

    // Product handling 
    document.querySelectorAll(".product-card").forEach(productCard => {
        const addOneBtn = productCard.querySelector(".add-one");
        const removeOneBtn = productCard.querySelector(".remove-one");
        const addToCartBtn = productCard.querySelector(".add-to-cart");
        const quantityDisplay = productCard.querySelector(".product-quantity");

        let quantity = 1;

        // زيادة الكمية
        addOneBtn.addEventListener("click", function () {
            quantity++;
            quantityDisplay.textContent = quantity;
        });

        // تقليل الكمية
        removeOneBtn.addEventListener("click", function () {
            if (quantity > 1) {
                quantity--;
                quantityDisplay.textContent = quantity;
            }
        });

        // إضافة المنتج للكارت
        addToCartBtn.addEventListener("click", function () {
            const productId = productCard.dataset.productId;
            const productName = productCard.querySelector("h2").textContent;
            const productPrice = parseFloat(productCard.querySelector(".product-price").textContent.replace("$", "").trim());
            const productImage = productCard.querySelector("img").src;

            // تحقق إذا كان المنتج موجودًا في الكارت
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    image: productImage,
                    quantity: quantity,
                });
            }

            // إعادة ضبط الكمية وحفظ البيانات
            quantity = 1;
            quantityDisplay.textContent = quantity;
            saveCartToLocalStorage();
            updateCartUI();
        });


    });

    updateCartUI();
});
