<nav class="bg-white">
  <div class=" mx-auto px-2 sm:px-6 border border-t-0 border-r-0 border-l-0 border-black">
    <div class="relative flex items-center justify-between h-16">
      <div class="flex items-center">
        <!-- Mobile menu button-->
        <button id="toggle_sidebar" v-on:click="toggle_sidebar" type="button" class="inline-flex items-center justify-center p-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="sr-only">Open main menu</span>

          <i class="fas fa-bars text-xl"></i>

        </button>
      </div>
      <div class="flex-auto flex items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex-shrink-0 flex items-center">
          <h1 class="text-xl font-bold md:pr-20 lg:pr-20">Webmail</h1>
        </div>
        <div class="hidden w-full sm:block sm:ml-6 md:pl-3 lg:pl-3">
          <div class="flex space-x-4">
            <div class="w-full max-w-xl">
              <searchbar-component/>
              {{-- <input class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none" --}}
                {{-- type="search" name="search" placeholder="Search"> --}}
              <button type="submit" class="absolute right-0 p-3">
                <i class="h-4 w-4 fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
        <navbar-buttons/>
      </div>
    </div>
  </div>

  <!-- Mobile menu, show/hide based on menu state. -->
  <div class="sm:hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1">
      <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
      {{-- <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</a>

      <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team</a>

      <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>

      <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a> --}}
      <div class="relative mx-auto text-gray-600 flex">
        <input class="border-2 border-gray-300 bg-white h-10 w-full px-5 pr-16 max-w-full rounded-lg text-sm focus:outline-none"
          type="search" name="search" placeholder="Search">
          <button type="submit" class="absolute right-0 p-3">
            <i class="h-4 w-4 fas fa-search"></i>
          </button>
      </div>
    </div>
  </div>
</nav>