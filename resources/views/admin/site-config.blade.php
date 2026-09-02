@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME &gt; ADMIN CP &gt; SITE CONFIGURATION</div>
            <h1 class="text-3xl font-black text-gray-900 uppercase">SITE CONFIGURATION</h1>
            <p class="text-gray-500 text-sm mt-1">Configure core platform behavior, privacy thresholds, and global network settings for the X-CODE infrastructure.</p>
        </div>

        <form action="#" method="POST" class="space-y-6">
            @csrf

            <!-- CARD 1: GENERAL SETTINGS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-100 p-4">
                    <h2 class="font-bold text-gray-900 text-sm tracking-wide">GENERAL SETTINGS</h2>
                    <i data-lucide="settings" class="w-4 h-4 text-red-600"></i>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Site Name & Slogan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1.5">Site Name</label>
                            <input type="text" value="{{ $config['site_name'] }}" class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1.5">Slogan</label>
                            <input type="text" value="{{ $config['slogan'] }}" class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">
                        </div>
                    </div>

                    <!-- Keywords -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">Keywords</label>
                        <textarea rows="2" class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">{{ $config['keywords'] }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1">Comma-separated list of keywords for search engine optimization.</p>
                    </div>

                    <!-- Webmaster Email -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">Webmaster Email</label>
                        <input type="email" value="{{ $config['webmaster_email'] }}" class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">
                        <p class="text-[10px] text-gray-400 mt-1">System notifications and contact forms will be sent from this address.</p>
                    </div>

                    <!-- Footer Message -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">Footer Message</label>
                        <textarea rows="2" class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">{{ $config['footer_message'] }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CARD 2: PRIVACY -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-100 p-4">
                    <h2 class="font-bold text-gray-900 text-sm tracking-wide">PRIVACY</h2>
                    <i data-lucide="shield" class="w-4 h-4 text-red-600"></i>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Network Viewing -->
                        <div>
                            <label class="block text-xs text-gray-600 mb-1.5 uppercase">NETWORK VIEWING</label>
                            <select class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors appearance-none cursor-pointer">
                                <option @if($config['network_viewing'] == 'Registered Members Only') selected @endif>Registered Members Only</option>
                                <option @if($config['network_viewing'] == 'Public') selected @endif>Public</option>
                            </select>
                        </div>
                        
                        <!-- Account Verification -->
                        <div>
                            <label class="block text-xs text-gray-600 mb-1.5 uppercase">ACCOUNT VERIFICATION</label>
                            <select class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors appearance-none cursor-pointer">
                                <option @if($config['account_verification'] == 'Email Verification Link') selected @endif>Email Verification Link</option>
                                <option @if($config['account_verification'] == 'Admin Approval') selected @endif>Admin Approval</option>
                                <option @if($config['account_verification'] == 'None') selected @endif>None</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pending Limit -->
                    <div class="pt-2">
                        <label class="block text-xs text-gray-600 mb-2 uppercase">PENDING MEMBERS THRESHOLD LIMIT</label>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <span>Pending members can submit up to</span>
                            <input type="number" value="{{ $config['pending_limit'] }}" class="w-16 text-center bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-2 py-1 transition-colors">
                            <span>items per day.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 3: OFFLINE STATUS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-100 p-4">
                    <h2 class="font-bold text-gray-900 text-sm tracking-wide">OFFLINE STATUS</h2>
                    <i data-lucide="power" class="w-4 h-4 text-red-600"></i>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Toggle Switch -->
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-bold text-gray-900 text-sm">Website Offline</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">Temporarily disable public access for maintenance operations.</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" {{ $config['website_offline'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>

                    <!-- Offline Reason -->
                    <div>
                        <label class="block text-xs text-gray-600 mb-1.5">Offline Reason</label>
                        <textarea rows="3" class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-2 text-sm text-gray-900 transition-colors">{{ $config['offline_reason'] }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1">This message will be displayed to all users attempting to access the site while offline.</p>
                    </div>
                </div>
            </div>

            <!-- CARD 4: LOCATION OPTIONS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-100 p-4">
                    <h2 class="font-bold text-gray-900 text-sm tracking-wide">LOCATION OPTIONS</h2>
                    <i data-lucide="globe" class="w-4 h-4 text-red-600"></i>
                </div>
                <div class="p-6">
                    <label class="block text-[10px] font-bold text-gray-500 mb-3 uppercase tracking-wide">AVAILABLE LOCATIONS</label>
                    
                    <!-- Selected Tags -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($config['locations'] as $loc)
                            <div class="flex items-center gap-1.5 bg-gray-100 border border-gray-200 text-gray-700 text-xs font-medium px-2.5 py-1 rounded-md">
                                {{ $loc }}
                                <i data-lucide="x" class="w-3 h-3 text-gray-400 hover:text-red-500 cursor-pointer transition-colors"></i>
                            </div>
                        @endforeach
                    </div>

                    <!-- Search Input -->
                    <div class="relative mb-4">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" placeholder="Find location to add..." class="w-full bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md pl-9 pr-3 py-2 text-sm text-gray-600 transition-colors">
                    </div>

                    <!-- Checkboxes -->
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-800">Australia</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-800">Austria</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-800">Brazil</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm text-gray-800">Canada</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CARD 5: OTHERS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-100 p-4">
                    <h2 class="font-bold text-gray-900 text-sm tracking-wide">OTHERS</h2>
                    <i data-lucide="sliders" class="w-4 h-4 text-red-600"></i>
                </div>
                <div class="p-6 space-y-6">
                    
                    <!-- Max Name Length -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-2 uppercase tracking-wide">MAXIMUM NAME/SLUG LENGTH</label>
                        <div class="flex items-center gap-3">
                            <input type="number" value="{{ $config['max_name_length'] }}" class="w-20 text-center bg-gray-50 border border-transparent focus:border-red-500 focus:bg-white rounded-md px-3 py-1.5 text-sm transition-colors">
                            <span class="text-xs text-gray-500">characters</span>
                        </div>
                    </div>

                    <!-- Security / Recaptcha Options -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-3 uppercase tracking-wide">RECAPTCHA INTEGRATION</label>
                        
                        <div class="space-y-4">
                            <!-- Enable on Signup -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="mt-0.5 flex-shrink-0">
                                    <input type="checkbox" {{ $config['enable_on_signup'] ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">Enable-on Signup</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">Require reCAPTCHA verification during new account creation.</div>
                                </div>
                            </label>

                            <!-- Enable on Login -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="mt-0.5 flex-shrink-0">
                                    <input type="checkbox" {{ $config['enable_on_login'] ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">Enable-on Login</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">Require reCAPTCHA verification during member authentication.</div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-6 pt-4 pb-12">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors">CANCEL</a>
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-6 py-2.5 rounded shadow-sm transition-colors">
                    SAVE CHANGES
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
