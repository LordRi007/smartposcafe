<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    /* Sidebar styles */
    .sidebar {
      width: 240px;
      background-color: var(--sidebar-bg);
      color: var(--sidebar-color);
      padding: 20px 0;
      position: fixed;
      height: 100vh;
      transition: all 0.3s ease;
      z-index: 1000;
      left: 0;
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

    /* Sidebar toggle button */
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

    /* Main content styles */
    .main {
      margin-left: 240px;
      flex: 1;
      padding: 20px;
      transition: margin-left 0.3s ease;
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

    .topbar h1 {
      font-size: 22px;
    }

    .topbar button {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      background-color: #3498db;
      color: white;
      cursor: pointer;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background: var(--card-bg);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: background 0.3s, color 0.3s;
    }

    .card h3 {
      margin-bottom: 8px;
      font-size: 18px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
    }

    .chart-container {
      margin-top: 30px;
      background: var(--card-bg);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
  <!-- Sidebar toggle button for mobile -->
  <button class="sidebar-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <div class="sidebar">
    <h2><i class="fas fa-coffee"></i> SmartCafe</h2>
    <ul>
      <li onclick="location.href='/'" class="active"><i class="fas fa-home"></i> Dashboard</li>
      <li onclick="location.href='/User'"><i class="fas fa-users"></i> Users</li>      
      <li onclick="location.href='/Product'"><i class="fas fa-box"></i> Product</li>
      <li onclick="location.href='/Invoice'"><i class="fas fa-file-invoice"></i> Invoices</li>
      <li onclick="location.href='/Pos'"><i class="fas fa-store"></i> POS</li>
      <li onclick="location.href='/logout'"><i class="fas fa-sign-out-alt"></i> Logout</li>
    </ul>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Admin Dashboard</h1>
      <button class="dark-toggle" onclick="toggleMode()"> Dark Mode</button>
    </div>

    <div class="cards">
      <div class="card">
        <h3>Total Orders</h3>
        <p>{{ $co }}</p>
      </div>
      <div class="card">
        <h3>Total Sales</h3>
        <p>${{ $total }}</p>
      </div>
      <div class="card">
        <h3>Total Product</h3>
        <p>{{$pro}}</p>
      </div>
       <div class="card">
        <h3>Total Users</h3>
        <p>{{ $usercount }}</p>
      </div>
    </div>


    
    <div class="chart-container">
      <h3>Sales Overview</h3>
      <canvas id="salesChart" height="100"></canvas>
    </div>
  </div>

  <script>
    // Apply the saved theme mode on page load
    if (localStorage.getItem("mode") === "dark") {
      document.body.classList.add("dark-mode");
    }

    // Toggle dark mode and store preference
    function toggleMode() {
      document.body.classList.toggle("dark-mode");
      const mode = document.body.classList.contains("dark-mode") ? "dark" : "light";
      localStorage.setItem("mode", mode);
    }

    // Sidebar toggle for mobile
    function toggleSidebar() {
      document.querySelector('.sidebar').classList.toggle('show');
    }

    // Sidebar menu active item highlight
    document.querySelectorAll('.sidebar ul li').forEach(item => {
      item.addEventListener('click', () => {
        document.querySelectorAll('.sidebar ul li').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
      });
    });

    // Chart.js sales chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
          label: 'Sales ($)',
          data: {!! json_encode($data) !!},
          backgroundColor: 'rgba(52, 152, 219, 0.6)',
          borderColor: 'rgba(52, 152, 219, 1)',
          borderWidth: 1
        }]
      },
      options: {
        indexAxis: 'y', // Horizontal bar
        responsive: true,
        scales: {
          x: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

</body>
</html>
