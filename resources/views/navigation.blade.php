<h3 class="flex items-center font-normal text-white mb-6 text-base no-underline">
	<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" fill="#B3C1D1" d="M17 22a2 2 0 0 1-2-2v-1a1 1 0 0 0-1-1 1 1 0 0 0-1 1v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-3H5a3 3 0 1 1 0-6h1V8c0-1.11.9-2 2-2h3V5a3 3 0 1 1 6 0v1h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1a1 1 0 0 0-1 1 1 1 0 0 0 1 1h1a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-3zm3-2v-3h-1a3 3 0 1 1 0-6h1V8h-3a2 2 0 0 1-2-2V5a1 1 0 0 0-1-1 1 1 0 0 0-1 1v1a2 2 0 0 1-2 2H8v3a2 2 0 0 1-2 2H5a1 1 0 0 0-1 1 1 1 0 0 0 1 1h1a2 2 0 0 1 2 2v3h3v-1a3 3 0 1 1 6 0v1h3z"/></svg>
	<span class="sidebar-label">
		{{ __('Generator') }}
	</span>
</h3>

<ul class="list-reset mb-8">
    <li class="leading-wide mb-4 text-sm">
        <router-link :to="{name: 'resource-generator.home'}" class="text-white ml-8 no-underline dim">
            {{ __('New resource') }}
        </router-link>
    </li>

    <li class="leading-wide mb-4 text-sm">
        <router-link :to="{name: 'resource-generator.settings'}" class="text-white ml-8 no-underline dim">
            {{ __('Settings') }}
        </router-link>
    </li>
</ul>