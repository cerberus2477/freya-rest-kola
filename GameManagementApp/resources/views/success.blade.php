@if(session('success'))
    <div class="success">
        <h2>Siker</h2>
        {{ session('success') }}
    </div>
@endif