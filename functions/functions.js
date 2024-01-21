function addToCart(productId, storage) {
    var quantity = document.getElementById("quantity_" + productId).value;
    if (quantity > storage || quantity < 1) {
        alert("Invalid quantity");
        return false;
    }
    window.location.href = "../functions/add_product.php?productId=" + productId + "&quantity=" + quantity;
}

