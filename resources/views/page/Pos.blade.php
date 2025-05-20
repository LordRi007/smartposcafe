<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>SmartPOS - Cashier Interface</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <style>
    #product-list > div {
  background: #f0f0f0;
  padding: 10px;
  border-radius: 8px;
}

    :root {
      --bg: #f9fafc;
      --text: #2e2e2e;
      --primary: #007bff;
      --card: #fff;
    }

    body.dark-mode {
      --bg: #1f1f2e;
      --text:  #007bff;
      --card: #2e2e42;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: var(--bg);
      color: var(--text);
      transition: 0.3s ease-in-out;
    }

    header {
      background: var(--card);
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    header h1 {
      color: var(--primary);
    }

    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: var(--text);
      font-weight: 500;
    }

    .hero {
      padding: 80px 20px;
      text-align: center;
    }

    .hero h2 {
      font-size: 42px;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 18px;
      max-width: 600px;
      margin: 0 auto 30px;
    }

    .hero button {
      padding: 14px 28px;
      font-size: 16px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .features {
      padding: 60px 40px;
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
    }

    .feature-card {
      background: var(--card);
      border-radius: 12px;
      width: 280px;
      padding: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      text-align: center;
    }

    .feature-card i {
      font-size: 36px;
      color: var(--primary);
      margin-bottom: 15px;
    }

    .feature-card h3 {
      margin-bottom: 10px;
    }

    .screenshot {
      text-align: center;
      padding: 40px;
    }

    .screenshot img {
      max-width: 80%;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .pos-interface {
      padding: 60px 40px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    #pos {
      border: 2px solid #ccc;
      border-radius: 12px;
      padding: 20px;
      background-color: #fff;
    }

    #product-list button {
      padding: 10px 15px;
      border-radius: 8px;
      background: var(--primary);
      color: white;
      border: none;
      cursor: pointer;
    }

    #product-list button:hover {
      background: #0056b3;
    }

    .cart-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .cart-table th, .cart-table td {
      padding: 8px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }

    footer {
      padding: 30px;
      text-align: center;
      background: var(--card);
      color: #888;
    }

    .dark-toggle {
      cursor: pointer;
      font-size: 14px;
      margin-left: 20px;
      color: var(--primary);
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .features {
        flex-direction: column;
        align-items: center;
      }

      .hero h2 {
        font-size: 30px;
      }
    }
  </style>
</head>
<body>

<header>

  <h1>SmartPOS</h1>
  <nav>
    <a href="#">Home</a>
    <a href="#features">Features</a>
    <a href="#pos">POS</a>
    <span class="dark-toggle" onclick="toggleMode()">Toggle Dark Mode</span>
  </nav>
</header>

<section id="pos" class="pos-interface">
  <h2 style="text-align:center; margin-bottom: 30px;">Cashier Terminal</h2>
  <div style="display: flex; gap: 40px; flex-wrap: wrap; justify-content: center;">
    <!-- Product Buttons -->
    <div style="flex: 1; min-width: 300px;">
      <h3>Products</h3>
      <div id="product-list" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;"></div>
    </div>

    <!-- Cart -->
    <div style="flex: 1; min-width: 300px;">
      <h3>Cart</h3>
      <table class="cart-table">
        <thead>
          <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
        </thead>
        <tbody id="cart-body"></tbody>
      </table>
      <h4 style="margin-top: 20px;">Total: $<span id="total-price">0.00</span></h4>
      <div>
        <label for="received-cash">Received Cash: $</label>
     
        <input type="number" id="received-cash" placeholder="Enter cash" style="padding: 5px; width: 100%; margin-top: 10px;" oninput="calculateChange()"/>
      </div>
      <h4 style="margin-top: 20px;"><span id="change-display">0.00</span></h4>

      
      <button onclick="checkout()" style="margin-top: 10px; padding: 10px 20px; background: var(--primary); color: #fff; border: none; border-radius: 6px;">Pay Now</button>
      <button onclick="printReceipt()" style="margin-top: 10px; padding: 10px 20px; background: #28a745; color: #fff; border: none; border-radius: 6px;">Print Receipt</button>
    </div>
  </div>
</section>

<section class="hero">
  <h2>Retail Cashier POS System</h2>
  <p>Fast, reliable point-of-sale software for your store. Sell products, print receipts, and track your sales with ease.</p>
  <button onclick="document.getElementById('pos').scrollIntoView({behavior: 'smooth'})">Try the POS</button>
</section>

<section id="features" class="features">
  <div class="feature-card">
    <i class="fas fa-barcode"></i>
    <h3>Barcode Scanning</h3>
    <p>Scan items instantly for faster checkout with barcode support.</p>
  </div>
  <div class="feature-card">
    <i class="fas fa-receipt"></i>
    <h3>Receipt Printing</h3>
    <p>Print professional receipts with customer and product details.</p>
  </div>
  <div class="feature-card">
    <i class="fas fa-cubes"></i>
    <h3>Inventory Sync</h3>
    <p>Auto-update product stock as you sell, return, or restock items.</p>
  </div>
  <div class="feature-card">
    <i class="fas fa-chart-pie"></i>
    <h3>Sales Analytics</h3>
    <p>Track top-selling items, revenue trends, and cashier performance.</p>
  </div>
</section>

<section class="screenshot">
  <h2>POS Interface Preview</h2>
  <img src="https://via.placeholder.com/800x400?text=POS+System+Interface" alt="POS Screenshot" />
</section>
<div id="receipt-container" style="display: none; font-family: monospace; width: 300px;">
  <h3 style="text-align: center;">Smart POS</h3>
  <hr>
  <div id="receipt-body"></div>
  <hr>
  <p><strong>Total:</strong> $<span id="receipt-total"></span></p>
  <p><strong>Cash:</strong> $<span id="receipt-cash"></span></p>
  <p><strong>Change:</strong> $<span id="receipt-change"></span></p>
  <p style="text-align: center;">Thank you!</p>
</div>

<footer>
  &copy; 2025 SmartPOS. All rights reserved.
</footer>
<script>
  function printReceipt(items, total, received, change) {
    const body = document.getElementById('receipt-body');
    body.innerHTML = '';

    items.forEach(item => {
      const row = document.createElement('p');
      row.textContent = `${item.name} x${item.quantity} - $${(item.price * item.quantity).toFixed(2)}`;
      body.appendChild(row);
    });

    document.getElementById('receipt-total').textContent = total.toFixed(2);
    document.getElementById('receipt-cash').textContent = received.toFixed(2);
    document.getElementById('receipt-change').textContent = change.toFixed(2);

    const receiptHTML = document.getElementById('receipt-container').innerHTML;
    const printWindow = window.open('', '', 'width=400,height=600');
    printWindow.document.write('<html><head><title>Receipt</title></head><body>');
    printWindow.document.write(receiptHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }
</script>

<script>
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

  const cart = {};

 function renderProducts() {
  const container = document.getElementById('product-list');
  container.innerHTML = ''; // Clear existing buttons
  products.forEach(product => {
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
      row.innerHTML = `<td>${item.name}</td><td>${item.qty}</td><td>$${(item.price * item.qty).toFixed(2)}</td>`;
      tbody.appendChild(row);
      total += item.price * item.qty;
    });
    document.getElementById('total-price').innerText = total.toFixed(2);
    calculateChange();
  }

  // function calculateChange() {
  //   const receivedCash = parseFloat(document.getElementById('received-cash').value);
  //   const total = parseFloat(document.getElementById('total-price').innerText);
  //   const change = receivedCash - total;
  //   document.getElementById('change').innerText = change >= 0 ? change.toFixed(2) : "0.00";
  // }
  function calculateChange() {
  const received = parseFloat(document.getElementById("received-cash").value);
  const total = Object.values(cart).reduce((sum, item) => sum + item.price * item.qty, 0);
  const change = received - total;

  document.getElementById("change-display").textContent = 
    isNaN(change) ? '' : `Change: $${change.toFixed(2)}`;
}


  function payNow() {
    if (Object.keys(cart).length === 0) return alert("Cart is empty!");
    alert("Payment processed successfully!");
    for (const key in cart) delete cart[key];
    updateCartDisplay();
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
</script>
<script>
  function calculateCartSummary() {
    let total = 0;
    Object.values(cart).forEach(item => {
     total += item.price * item.qty; // ✅ use .qty instead of .quantity

    });
    return total.toFixed(2);
  }

 function checkout() {
  const receivedAmount = parseFloat(document.getElementById("received-cash").value);
  if (isNaN(receivedAmount)) {
    alert("Please enter a valid received cash amount.");
    return;
  }

  const items = Object.values(cart).map(item => ({
    product_id: item.id,
    quantity: item.qty,
    price: item.price
  }));

  const totalAmount = Object.values(cart).reduce((acc, item) => acc + (item.price * item.qty), 0);
  const changeAmount = receivedAmount - totalAmount;

  if (changeAmount < 0) {
    alert("Received cash is less than total amount!");
    return;
  }

  const payload = {
    items: items,
    total_amount: totalAmount,
    received_amount: receivedAmount,
    change_amount: changeAmount
  };

  fetch('/pos/checkout', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(payload)
  })
  .then(response => {
    if (!response.ok) throw new Error("Failed to save receipt");
    return response.json();
  })
  .then(data => {
    alert("Receipt saved! Change: $" + changeAmount.toFixed(2));
    printReceipt(Object.values(cart), totalAmount, receivedAmount, changeAmount);
    for (const key in cart) delete cart[key];
    updateCartDisplay();
    document.getElementById("received-cash").value = "";
    document.getElementById("change-display").textContent = ""; // optional: clear change display
  })
  .catch(error => {
    console.error(error);
    alert("Error: " + error.message);
  });
}


</script>

</body>
</html>
