<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Management</title>
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

    .user-table {
      width: 100%;
      border-collapse: collapse;
      background: var(--card-bg);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .user-table th, .user-table td {
      padding: 15px;
      text-align: left;
    }

    .user-table th {
      background: #3498db;
      color: white;
    }

    .user-table tr:nth-child(even) {
      background: rgba(0, 0, 0, 0.03);
    }

    .user-table button {
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
      max-width: 400px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .modal-content h3 {
      margin-bottom: 15px;
    }

    .modal-content input {
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
  <h1>Users</h1>
  <form id="delete-form" method="POST" action="/deleteuser" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="delete-user-id">
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

  <button onclick="openModal()">Add User</button>
</div>



    <table class="user-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="userTable">
     @foreach ($user as $p)
  <tr data-id="{{ $p->id }}">
    <td>{{ $p->id }}</td>
    <td>{{ $p->name }}</td>
    <td>{{ $p->email }}</td>
    <td>{{ $p->role }}</td>
    <td>
     <button 
  class="edit-btn"
  onclick="editUser(this)"
  data-id="{{ $p->id }}"
  data-name="{{ $p->name }}"
  data-email="{{ $p->email }}"
  data-role="{{ $p->role }}"
>
  Edit
</button>

      <button class="delete-btn" onclick="deleteUser(this)">Delete</button>
    </td>
  </tr>
@endforeach

      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal" id="userModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeModal()">&times;</span>
      <form action="/adduser" method="post">
        @csrf
      <h3>Add New User</h3>
     <input type="text" name="name" id="name" placeholder="Name">
<input type="email" name="email" id="email" placeholder="Email">
<input type="password" name="pass" id="pass" placeholder="Password">
<select name="role" id="role" required style="padding: 8px; width: 100%; border-radius: 4px;">
  <option value="">Select Role</option>
        <option value="Manager">Manager</option>
        <option value="Assistant">Assistant</option>
        <option value="Staff">Staff</option>
      </select>
      <br>
      <br>
      <br>
      <button type="submit">Save User</button>
    </form>
    </div>
  </div>

   <!-- Modal2 -->
  <div class="modal" id="userModal2">
    <div class="modal-content">
      <span class="modal-close" onclick="closeModal2()">&times;</span>
      <form action="/updateuser" method="post">
        @csrf
      <h3>Update User</h3>
    <input type="hidden" name="id" id="editId">
<input type="text" name="name" id="editName" placeholder="Name">
<input type="email" name="email" id="editEmail" placeholder="Email">
<input type="password" name="pass" id="editPass" placeholder="Password">
<select name="role" id="editRole" required>
  <option value="">Select Role</option>
  <option value="Manager">Manager</option>
  <option value="Assistant">Assistant</option>
  <option value="Staff">Staff</option>
</select>
      <br>
      <br>
      <br>
      <button type="submit">Save User</button>
    </form>
    </div>
  </div>


  <script>
      setTimeout(() => {
    const alerts = document.querySelectorAll('div[style*="background"]');
    alerts.forEach(el => el.style.display = 'none');
  }, 4000); // Hide after 4 seconds
    function openModal() {
      document.getElementById('userModal').style.display = 'flex';
    }
function openModal2() {
      document.getElementById('userModal2').style.display = 'flex';
    }
    function closeModal() {
      document.getElementById('userModal').style.display = 'none';
    }
     function closeModal2() {
      document.getElementById('userModal2').style.display = 'none';
    }
 // Optional: close modal if user clicks outside content

  // Combine modal close logic for both modals
  window.onclick = function (event) {
    const modal1 = document.getElementById('userModal');
    const modal2 = document.getElementById('userModal2');

    if (event.target === modal1) {
      closeModal();
    }

    if (event.target === modal2) {
      closeModal2();
    }
  };


    // function addUser() {
    //   const name = document.getElementById('name').value;
    //   const email = document.getElementById('email').value;
    //   const role = document.getElementById('role').value;

    //   if (!name || !email || !role) return alert("Fill all fields");

    //   const row = document.createElement('tr');
    //   row.innerHTML = `
    //     <td>${name}</td>
    //     <td>${email}</td>
    //     <td>${role}</td>
    //     <td>
    //       <button class="edit-btn" onclick="editUser(this)">Edit</button>
    //       <button class="delete-btn" onclick="deleteUser(this)">Delete</button>
    //     </td>
    //   `;
    //   document.getElementById('userTable').appendChild(row);
    //   closeModal();

    //   document.getElementById('name').value = '';
    //   document.getElementById('email').value = '';
    //   document.getElementById('role').value = '';
    // }

    // function deleteUser(button) {
    //   if (confirm("Are you sure you want to delete this user?")) {
    //     button.closest('tr').remove();
    //   }
    // }
    

 function deleteUser(button) {
    if (confirm("Are you sure you want to delete this user?")) {
      const row = button.closest('tr');
      const userId = row.getAttribute('data-id');

      if (userId) {
        document.getElementById('delete-user-id').value = userId;
        document.getElementById('delete-form').submit();
      } else {
        alert("User ID not found.");
      }
    }
  }


  function editUser(button) {
  const id = button.getAttribute('data-id');
  const name = button.getAttribute('data-name');
  const email = button.getAttribute('data-email');
  const role = button.getAttribute('data-role');

  document.getElementById('editId').value = id;
  document.getElementById('editName').value = name;
  document.getElementById('editEmail').value = email;
  document.getElementById('editRole').value = role;

  openModal2();
}

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
