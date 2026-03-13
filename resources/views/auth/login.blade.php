@extends('layouts.guest')

@section('contenido')

<div class="container-fluid">
    <div class="row justify-content-center align-items-center mt-4">

        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow">

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
                            <label class="form-label">Usuario</label>
                            <input
                                type="text"
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
    </div>

    @endsection