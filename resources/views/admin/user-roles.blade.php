@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME &gt; ADMIN CP &gt; USER ROLES</div>
            <h1 class="text-3xl font-black text-gray-900 uppercase">USER ROLES</h1>
        </div>

        <div class="space-y-6">
            <!-- CURRENT ROLES Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">CURRENT ROLES</h2>
                    <p class="text-xs text-gray-500 mb-6">
                        'User Roles' is mainly used for grouping your members so that you can give different permissions to different members.
                    </p>
                    
                    <div class="space-y-3">
                        @foreach($current_roles as $role)
                            <div class="text-sm font-medium text-gray-800 pb-2 border-b border-gray-100 last:border-0 last:pb-0">
                                {{ $role }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ADD A ROLE Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">ADD A ROLE</h2>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">ROLE NAME</label>
                        <div class="flex items-center gap-4">
                            <input type="text" placeholder="Enter new role name" class="flex-1 max-w-md bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">
                            <button class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-6 py-2 rounded-md shadow-sm transition-colors">
                                ADD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Note -->
            <div class="text-center mt-8">
                <span class="text-[10px] text-red-400 italic">Go to 'Admin CP &gt; Themes &gt; Manage Blocks' to edit this message</span>
            </div>
        </div>

    </div>
</div>
@endsection
