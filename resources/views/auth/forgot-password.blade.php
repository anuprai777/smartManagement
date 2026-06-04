@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                <svg class="w-12 h-12 text-indigo-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <h1 class="text-2xl font-bold text-gray-900">Forgot Password</h1>
                <p class="text-gray-500 mt-1">Enter your email and we'll send you a reset link</p>
            </div>

            @if(session('status'))
            <div class="p-4 mb-6 text-green-800 bg-green-50 border border-green-200 rounded-lg text-sm">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                        placeholder="you@example.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Send Reset Link
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Remember your password?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
