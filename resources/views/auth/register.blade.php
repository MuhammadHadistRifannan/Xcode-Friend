@extends('layouts.auth')
@section('title', 'Register')
@section('content')
<div class="w-full max-w-2xl bg-white shadow-sm border border-neutral-200 rounded-lg p-6 sm:p-10">
    <div class="flex items-center mb-6 border-l-4 border-red-700 pl-3">
        <h2 class="text-xl font-bold text-neutral-800">Registration</h2>
    </div>

    <form method="POST" action="/register" class="space-y-6">
        @csrf

        <!-- Passport Section -->
        <div class="bg-neutral-50/50 border border-neutral-200 rounded-lg p-5">
            <h3 class="text-sm font-bold text-neutral-700 mb-4 flex items-center text-red-700">
                <!-- ID Card SVG -->
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Passport
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-3 items-center">
                    <label class="text-xs text-neutral-600 font-medium">Email Address <span class="text-red-600">*</span></label>
                    <div class="col-span-2">
                        <input type="email" name="email" required placeholder="Enter email" class="w-full border border-neutral-200 rounded py-1.5 px-3 text-sm focus:border-red-700 focus:outline-none transition">
                        <p class="text-[10px] text-neutral-400 mt-1 italic">(We won't display your Email Address.)</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 items-center">
                    <label class="text-xs text-neutral-600 font-medium">Username/Nickname <span class="text-red-600">*</span></label>
                    <div class="col-span-2">
                        <input type="text" name="username" required placeholder="bambang" class="w-full border border-neutral-200 rounded py-1.5 px-3 text-sm focus:border-red-700 focus:outline-none transition">
                        <p class="text-[10px] text-neutral-400 mt-1 italic">(4 to 18 characters, made up of 0-9,a-z)</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 items-center">
                    <label class="text-xs text-neutral-600 font-medium">Password <span class="text-red-600">*</span></label>
                    <div class="col-span-2">
                        <input type="password" name="password" required placeholder="••••••" class="w-full border border-neutral-200 rounded py-1.5 px-3 text-sm focus:border-red-700 focus:outline-none transition">
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Info Section -->
        <div class="bg-neutral-50/50 border border-neutral-200 rounded-lg p-5">
            <h3 class="text-sm font-bold text-neutral-700 mb-4 flex items-center text-red-700">
                <!-- User SVG -->
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Personal info
            </h3>
            <div class="space-y-4">
                <div class="grid grid-cols-3 items-center">
                    <label class="text-xs text-neutral-600 font-medium">Full Name <span class="text-red-600">*</span></label>
                    <div class="col-span-2">
                        <input type="text" name="fullname" required class="w-full border border-neutral-200 rounded py-1.5 px-3 text-sm focus:border-red-700 focus:outline-none transition">
                    </div>
                </div>
                <div class="grid grid-cols-3 items-start">
                    <label class="text-xs text-neutral-600 font-medium mt-2">Birth <span class="text-red-600">*</span></label>
                    <div class="col-span-2">
                        <div class="flex gap-2 mb-2">
                            <select name="birthyear" class="border border-neutral-200 rounded py-1 px-2 text-xs outline-none">
                                @for($i=2026; $i>=1970; $i--) <option value="{{$i}}">{{$i}}</option> @endfor
                            </select>
                            <select name="birthmonth" class="border border-neutral-200 rounded py-1 px-2 text-xs outline-none">
                                @for($i=1; $i<=12; $i++) <option value="{{$i}}">{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option> @endfor
                            </select>
                            <select name="birthday" class="border border-neutral-200 rounded py-1 px-2 text-xs outline-none">
                                @for($i=1; $i<=31; $i++) <option value="{{$i}}">{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option> @endfor
                            </select>
                        </div>
                        <label class="text-[11px] text-neutral-500 flex items-center cursor-pointer">
                            <input type="checkbox" name="hide_age" class="mr-1"> Hide my age
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-3 items-center">
                    <label class="text-xs text-neutral-600 font-medium">Gender <span class="text-red-600">*</span></label>
                    <div class="col-span-2 flex gap-4 text-xs text-neutral-700">
                        <label class="cursor-pointer"><input type="radio" name="gender" value="1" class="mr-1 text-red-600"> Male</label>
                        <label class="cursor-pointer"><input type="radio" name="gender" value="2" checked class="mr-1 text-red-600"> Female</label>
                        <label class="cursor-pointer"><input type="radio" name="gender" value="0" class="mr-1 text-red-600"> Hide</label>
                    </div>
                </div>
                <div class="grid grid-cols-3 items-center">
                    <label class="text-xs text-neutral-600 font-medium">Come from <span class="text-red-600">*</span></label>
                    <div class="col-span-2">
                        <select name="country" class="w-full border border-neutral-200 rounded py-1.5 px-3 text-sm outline-none">
                            <option value="Indonesia">Indonesia</option>
                            <option value="Afghanistan" selected>Afghanistan</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 items-start">
                    <label class="text-xs text-neutral-600 font-medium mt-2">About me</label>
                    <div class="col-span-2">
                        <textarea name="about_me" rows="3" class="w-full border border-neutral-200 rounded py-1.5 px-3 text-sm focus:border-red-700 focus:outline-none transition"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rules & Conditions Section -->
        <div class="bg-neutral-50/50 border border-neutral-200 rounded-lg p-5">
            <h3 class="text-sm font-bold text-neutral-700 mb-2 flex items-center text-red-700">
                <!-- Document SVG -->
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Rules & Conditions
            </h3>
            <div class="bg-white border border-neutral-200 rounded p-3 text-[11px] text-neutral-400 h-20 mb-4 overflow-y-auto">
                none
            </div>
            <div class="flex items-center justify-between">
                <label class="text-xs text-neutral-700 font-medium flex items-center cursor-pointer">
                    <input type="checkbox" required class="mr-2 h-4 w-4 text-red-700 border-neutral-300 rounded focus:ring-red-600">
                    I have read, and agree to abide by the Rules & Conditions.
                </label>
                <button type="submit" class="bg-[#990000] text-white text-xs font-bold px-5 py-2 rounded shadow hover:bg-red-800 transition">
                    Signup Now &rarr;
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
