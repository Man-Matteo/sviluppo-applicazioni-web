function addToCart(productId, storage) {
    var quantity = document.getElementById("quantity_" + productId).value;
    if (quantity > storage || quantity < 1) {
        alert("Invalid quantity");
        return false;
    }
    window.location.href = "https://saw21.dibris.unige.it/~S4628329/functions/add_product.php?productId=" + productId + "&quantity=" + quantity;
}

