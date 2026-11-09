@extends('layouts.app')

@section('title', 'SmartCafe - Cashiers')
@section('page_title', 'Cashier Management')

@section('content')
<!-- Header Action Area -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h3 class="text-lg font-bold text-slate-950 dark:text-white">Active Cashier Registrations</h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Manage store accounts and system permissions</p>
    </div>
    <button onclick="openAddModal()" class="flex items-center justify-center gap-2 px-5 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
        <i class="fas fa-user-plus"></i>
        <span>Add Cashier</span>
    </button>
</div>

<!-- Users Table Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 font-semibold">
                    <th class="px-6 py-4.5 font-bold">Cashier Info</th>
                    <th class="px-6 py-4.5 font-bold">Email Address</th>
                    <th class="px-6 py-4.5 font-bold">System Role</th>
                    <th class="px-6 py-4.5 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                @foreach ($user as $p)
                <tr data-id="{{ $p->id }}" class="hover:bg-slate-50/40 dark:hover:bg-slate-800/10 transition-colors duration-150">
                    <td class="px-6 py-4.5">
                        <div class="flex items-center gap-3">
                            <!-- Avatar Circle -->
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm select-none border border-indigo-100 dark:border-indigo-950/50">
                                {{ strtoupper(substr($p->name, 0, 2)) }}
                            </div>
                            <div>
                                <span class="font-semibold text-slate-900 dark:text-white block">{{ $p->name }}</span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">ID: #{{ $p->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4.5 text-slate-600 dark:text-slate-400 font-medium">
                        {{ $p->email }}
                    </td>
                    <td class="px-6 py-4.5">
                        @if(strtolower($p->role) === 'manager')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 dark:bg-purple-950/20 text-purple-700 dark:text-purple-400 text-xs font-semibold rounded-full border border-purple-100 dark:border-purple-950/30">
                                <i class="fas fa-user-shield text-[10px]"></i>
                                Manager
                            </span>
                        @elseif(strtolower($p->role) === 'assistant')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 text-xs font-semibold rounded-full border border-blue-100 dark:border-blue-950/30">
                                <i class="fas fa-user-tie text-[10px]"></i>
                                Assistant
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-full border border-emerald-100 dark:border-emerald-950/30">
                                <i class="fas fa-user text-[10px]"></i>
                                {{ $p->role }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4.5">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Edit Button -->
                            <button 
                                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-900/50 flex items-center justify-center transition-all cursor-pointer hover:scale-105 active:scale-95"
                                onclick="editUser(this)"
                                data-id="{{ $p->id }}"
                                data-name="{{ $p->name }}"
                                data-email="{{ $p->email }}"
                                data-role="{{ $p->role }}"
                            >
                                <i class="fas fa-pen-to-square text-xs"></i>
                            </button>
                            <!-- Delete Button -->
                            <button 
                                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:border-rose-200 dark:hover:border-rose-900/50 flex items-center justify-center transition-all cursor-pointer hover:scale-105 active:scale-95"
                                onclick="deleteUser(this)"
                            >
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-form" method="POST" action="{{ route('user.destroy') }}" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="delete-user-id">
</form>

<!-- Modal: Add New User -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-md hidden transition-opacity duration-300" id="userModal">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-200/50 dark:border-slate-800/50 transform scale-95 transition-transform duration-300">
        <!-- Modal Title -->
        <div class="flex items-center justify-between px-8 py-6 border-b border-slate-200/50 dark:border-slate-800/50">
            <h3 class="text-xl font-bold text-slate-950 dark:text-white">Add New Cashier</h3>
            <button onclick="closeAddModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-times"></i></button>
        </div>
        
        <!-- Modal Form -->
        <form action="{{ route('user.add') }}" method="post" class="p-8 space-y-5">
            @csrf
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Full Name</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-user text-slate-400 ml-4"></i>
                    <input type="text" name="name" id="name" placeholder="John Doe" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Email Address</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-envelope text-slate-400 ml-4"></i>
                    <input type="email" name="email" id="email" placeholder="john@company.com" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="pass" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Password</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-lock text-slate-400 ml-4"></i>
                    <input type="password" name="pass" id="pass" placeholder="••••••••" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="role" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">System Role</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-user-shield text-slate-400 ml-4"></i>
                    <select name="role" id="role" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white font-medium cursor-pointer">
                        <option value="" disabled selected class="dark:bg-slate-900 text-slate-400">Select Role</option>
                        <option value="Manager" class="dark:bg-slate-900">Manager</option>
                        <option value="Assistant" class="dark:bg-slate-900">Assistant</option>
                        <option value="Staff" class="dark:bg-slate-900">Staff</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-2">
                Save Cashier
            </button>
        </form>
    </div>
</div>

<!-- Modal: Edit User -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-md hidden transition-opacity duration-300" id="userModal2">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-200/50 dark:border-slate-800/50 transform scale-95 transition-transform duration-300">
        <!-- Modal Title -->
        <div class="flex items-center justify-between px-8 py-6 border-b border-slate-200/50 dark:border-slate-800/50">
            <h3 class="text-xl font-bold text-slate-950 dark:text-white">Update Cashier</h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-times"></i></button>
        </div>
        
        <!-- Modal Form -->
        <form action="{{ route('user.update') }}" method="post" class="p-8 space-y-5">
            @csrf
            <input type="hidden" name="id" id="editId">
            
            <div class="space-y-1.5">
                <label for="editName" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Full Name</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-user text-slate-400 ml-4"></i>
                    <input type="text" name="name" id="editName" placeholder="Full Name" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="editEmail" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Email Address</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-envelope text-slate-400 ml-4"></i>
                    <input type="email" name="email" id="editEmail" placeholder="email@company.com" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center ml-1">
                    <label for="editPass" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Password</label>
                    <span class="text-[10px] text-indigo-500 dark:text-indigo-400 font-bold uppercase tracking-wide">Leave blank to keep current</span>
                </div>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-lock text-slate-400 ml-4"></i>
                    <input type="password" name="pass" id="editPass" placeholder="••••••••" class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="editRole" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">System Role</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-user-shield text-slate-400 ml-4"></i>
                    <select name="role" id="editRole" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white font-medium cursor-pointer">
                        <option value="" disabled class="dark:bg-slate-900 text-slate-400">Select Role</option>
                        <option value="Manager" class="dark:bg-slate-900">Manager</option>
                        <option value="Assistant" class="dark:bg-slate-900">Assistant</option>
                        <option value="Staff" class="dark:bg-slate-900">Staff</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-2">
                Update Account
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Open Add Cashier Modal
    function openAddModal() {
        const modal = document.getElementById('userModal');
        const content = modal.querySelector('div');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    // Close Add Cashier Modal
    function closeAddModal() {
        const modal = document.getElementById('userModal');
        const content = modal.querySelector('div');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 150);
    }

    // Open Edit Cashier Modal
    function openEditModal() {
        const modal = document.getElementById('userModal2');
        const content = modal.querySelector('div');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    // Close Edit Cashier Modal
    function closeEditModal() {
        const modal = document.getElementById('userModal2');
        const content = modal.querySelector('div');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 150);
    }

    // Trigger user deletion
    function deleteUser(button) {
        if (confirm("Are you sure you want to delete this cashier account? This action cannot be undone.")) {
            const row = button.closest('tr');
            const userId = row.getAttribute('data-id');
            if (userId) {
                document.getElementById('delete-user-id').value = userId;
                document.getElementById('delete-form').submit();
            } else {
                alert("Account ID not found.");
            }
        }
    }

    // Load cashier data into Edit Modal and display
    function editUser(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const role = button.getAttribute('data-role');

        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role;
        document.getElementById('editPass').value = ''; // Reset password field

        openEditModal();
    }

    // Close modal if user clicks outside
    window.addEventListener('click', function(event) {
        const addModal = document.getElementById('userModal');
        const editModal = document.getElementById('userModal2');
        
        if (event.target === addModal) {
            closeAddModal();
        }
        if (event.target === editModal) {
            closeEditModal();
        }
    });

    // Close modals on Esc press
    window.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeAddModal();
            closeEditModal();
        }
    });
</script>
@endsection
