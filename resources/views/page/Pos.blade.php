<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SmartPOS - Cashier Interface</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1>SmartCafe</h1>
  <nav>
    <a href="/">Home</a>
    <a href="#features">Features</a>
    <a onclick="location.href='/Login'">Logout</a>
   <a onclick="toggleMode()" >
  Dark Mode
</a>

  </nav>
</header>

<section id="pos" class="pos-interface">
  <h2 style="text-align:center; margin-bottom: 30px;">Cashier Terminal</h2>
  <div class="pos-layout">
    <!-- Product Buttons -->
    <div class="pos-products">
      <div>
        <h1>Products</h1>
        <div class="filter-cards">
          <div class="filter-card" onclick="filterProducts(1)" id="filter-1">
            <i class="fas fa-snowflake"></i><span>Ice</span>
          </div>
          <div class="filter-card" onclick="filterProducts(2)" id="filter-2">
            <i class="fas fa-mug-hot"></i><span>Hot</span>
          </div>
          <div class="filter-card" onclick="filterProducts(3)" id="filter-3">
            <i class="fas fa-blender"></i><span>Frappe</span>
          </div>
          <div class="filter-card" onclick="filterProducts(4)" id="filter-4">
            <i class="fas fa-ice-cream"></i><span>Topping</span>
          </div>
          <div class="filter-card active" onclick="filterProducts('All')" id="filter-All">
            <i class="fas fa-border-all"></i><span>All</span>
          </div>
        </div>
      </div>
      <div id="product-list" class="product-grid"></div>
    </div>

    <!-- Cart -->
    <div class="pos-cart">
      <h3>Cart</h3>
      <table class="cart-table">
        <thead><tr><th>Item</th><th>Qty</th><th>Price</th></tr></thead>
        <tbody id="cart-body"></tbody>
      </table>
      <h4>Total: $<span id="total-price">0.00</span></h4>
      <h4>Total (KHR): <span id="total-khr">0</span> ៛</h4>

      <div>
        <label id="received-label" for="received-cash">Received Cash: $</label>
<div style="display: flex; gap: 8px; margin-top: 10px;">
  <input 
    type="number" 
    id="received-cash" 
    placeholder="Enter cash" 
    style="padding: 5px; flex: 1;" 
    oninput="calculateChange()"
  />
  {{-- <button type="button" id="toggle-currency-btn" onclick="toggleCurrency()">KHR</button> --}}
</div>
 </div>
      <h4>Change: $<span id="change">0.00</span></h4>
      <h4>Change (KHR): <span id="change-khr">0</span> ៛</h4>

      <button onclick="clearCart()" style="margin-top: 10px; padding: 10px 20px; background: #dc3545; color: #fff; border: none; border-radius: 6px;">Clear Cart</button>
      <button onclick="payNow()" style="margin-top: 10px; padding: 10px 20px; background: var(--primary); color: #fff; border: none; border-radius: 6px;">Pay Now</button>
    </div>
  </div>
</section>

<!-- Your other sections like Hero, Features, Footer remain unchanged -->
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

<footer>
  &copy; 2025 SmartPOS. All rights reserved.
</footer>

<script>
  
   if (localStorage.getItem("mode") === "dark") {
    document.body.classList.add("dark-mode");
  }

  // Toggle dark mode and store preference
  function toggleMode() {
    document.body.classList.toggle("dark-mode");
    const mode = document.body.classList.contains("dark-mode") ? "dark" : "light";
    localStorage.setItem("mode", mode);
  }
  const products = {!! json_encode($products) !!};
  console.log(products); // Debug to check product data
  let cart = {};
  let currentFilter = 'All';

  function filterProducts(catId) {
    currentFilter = catId;
    renderProducts(catId);

    document.querySelectorAll('.filter-card').forEach(card => card.classList.remove('active'));
    const activeCard = document.getElementById(`filter-${catId.toString()}`);
    if (activeCard) activeCard.classList.add('active');
  }

  function renderProducts(filter = 'All') {
    const container = document.getElementById('product-list');
    container.innerHTML = '';

    const filteredProducts = filter === 'All' ? products : products.filter(p => p.cat_id == filter);

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
const USD_TO_KHR = 4100;

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

  // Update total in KHR here
  const totalKHR = total * USD_TO_KHR;
  document.getElementById('total-khr').innerText = totalKHR.toLocaleString();

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

  function clearCart() {
    if (confirm("Clear the cart?")) {
      for (const key in cart) delete cart[key];
      updateCartDisplay();
      document.getElementById('received-cash').value = '';
      document.getElementById('change').innerText = '0.00';
    }
  }

function calculateChange() {
  const input = document.getElementById('received-cash');
  let receivedCash = parseFloat(input.value);
  if (isNaN(receivedCash)) receivedCash = 0;

  const totalUSD = parseFloat(document.getElementById('total-price').innerText);
  
  let receivedUSD;
  if (receivedIn === 'USD') {
    receivedUSD = receivedCash;
  } else {
    // Convert KHR input to USD for calculation
    receivedUSD = receivedCash / USD_TO_KHR;
  }

const changeUSD = receivedUSD - totalUSD;
const changeDisplay = changeUSD >= 0 ? changeUSD.toFixed(2) : "0.00";
const changeKHR = changeUSD >= 0 ? (changeUSD * USD_TO_KHR).toFixed(0) : "0";

document.getElementById('change').innerText = changeDisplay;
document.getElementById('change-khr').innerText = changeKHR;

}


  function payNow() {
    if (Object.keys(cart).length === 0) return alert("Cart is empty!");

    const receivedCash = parseFloat(document.getElementById('received-cash').value);
    const total = parseFloat(document.getElementById('total-price').innerText);
    const change = receivedCash - total;

    if (receivedCash < total) {
      return alert("Insufficient cash received.");
    }

    fetch('{{ route('pos.checkout') }}', {
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
        printReceipt();
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
  let total = 0;
  const USD_TO_KHR = 4100;

  let receiptContent = `
    <html><head><title>Receipt</title><style>
      body { font-family: Arial, sans-serif; padding: 20px; }
      table { width: 100%; border-collapse: collapse; margin-top: 20px; }
      th, td { padding: 8px; border-bottom: 1px solid #ccc; text-align: left; }
      .total { font-weight: bold; margin-top: 20px; }
    </style></head><body>
    <h2 style="text-align:center;">SmartCafe</h2>
    <table><thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead><tbody>`;

  Object.values(cart).forEach(item => {
    const itemTotal = item.qty * item.price;
    total += itemTotal;
    receiptContent += `<tr><td>${item.name}</td><td>${item.qty}</td><td>$${itemTotal.toFixed(2)}</td></tr>`;
  });

  const received = parseFloat(document.getElementById('received-cash').value || 0);
  const change = received - total;

  receiptContent += `</tbody></table>
    <p class="total">Total: $${total.toFixed(2)}</p>
    <p class="total">Total in KHR: ៛${(total * USD_TO_KHR).toFixed(0)}</p>
    <p class="total">Received: $${received.toFixed(2)}</p>
    <p class="total">Change: $${(change > 0 ? change : 0).toFixed(2)}</p>
    <p class="total">Change in KHR: ៛${(change > 0 ? change * USD_TO_KHR : 0).toFixed(0)}</p>
    <p style="text-align:center; margin-top:30px;">Thank you for your purchase!</p>
    </body></html>`;

  receiptWindow.document.write(receiptContent);
  receiptWindow.document.close();
  receiptWindow.focus();
  receiptWindow.print();
}






let receivedIn = 'USD';  // Track input currency, default USD

function toggleCurrency() {
  const btn = document.getElementById('toggle-currency-btn');
  const label = document.getElementById('received-label');
  const input = document.getElementById('received-cash');

  if (receivedIn === 'USD') {
    receivedIn = 'KHR';
    btn.innerText = 'USD';
    label.innerText = 'Received Cash: ៛';
    input.placeholder = "Enter cash in Riel";
  } else {
    receivedIn = 'USD';
    btn.innerText = 'KHR';
    label.innerText = 'Received Cash: $';
    input.placeholder = "Enter cash in Dollars";
  }

  calculateChange(); // Recalculate change on toggle
}

  renderProducts(); // initial load
</script>

</body>
</html>
