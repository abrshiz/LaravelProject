@extends('layouts.app')

@section('content')
<style>
    .auth-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .auth-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        text-align: center;
        color: white;
    }
    
    .auth-header h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .auth-header p {
        opacity: 0.9;
        font-size: 14px;
    }
    
    .auth-body {
        padding: 40px;
    }
    
    .form-group {
        margin-bottom: 20px;
        position: relative;
    }
    
    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .form-control:focus {
        border-color: #667eea;
        outline: none;
        background: white;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    
    .input-icon {
        position: relative;
    }
    
    .input-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 16px;
    }
    
    .input-icon .form-control {
        padding-left: 45px;
    }
    
    .btn-auth {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102,126,234,0.4);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e1e8ed;
    }
    
    .auth-footer a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .auth-footer a:hover {
        color: #764ba2;
    }
    
    .alert {
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .alert-danger {
        background: #fee;
        color: #c33;
        border: 1px solid #fcc;
    }
    
    .password-strength {
        margin-top: 5px;
        font-size: 12px;
    }
    
    .strength-weak { color: #dc3545; }
    .strength-medium { color: #ffc107; }
    .strength-strong { color: #28a745; }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Join SocialHub! 🚀</h2>
            <p>Create your account and start connecting</p>
        </div>
        
        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password" onkeyup="checkPasswordStrength(this.value)">
                    </div>
                    <div id="password-strength" class="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <div class="input-icon">
                        <i class="fas fa-check-circle"></i>
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                
                <button type="submit" class="btn-auth">
                    <i class="fas fa-user-plus me-2"></i> Register
                </button>
                
                <div class="auth-footer">
                    <span style="color: #666;">Already have an account?</span>
                    <a href="{{ route('login') }}"> Sign in here</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('password-strength');
    if (password.length === 0) {
        strengthDiv.innerHTML = '';
        return;
    }
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    let strengthText = '';
    let strengthClass = '';
    
    if (strength <= 2) {
        strengthText = 'Weak password';
        strengthClass = 'strength-weak';
    } else if (strength <= 4) {
        strengthText = 'Medium password';
        strengthClass = 'strength-medium';
    } else {
        strengthText = 'Strong password!';
        strengthClass = 'strength-strong';
    }
    
    strengthDiv.innerHTML = strengthText;
    strengthDiv.className = 'password-strength ' + strengthClass;
}
</script>
@endsection
