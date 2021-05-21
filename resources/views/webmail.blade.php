@extends('layouts.body')

@section('content')
<div id="inbox_main">
    <nav class="bg-white">
        {{-- px-2 sm:px-6 --}}
        <div class=" mx-auto  shadow-black flex p-3 py-4 items-center justify-between">
            <div class="items-center justify-center flex w-full">
                <label class="inline-flex items-center pr-2">
                    <input type="checkbox" class="form-checkbox mr-1 h-5 w-5 text-gray-600">
                    <button class="flex w-3 items-center justify-center">
                        <svg class="w-2 items-center justify-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 21l-12-18h24z"/></svg>
                    </button>
                </label>
                <button class="flex items-center justify-center">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.5 2c-5.288 0-9.649 3.914-10.377 9h-3.123l4 5.917 4-5.917h-2.847c.711-3.972 4.174-7 8.347-7 4.687 0 8.5 3.813 8.5 8.5s-3.813 8.5-8.5 8.5c-3.015 0-5.662-1.583-7.171-3.957l-1.2 1.775c1.916 2.536 4.948 4.182 8.371 4.182 5.797 0 10.5-4.702 10.5-10.5s-4.703-10.5-10.5-10.5z"/></svg>
                </button>
                <button class="mx-3">
                    <i class="px-2 fas fa-ellipsis-v"></i>
                </button>
                <div class="ml-auto">
                    <span class="text-gray-400 text-sm">1 of 1</span>
                    <button class="mx-3">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="mx-3">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                </div>
            </div> 
        </div>
    </nav>
    {{-- <inbox-component /> --}}
    <div class="flex p-3 hover:bg-gray-300 border border-l-0 border-r-0 border-gray-300">
      
    </div>
</div>
@endsection