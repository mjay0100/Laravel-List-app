 @if (session('success'))
     <div class="mb-4 py-2 px-4 bg-green-200 border-green-100 text-green-600 rounded-md">
         {{ $slot }}
     </div>
 @endif
