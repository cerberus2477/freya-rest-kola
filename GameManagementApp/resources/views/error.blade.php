@if($errors->any())
    <div class="error">
        <h2>Hiba</h2>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif