@extends('layouts.app')

@section('content')
	<div class="container mx-auto">
		<div class="flex flex-wrap justify-center">
			<div class="w-full max-w-sm">
				<div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">

					<div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">
						{{ __('Verify One-Time Password') }}
					</div>

					<form class="w-full p-6" method="POST" action="{{ route('otp.verify') }}">
						@csrf

						<div class="flex flex-wrap mb-6">
							<label for="otp" class="block text-gray-700 text-sm font-bold mb-2">
								{{ __('OTP Code') }}:
							</label>

							<input id="otp" type="text" class="form-input w-full @error('otp') border-red-500 @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus>

							@error('otp')
							<p class="text-red-500 text-xs italic mt-4">
								{{ $message }}
							</p>
							@enderror
						</div>

						<div class="flex flex-wrap items-center">
							<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-gray-100 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
								{{ __('Verify') }}
							</button>

							@if (Route::has('password.request'))
							<a class="text-sm text-blue-500 hover:text-blue-700 whitespace-no-wrap no-underline ml-auto" href="{{ route('password.request') }}">
								{{ __('Forgot Your Password?') }}
							</a>
							@endif

							@if (Route::has('register'))
							<p class="w-full text-xs text-center text-gray-700 mt-8 -mb-4">
								{{ __("Don't have an account?") }}
								<a class="text-blue-500 hover:text-blue-700 no-underline" href="{{ route('register') }}">
									{{ __('Register') }}
								</a>
							</p>
							@endif
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
@endsection