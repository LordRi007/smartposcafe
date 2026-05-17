
  let currentFilter = 'all';
function clearCart() {
  if (confirm("Clear the cart?")) {
    for (const key in cart) delete cart[key];
    updateCartDisplay();
    document.getElementById('received-cash').value = '';
    document.getElementById('change').innerText = '0.00';
  }
}
function filterProducts(catId) {
  currentFilter = catId;
  renderProducts(catId);

  // Update active button styles
  document.querySelectorAll('.filter-card').forEach(card => card.classList.remove('active'));
  const activeCard = document.getElementById(`filter-${catId}`);
  if (activeCard) activeCard.classList.add('active');
}






  // Dark mode toggle
 if (localStorage.getItem("mode") === "dark") {
    document.body.classList.add("dark-mode");
  }

  function toggleMode() {
    document.body.classList.toggle("dark-mode");
    const mode = document.body.classList.contains("dark-mode") ? "dark" : "light";
    localStorage.setItem("mode", mode);
  }

  const products = @json($products); // ✅ This is the correct version
console.log(products);

  const cart = {};

function renderProducts(filter = 'Ice') {
  const container = document.getElementById('product-list');
  container.innerHTML = ''; // Clear existing buttons

  // Apply filte
  const filteredProducts = filter === 'Ice' ? products : products.filter(p => p.cat_id === filter);

  filteredProducts.forEach(product => {
    const div = document.createElement('div');
    div.style.width = '120px';
    div.style.textAlign = 'center';

    const img = document.createElement('img');
    img.src = product.img;
    img.alt = product.name;
    img.style.width = '100px';
    img.style.height = '100px';
    img.style.objectFit = 'cover';
    img.style.borderRadius = '8px';

    const btn = document.createElement('button');
    btn.innerText = `${product.name}\n$${product.price}`;
    btn.style.marginTop = '5px';
    btn.onclick = () => addToCart(product);

    div.appendChild(img);
    div.appendChild(btn);
    container.appendChild(div);
  });
}



  function addToCart(product) {
    if (!cart[product.name]) {
      cart[product.name] = { ...product, qty: 1 };
    } else {
      cart[product.name].qty += 1;
    }
    updateCartDisplay();
  }

function updateCartDisplay() {
  const tbody = document.getElementById('cart-body');
  tbody.innerHTML = '';
  let total = 0;

  Object.values(cart).forEach(item => {
    const row = document.createElement('tr');

    row.innerHTML = `
      <td style="display: flex; align-items: center; gap: 8px;">
        <img src="${item.img}" alt="${item.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
        ${item.name}
      </td>
      <td>
        <input type="number" min="1" value="${item.qty}" style="width: 50px; padding: 4px;" 
          onchange="updateQty('${item.name}', this.value)">
      </td>
      <td>$${(item.price * item.qty).toFixed(2)}</td>
      <td>
        <button onclick="removeFromCart('${item.name}')" 
          style="background:none; border:none; color:red; font-size:18px; cursor:pointer;">&times;</button>
      </td>
    `;

    tbody.appendChild(row);
    total += item.price * item.qty;
  });

  document.getElementById('total-price').innerText = total.toFixed(2);
  calculateChange();
}

function updateQty(productName, newQty) {
  newQty = parseInt(newQty);
  if (newQty > 0) {
    cart[productName].qty = newQty;
    updateCartDisplay();
  }
}

function removeFromCart(productName) {
  if (cart[productName]) {
    delete cart[productName];
    updateCartDisplay();
  }
}



  function calculateChange() {
    const receivedCash = parseFloat(document.getElementById('received-cash').value);
    const total = parseFloat(document.getElementById('total-price').innerText);
    const change = receivedCash - total;
    document.getElementById('change').innerText = change >= 0 ? change.toFixed(2) : "0.00";
  }

function payNow() {
  if (Object.keys(cart).length === 0) return alert("Cart is empty!");

  const receivedCash = parseFloat(document.getElementById('received-cash').value);
  const total = parseFloat(document.getElementById('total-price').innerText);
  const change = receivedCash - total;

  if (receivedCash < total) {
    return alert("Insufficient cash received.");
  }

  fetch('/pos/checkout', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
    },
    body: JSON.stringify({
      cart: Object.values(cart),
      received: receivedCash,
      total,
      change,
    }),
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("Payment successful and receipt saved!");
      printReceipt(); // ✅ Print receipt automatically
      for (const key in cart) delete cart[key];
      updateCartDisplay();
      document.getElementById('received-cash').value = '';
      document.getElementById('change').innerText = '0.00';
    } else {
      alert("Checkout failed.");
    }
  })
  .catch(err => {
    console.error(err);
    alert("An error occurred during checkout.");
  });
}



  function printReceipt() {
    let receiptWindow = window.open('', 'Receipt', 'width=400,height=600');
    let receiptContent = `
      <html><head><title>Receipt</title><style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border-bottom: 1px solid #ccc; text-align: left; }
        .total { font-weight: bold; margin-top: 20px; }
      </style></head><body>
      <h2>SmartPOS Receipt</h2>
      <table><thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead><tbody>`;

    let total = 0;
    Object.values(cart).forEach(item => {
      const itemTotal = item.qty * item.price;
      total += itemTotal;
      receiptContent += `<tr><td>${item.name}</td><td>${item.qty}</td><td>$${itemTotal.toFixed(2)}</td></tr>`;
    });

    const received = parseFloat(document.getElementById('received-cash').value || 0);
    const change = received - total;

    receiptContent += `</tbody></table>
      <p class="total">Total: $${total.toFixed(2)}</p>
      <p class="total">Received: $${received.toFixed(2)}</p>
      <p class="total">Change: $${(change > 0 ? change : 0).toFixed(2)}</p>
      <p style="text-align:center; margin-top:30px;">Thank you for your purchase!</p>
      </body></html>`;

    receiptWindow.document.write(receiptContent);
    receiptWindow.document.close();
    receiptWindow.focus();
    receiptWindow.print();
  }

  renderProducts();

