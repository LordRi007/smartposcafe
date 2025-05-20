<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Product Management</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    
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
    
  </style>
  
</head>
<body>

  <div class="sidebar">
    <h2><i class="fas fa-user-cog"></i> Admin</h2>
    <ul>
        <li onclick="location.href='/'"><i class="fas fa-home"></i> Dashboard</li>
        <li onclick="location.href='/User'">
          <i class="fas fa-users"></i> Users
        </li>      
        <li onclick="location.href='/Product'"><i class="fas fa-box"></i> Product</li>
      
        <li onclick="location.href='/Login'"><i class="fas fa-sign-out-alt"></i> Logout</li>
    </ul>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Products</h1>
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
      <button onclick="openModal()">Add Product</button>
    </div>

    <table class="product-table">
      <thead>
        <tr>
           <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price ($)</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="productTable">
              @foreach ($pro as $p)
        <tr data-id="{{ $p->id }}">
          <td>{{ $p->id }}</td>
         <td><img src="{{ asset('img/' . $p->img) }}" alt="" style="width: 50px;"></td>
          <td>{{ $p->name }}</td>
          <td>{{ $p->price }}</td>
          <td>{{ $p->cat_id }}</td>
          <td>
        <button 
  class="edit-btn"
  onclick="editProduct(this)"
  data-id="{{ $p->id }}"
>
  Edit
</button>


         <button class="delete-btn" onclick="deleteProduct({{ $p->id }})">Delete</button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <!-- Add Product Modal -->
<div class="modal" id="productModal">
  <div class="modal-content" style="max-width: 400px;">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <form action="/addpro" method="POST" enctype="multipart/form-data">
      @csrf
      <h3 style="text-align: center; margin-bottom: 20px;">Add Product</h3>

      <label>Product Image</label>
      <input type="file" name="img" required>

      <label>Product Name</label>
      <input type="text" name="name" placeholder="Product Name" required>

      <label>Price</label>
      <input type="text" name="price" placeholder="Price" required>

     
      <label>Category</label>
      <select name="cat_id" required>
        <option value="" disabled selected>Select Category</option>
        <option value="Ice">Ice</option>
        <option value="Hot">Hot</option>
        <option value="Fapper">Fapper</option>
        <option value="Tea">Tea</option>
        <option value="Topping">Topping</option>
      </select>

      
      <button type="submit" style="margin-top: 15px;">Save Product</button>
    </form>
  </div>
</div>



<!-- Edit Product Modal -->
<div class="modal" id="editProductModal">
  <div class="modal-content" style="max-width: 400px;">
    <span class="modal-close" onclick="closeEditModal()">&times;</span>
    <form id="editProductForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <h3 style="text-align: center; margin-bottom: 20px;">Edit Product</h3>

      <input type="hidden" name="id" id="editId">

      <label>Product Image</label>
      <input type="file" name="img" id="editImg">

      <label>Product Name</label>
      <input type="text" name="name" id="editName" required>

      <label>Price</label>
      <input type="text" name="price" id="editPrice" required>

      <label>Category</label>
      <select name="cat_id" id="editCat" required>
        <option value="" disabled>Select Category</option>
        <option value="Ice">Ice</option>
        <option value="Hot">Hot</option>
        <option value="Fapper">Fapper</option>
        <option value="Tea">Tea</option>
        <option value="Topping">Topping</option>
      </select>

      <button type="submit" style="margin-top: 15px;">Update Product</button>
    </form>
  </div>
</div>


 <script>
  setTimeout(() => {
    const alerts = document.querySelectorAll('div[style*="background"]');
    alerts.forEach(el => el.style.display = 'none');
  }, 4000); // Hide after 4 seconds

  let imageBase64 = "";

  document.getElementById("prodImage")?.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        imageBase64 = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  function openModal() {
    document.getElementById('productModal').style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('productModal').style.display = 'none';
  }

  function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = '/Product/delete/' + id;
    }
}

  function editProduct(button) {
    const row = button.closest('tr');
    const id = button.getAttribute("data-id");
    const name = row.children[2].textContent.trim();
    const price = row.children[3].textContent.trim();
    const cat = row.children[4].textContent.trim();

    // Set form action dynamically
    const form = document.getElementById("editProductForm");
    form.action = `/editpro/${id}`;

    document.getElementById("editId").value = id;
    document.getElementById("editName").value = name;
    document.getElementById("editPrice").value = price;
    document.getElementById("editCat").value = cat;

    // Show modal
    document.getElementById("editProductModal").style.display = "flex";
  }

  function closeEditModal() {
    document.getElementById('editProductModal').style.display = 'none';
  }

  // SINGLE window.onclick listener to close modals when clicking outside
  window.onclick = function(event) {
    const productModal = document.getElementById('productModal');
    const editProductModal = document.getElementById('editProductModal');
    
    if (event.target === productModal) {
      closeModal();
    }
    if (event.target === editProductModal) {
      closeEditModal();
    }
  };

</script>

<script>
  // Apply stored mode on page load
  if (localStorage.getItem("mode") === "dark") {
    document.body.classList.add("dark-mode");
  }

  // Toggle dark mode and store preference
  function toggleMode() {
    document.body.classList.toggle("dark-mode");
    const mode = document.body.classList.contains("dark-mode") ? "dark" : "light";
    localStorage.setItem("mode", mode);
  }
</script>


</body>
</html>
