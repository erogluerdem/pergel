@extends('layouts.master-without-nav')

@section('title')
    Login - Basit
@endsection

@section('css')
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 50px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .checkbox-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        
        .debug-info {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            max-width: 300px;
            z-index: 9999;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}?v=2">
@endsection

@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="Logo">
                <h3 class="mt-3">Hoş Geldiniz</h3>
                <p class="text-muted">Lütfen giriş yapın</p>
            </div>

            <form method="POST" action="{{ url('login') }}" id="loginForm">
                @csrf
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label for="email">E-posta Adresi</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="e-posta@example.com"
                           value="{{ old('email') }}" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Şifrenizi girin"
                           required>
                </div>

                <div class="checkbox-container">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Beni hatırla
                        </label>
                    </div>
                    <a href="#" class="text-muted">Şifremi unuttum</a>
                </div>

                <button type="submit" class="btn-login">
                    Giriş Yap
                </button>
            </form>
        </div>
    </div>

    <div class="debug-info" id="debugInfo">
        Debug: Sayfa yüklendi
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth-scene.js') }}?v=1"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const debugInfo = document.getElementById('debugInfo');
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            debugInfo.innerHTML = 'Sayfa yüklendi<br>';
            debugInfo.innerHTML += 'Email input: ' + (emailInput ? 'BULUNDU' : 'BULUNAMADI') + '<br>';
            debugInfo.innerHTML += 'Password input: ' + (passwordInput ? 'BULUNDU' : 'BULUNAMADI') + '<br>';
            
            // Email input events
            if (emailInput) {
                emailInput.addEventListener('focus', function() {
                    debugInfo.innerHTML += 'Email FOCUS<br>';
                });
                
                emailInput.addEventListener('input', function() {
                    debugInfo.innerHTML += 'Email INPUT: ' + this.value.length + ' karakter<br>';
                });
                
                emailInput.addEventListener('click', function() {
                    debugInfo.innerHTML += 'Email CLICK<br>';
                });
            }
            
            // Password input events
            if (passwordInput) {
                passwordInput.addEventListener('focus', function() {
                    debugInfo.innerHTML += 'Password FOCUS<br>';
                });
                
                passwordInput.addEventListener('input', function() {
                    debugInfo.innerHTML += 'Password INPUT: ' + this.value.length + ' karakter<br>';
                });
                
                passwordInput.addEventListener('click', function() {
                    debugInfo.innerHTML += 'Password CLICK<br>';
                });
            }
            
            // Form submit
            form.addEventListener('submit', function(e) {
                debugInfo.innerHTML += 'FORM SUBMIT<br>';
                debugInfo.innerHTML += 'Email: ' + emailInput.value + '<br>';
                debugInfo.innerHTML += 'Password: ' + (passwordInput.value.length > 0 ? 'VAR' : 'YOK') + '<br>';
            });
        });
    </script>
@endsection