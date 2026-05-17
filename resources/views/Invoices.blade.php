<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Invoice Management</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <style>
    #receiptItems table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
#receiptItems th, #receiptItems td {
  padding: 6px 10px;
  border-bottom: 1px solid #ccc;
  text-align: left;
}

    :root {
      --bg-color: #f4f6f9;
      --card-bg: #fff;
      --text-color: #333;
      --sidebar-bg: #2c3e50;
      --sidebar-color: #ecf0f1;
    }

    body.dark-mode {
      --bg-color: #1e1e2f;
      --card-bg: #2a2a3d;
      --text-color: #f1f1f1;
      --sidebar-bg: #111;
      --sidebar-color: #ccc;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-color: var(--bg-color);
      color: var(--text-color);
      transition: background 0.3s, color 0.3s;
    }

    .sidebar {
      width: 240px;
      background-color: var(--sidebar-bg);
      color: var(--sidebar-color);
      padding: 20px 0;
      position: fixed;
      height: 100vh;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      padding: 15px 25px;
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .sidebar ul li:hover {
      background-color: rgba(255,255,255,0.1);
    }

    .main {
      margin-left: 240px;
      flex: 1;
      padding: 20px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      background: var(--card-bg);
      padding: 15px 25px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
      border-radius: 8px;
    }

    .topbar button {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      background-color: #3498db;
      color: white;
      cursor: pointer;
    }

    .product-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--card-bg);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .product-table th, .product-table td {
      padding: 15px;
      text-align: left;
      vertical-align: middle;
    }

    .product-table th {
      background: #3498db;
      color: white;
    }

    .product-table tr:nth-child(even) {
      background: rgba(0, 0, 0, 0.03);
    }

    .product-table img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 5px;
    }

    .product-table button {
      margin-right: 5px;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      color: white;
    }

    .edit-btn {
      background-color: #27ae60;
    }

    .delete-btn {
      background-color: #e74c3c;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: var(--card-bg);
      padding: 20px;
      border-radius: 12px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      position: relative;
    }

    .modal-content h3 {
      margin-bottom: 15px;
    }

    .modal-content input,
    .modal-content textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .modal-content button {
      width: 100%;
      padding: 10px;
      border: none;
      background-color: #3498db;
      color: white;
      border-radius: 8px;
      cursor: pointer;
    }

    .modal-close {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 24px;
      color: white;
      cursor: pointer;
    }

    @media (max-width: 700px) {
      .sidebar {
        display: none;
      }
      .main {
        margin-left: 0;
      }
    }
    .sidebar {
  width: 240px;
  background-color: var(--sidebar-bg);
  color: var(--sidebar-color);
  padding: 20px 0;
  position: fixed;
  height: 100vh;
  transition: all 0.3s ease;
  z-index: 1000;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 24px;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar ul li {
  padding: 15px 25px;
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  transition: background 0.2s, padding-left 0.2s;
}

.sidebar ul li:hover,
.sidebar ul li.active {
  background-color: rgba(255, 255, 255, 0.1);
  padding-left: 30px;
}

.sidebar ul li i {
  min-width: 20px;
  font-size: 16px;
}

.sidebar-toggle {
  display: none;
  position: fixed;
  top: 15px;
  left: 15px;
  background: #3498db;
  color: white;
  border: none;
  padding: 10px 12px;
  font-size: 18px;
  border-radius: 6px;
  z-index: 1001;
  cursor: pointer;
}

@media (max-width: 768px) {
  .sidebar {
    left: -240px;
  }

  .sidebar.show {
    left: 0;
  }

  .sidebar-toggle {
    display: block;
  }

  .main {
    margin-left: 0;
    padding-top: 60px;
  }
}

  </style>
</head>
<body>

  <div class="sidebar">
     <h2><i class="fas fa-coffee"></i> SmartCafe</h2>
    <ul>
        <li onclick="location.href='/'"><i class="fas fa-home"></i> Dashboard</li>
        <li onclick="location.href='/User'"><i class="fas fa-users"></i> Users</li>      
        <li onclick="location.href='/Product'"><i class="fas fa-box"></i> Product</li>
        <li onclick="location.href='/Invoice'" class="active"><i  class="fas fa-file-invoice"></i> Invoices</li>
     <li onclick="location.href='/Pos'"><i class="fas fa-solid fa-store"></i> POS</li>
        <li onclick="location.href='/Login'"><i class="fas fa-sign-out-alt"></i> Logout</li>
    </ul>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Invoices</h1>
      <form id="delete-form" method="POST" action="/deleteinvoice" style="display: none;">
        @csrf
        <input type="hidden" name="id" id="delete-invoice-id">
      </form>

      @if(session('success'))
      <div style="padding:10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb;">
        {{ session('success') }}
      </div>
      @endif

      @if(session('error'))
      <div style="padding:10px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;">
        {{ session('error') }}
      </div>
      @endif

    {{-- <button onclick="openModal()">Add Invoice</button> --}}
    </div>


<table class="product-table">
      <thead>
        <tr>
          <th>ID</th>
    <th>Total</th>
    <th>Received</th>
    <th>Change</th>
    <th>Date</th>
    <th>Actions</th>
        </tr>
      </thead>
      <tbody id="productTable">
           @foreach ($receipts as $receipt)
<tr>
    <td>{{ $receipt->id }}</td>

    <td>${{ number_format($receipt->total, 2) }}</td>
    <td>${{ number_format($receipt->received, 2) }}</td>
    <td>${{ number_format($receipt->change, 2) }}</td>
    <td>{{ $receipt->created_at->format('Y-m-d H:i') }}</td>
    <td>
        <button onclick='openReceiptModal(@json($receipt))'  class="edit-btn">View</button>
    </td>
</tr>
@endforeach


      </tbody>
    </table>

   
  </div>
<div class="modal" id="receiptModal">
  <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
    <span class="modal-close " onclick="closeReceiptModal()">&times;</span>
    <h2 style="text-align: center;">SmartCafe</h2>
    <p><strong>Receipt ID:</strong> <span id="receiptId"></span></p>
    <hr>
    <div id="receiptItems"></div>
    <hr>
   <p><strong>Total:</strong> $<span id="receiptTotal"></span></p>
<p><strong>Total in KHR:</strong> ៛<span id="receiptTotalKHR"></span></p>
<p><strong>Received:</strong> $<span id="receiptReceived"></span></p>
<p><strong>Change:</strong> $<span id="receiptChange"></span></p>
<p><strong>Change in KHR:</strong> ៛<span id="receiptChangeKHR"></span></p>

    <p style="text-align: center; margin-top: 15px;">Thank you!</p>
    <button onclick="window.print()">Print Receipt</button>
  </div>
</div>

  <!-- Modal -->
  <div class="modal" id="invoiceModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeModal()">&times;</span>
      <form action="/addinvoice" method="post">
        @csrf
        <h3>Add New Invoice</h3>
        <input type="text" name="customer_name" placeholder="Customer Name">
        <input type="number" name="total_amount" placeholder="Total Amount">
        <button type="submit">Save Invoice</button>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal" id="invoiceModal2">
    <div class="modal-content">
      <span class="modal-close" onclick="closeModal2()">&times;</span>
      <form action="/updateinvoice" method="post">
        @csrf
        <h3>Update Invoice</h3>
        <input type="hidden" name="id" id="editInvoiceId">
        <input type="text" name="customer_name" id="editCustomer" placeholder="Customer Name">
        <input type="number" name="total_amount" id="editTotal" placeholder="Total Amount">
        <button type="submit">Update Invoice</button>
      </form>
    </div>
  </div>

  <script>
function openReceiptModal(receipt) {
  const rate = 4100; // Exchange rate USD → KHR

  const totalUSD = parseFloat(receipt.total);
  const receivedUSD = parseFloat(receipt.received);
  const changeUSD = parseFloat(receipt.change);

  document.getElementById('receiptId').textContent = receipt.id;
  document.getElementById('receiptTotal').textContent = totalUSD.toFixed(2);
  document.getElementById('receiptReceived').textContent = receivedUSD.toFixed(2);
  document.getElementById('receiptChange').textContent = changeUSD.toFixed(2);

  // New KHR values
  document.getElementById('receiptTotalKHR').textContent = Math.round(totalUSD * rate).toLocaleString();
  document.getElementById('receiptChangeKHR').textContent = Math.round(changeUSD * rate).toLocaleString();

  // Render items
  let itemsHtml = '<table style="width:100%">';
  itemsHtml += '<tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>';
  receipt.items.forEach(item => {
    const totalPrice = item.quantity * item.price;
    itemsHtml += `<tr>
      <td>${item.product_name}</td>
      <td>${item.quantity}</td>
      <td>$${parseFloat(item.price).toFixed(2)}</td>
      <td>$${totalPrice.toFixed(2)}</td>
    </tr>`;
  });
  itemsHtml += '</table>';

  document.getElementById('receiptItems').innerHTML = itemsHtml;
  document.getElementById('receiptModal').style.display = 'flex';
}






function closeReceiptModal() {
  document.getElementById('receiptModal').style.display = 'none';
}

window.onclick = function(event) {
  const receiptModal = document.getElementById('receiptModal');
  const modalContent = receiptModal.querySelector('.modal-content');

  if (receiptModal.style.display === 'flex' && !modalContent.contains(event.target)) {
    closeReceiptModal();
  }

  const invoiceModal1 = document.getElementById('invoiceModal');
  const invoiceModal2 = document.getElementById('invoiceModal2');

  if (invoiceModal1.style.display === 'flex' && !invoiceModal1.querySelector('.modal-content').contains(event.target)) {
    closeModal();
  }

  if (invoiceModal2.style.display === 'flex' && !invoiceModal2.querySelector('.modal-content').contains(event.target)) {
    closeModal2();
  }
};


  // Close modals when pressing ESC
  window.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
      closeReceiptModal();
      closeModal();
      closeModal2();
    }
  });

 
    setTimeout(() => {
      const alerts = document.querySelectorAll('div[style*="background"]');
      alerts.forEach(el => el.style.display = 'none');
    }, 4000);

    function openModal() {
      document.getElementById('invoiceModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('invoiceModal').style.display = 'none';
    }

    function openModal2() {
      document.getElementById('invoiceModal2').style.display = 'flex';
    }

    function closeModal2() {
      document.getElementById('invoiceModal2').style.display = 'none';
    }

    window.onclick = function (event) {
      const modal1 = document.getElementById('invoiceModal');
      const modal2 = document.getElementById('invoiceModal2');

      if (event.target === modal1) closeModal();
      if (event.target === modal2) closeModal2();
    };

    function deleteInvoice(button) {
      if (confirm("Are you sure you want to delete this invoice?")) {
        const row = button.closest('tr');
        const invoiceId = row.getAttribute('data-id');
        if (invoiceId) {
          document.getElementById('delete-invoice-id').value = invoiceId;
          document.getElementById('delete-form').submit();
        } else {
          alert("Invoice ID not found.");
        }
      }
    }

    function editInvoice(button) {
      const id = button.getAttribute('data-id');
      const customer = button.getAttribute('data-customer');
      const total = button.getAttribute('data-total');

      document.getElementById('editInvoiceId').value = id;
      document.getElementById('editCustomer').value = customer;
      document.getElementById('editTotal').value = total;

      openModal2();
    }
  </script>
  <script>
    if (localStorage.getItem("mode") === "dark") {
      document.body.classList.add("dark-mode");
    }
    function toggleMode() {
      document.body.classList.toggle("dark-mode");
      const mode = document.body.classList.contains("dark-mode") ? "dark" : "light";
      localStorage.setItem("mode", mode);
    }
  </script>
<script>
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
  }

  // Add active class to current menu item
  document.querySelectorAll('.sidebar ul li').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelectorAll('.sidebar ul li').forEach(i => i.classList.remove('active'));
      item.classList.add('active');
    });
  });
</script>

</body>
</html>
