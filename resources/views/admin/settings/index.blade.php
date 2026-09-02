@extends('layouts.admin')

@section('content')
<div class="bg-[#f5f5f5] min-h-[calc(100vh-64px)] py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="text-[10px] font-bold text-gray-500 tracking-wider mb-2 uppercase">HOME > ADMIN CP > SITE CONFIGURATION</div>
            <h1 class="text-3xl font-black text-gray-900">SITE CONFIGURATION</h1>
            <p class="text-gray-500 text-sm mt-2 max-w-2xl">Configure core platform behavior, privacy thresholds, and global network settings for the X-CODE infrastructure.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 x-transition.duration.500ms
                 class="fixed bottom-4 right-4 z-50 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg shadow-lg text-sm font-bold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- SECTION 1: GENERAL SETTINGS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800">GENERAL SETTINGS</h2>
                    <i data-lucide="settings" class="w-5 h-5 text-red-600"></i>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Site Name</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Slogan</label>
                            <input type="text" name="slogan" value="{{ old('slogan', $settings['slogan']) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Keywords</label>
                        <input type="text" name="keywords" value="{{ old('keywords', $settings['keywords']) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                        <p class="text-[10px] text-gray-400 mt-1.5">Comma separated list of keywords for search engine optimization.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Webmaster Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                        <p class="text-[10px] text-gray-400 mt-1.5">System notifications and critical alerts will be sent from this address.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Footer Message</label>
                        <input type="text" name="footer_message" value="{{ old('footer_message', $settings['footer_message']) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PRIVACY -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800">PRIVACY</h2>
                    <i data-lucide="shield" class="w-5 h-5 text-red-600"></i>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">NETWORK VISITING</label>
                            <select name="network_visiting" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                                <option value="Registered Members Only" {{ old('network_visiting', $settings['network_visiting']) == 'Registered Members Only' ? 'selected' : '' }}>Registered Members Only</option>
                                <option value="Publicly Accessible" {{ old('network_visiting', $settings['network_visiting']) == 'Publicly Accessible' ? 'selected' : '' }}>Publicly Accessible</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">ACCOUNT VERIFICATION</label>
                            <select name="account_verification" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">
                                <option value="Email Verification Link" {{ old('account_verification', $settings['account_verification']) == 'Email Verification Link' ? 'selected' : '' }}>Email Verification Link</option>
                                <option value="Admin Approval" {{ old('account_verification', $settings['account_verification']) == 'Admin Approval' ? 'selected' : '' }}>Admin Approval</option>
                                <option value="None" {{ old('account_verification', $settings['account_verification']) == 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">PENDING MEMBERS SUBMISSION LIMIT</label>
                        <div class="flex items-center gap-3 text-sm text-gray-800">
                            <span>Pending members can submit up to</span>
                            <input type="number" name="pending_limit" value="{{ old('pending_limit', $settings['pending_limit']) }}" class="w-20 bg-gray-50 border border-gray-200 text-center text-sm rounded-md focus:ring-red-500 focus:border-red-500 p-2">
                            <span>items per day.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: OFFLINE STATUS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800">OFFLINE STATUS</h2>
                    <i data-lucide="power" class="w-5 h-5 text-red-600"></i>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1">Website Offline</label>
                            <p class="text-[11px] text-gray-400">Temporarily disable public access for maintenance operations.</p>
                        </div>
                        
                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" name="offline_mode" value="1" class="sr-only peer" {{ old('offline_mode', $settings['offline_mode']) == '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Offline Reason</label>
                        <textarea name="offline_reason" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-red-500 focus:border-red-500 block p-2.5">{{ old('offline_reason', $settings['offline_reason']) }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1.5">This message will be displayed to all users attempting to access the site while offline.</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: LOCATION OPTIONS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8" 
                 x-data="{ 
                    query: '', 
                    selected: {{ json_encode(old('locations', $settings['locations'])) }},
                    allLocations: ['Indonesia', 'USA', 'Japan', 'Australia', 'Austria', 'Brazil', 'Canada', 'United Kingdom', 'Germany', 'France'],
                    get filtered() {
                        return this.allLocations.filter(loc => loc.toLowerCase().includes(this.query.toLowerCase()));
                    },
                    remove(loc) {
                        this.selected = this.selected.filter(i => i !== loc);
                    },
                    toggle(loc) {
                        if(this.selected.includes(loc)) {
                            this.remove(loc);
                        } else {
                            this.selected.push(loc);
                        }
                    }
                 }">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800">LOCATION OPTIONS</h2>
                    <i data-lucide="globe" class="w-5 h-5 text-red-600"></i>
                </div>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">AVAILABLE LOCATIONS</label>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <template x-for="loc in selected" :key="loc">
                                <!-- Hidden inputs to submit selected locations array -->
                                <div>
                                    <input type="hidden" name="locations[]" :value="loc">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded bg-gray-100 text-xs font-medium text-gray-700">
                                        <span x-text="loc"></span>
                                        <button type="button" @click="remove(loc)" class="text-gray-400 hover:text-red-500">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </span>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="relative mb-4">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" x-model="query" placeholder="Search countries to add..." class="w-full bg-gray-50 border-none text-gray-900 text-sm focus:ring-0 py-3 pl-10">
                        </div>

                        <!-- Checkbox List -->
                        <div class="bg-gray-50 p-4 rounded max-h-48 overflow-y-auto space-y-3">
                            <template x-for="loc in filtered" :key="loc">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 text-blue-500 bg-white border-gray-300 rounded focus:ring-blue-500" 
                                           :checked="selected.includes(loc)"
                                           @change="toggle(loc)">
                                    <span class="text-sm text-gray-700" x-text="loc"></span>
                                </label>
                            </template>
                            <div x-show="filtered.length === 0" class="text-xs text-gray-400">No countries found matching your search.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: OTHERS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800">OTHERS</h2>
                    <i data-lucide="sliders-horizontal" class="w-5 h-5 text-red-600"></i>
                </div>
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">MAXIMUM MINI-BLOG LENGTH</label>
                        <div class="flex items-center gap-3 text-sm text-gray-800">
                            <input type="number" name="max_miniblog_length" value="{{ old('max_miniblog_length', $settings['max_miniblog_length']) }}" class="w-24 bg-gray-50 border border-gray-200 text-sm rounded-md focus:ring-red-500 focus:border-red-500 p-2.5">
                            <span class="text-xs text-gray-500">characters</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-4">RECAPTCHA INTEGRATION</label>
                        
                        <div class="space-y-4">
                            <!-- Signup Checkbox -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input type="checkbox" name="recaptcha_signup" value="1" {{ old('recaptcha_signup', $settings['recaptcha_signup']) == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-500 bg-white border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">Enable on Signup</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">Require reCAPTCHA verification during new account creation.</div>
                                </div>
                            </label>

                            <!-- Login Checkbox -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input type="checkbox" name="recaptcha_login" value="1" {{ old('recaptcha_login', $settings['recaptcha_login']) == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-500 bg-white border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">Enable on Login</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">Require reCAPTCHA verification during member authentication.</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="flex items-center justify-end gap-4 pt-4 pb-10">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 transition-colors">
                    CANCEL
                </a>
                <button type="submit" class="bg-[#b90000] hover:bg-red-700 text-white text-xs font-bold py-2.5 px-6 rounded transition-colors">
                    SAVE CHANGES
                </button>
            </div>
            
        </form>
    </div>
</div>

<!-- Re-initialize Lucide icons in Alpine x-for loops -->
<script>
    document.addEventListener('alpine:initialized', () => {
        // Observers can be used to re-render lucide icons when alpine DOM changes,
        // but since we only have x-for on items that use simple text or non-lucide SVGs mostly, we are fine.
        // If we strictly need lucide inside alpine loop, we just call lucide.createIcons() on DOM mutations.
        const observer = new MutationObserver(() => {
            lucide.createIcons();
        });
        observer.observe(document.querySelector('form'), { childList: true, subtree: true });
    });
</script>
@endsection
