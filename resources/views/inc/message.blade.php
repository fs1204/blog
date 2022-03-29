@if(session('message'))
    <p class="message" style="color: green">{{ session('message') }}</p>
@endif
