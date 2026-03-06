@extends('layouts.guest')

@section('contenido')

<div class="container d-flex justify-content-center align-items-center" style="height:80vh;">

    <div class="card shadow" style="width:400px;">

        <div class="card-header text-center bg-warning text-dark">
            <h4>SIMEPP - INICIAR SESIÓN</h4>
        </div>

        <div class="card-body">

            @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required>

                    @error('email')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>

                    @error('password')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input
                        type="checkbox"
                        name="remember"
                        class="form-check-input"
                        id="remember">

                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">
                        Iniciar sesión
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection