@extends('client.components.app')

@section('page_title', 'Inscription - King ASIC Miner')

@section('content')

@include('client.pages.auth.partials.register_form')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle pour le mot de passe
    const togglePassword = document.getElementById('passwordToggler');
    const password = document.getElementById('fakePassword');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    }

    // Toggle pour la confirmation du mot de passe
    const togglePassword2 = document.getElementById('passwordToggler2');
    const confirmPassword = document.getElementById('confirmPassword');

    if (togglePassword2 && confirmPassword) {
        togglePassword2.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    }
});
</script>
@endsection
