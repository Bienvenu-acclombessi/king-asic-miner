@extends('client.components.app')

@section('page_title', 'Connexion - King ASIC Miner')

@section('content')

@include('client.pages.auth.partials.login_form')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection
