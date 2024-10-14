<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celke - Importar CSV</title>
</head>
<body>
    @if (session('success'))
    <p style="color: green;">{!! session('success') !!}</p> 
@endif

@if (session('error'))
    <p style="color: purple;">{!! session('error') !!}</p> 
@endif


    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <p style="color:red;">{{$error}}</p>
        
    @endforeach
        
    @endif
    <form action="{{ route ('user.import')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="file" name="file" id="file" accept=".csv"><br><br>
        <button type="submit">Importar</button>

    </form>
    
    @foreach ($users as $user)
        {{$user->id}}
        @endforeach 
</body>
</html>
