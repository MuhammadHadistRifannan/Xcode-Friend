@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left Sidebar (col-span-3 equivalent in a 12-grid system: roughly 25%) -->
        <div class="w-full lg:w-3/12 xl:w-1/4 shrink-0">
            <x-sidebar-left :profile="$pageData['profile']" />
        </div>

        <!-- Main Feed (col-span-6 equivalent: roughly 50%) -->
        <div class="w-full lg:w-6/12 xl:w-2/4">
            <x-main-feed :posts="$pageData['posts']" />
        </div>

        <!-- Right Sidebar (col-span-3 equivalent: roughly 25%) -->
        <div class="w-full lg:w-3/12 xl:w-1/4 shrink-0">
            <x-sidebar-right :data="$pageData" />
        </div>

    </div>
</div>
@endsection
