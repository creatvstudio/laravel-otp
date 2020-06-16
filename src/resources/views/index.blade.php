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
						</div>
					</form>

					@if(Route::has('otp.resend'))
						<form method="post" action="{{ route('otp.resend') }}" class="w-full text-xs text-center text-gray-700 mb-6">
							@csrf
							{{ __("Are you having problem providing OTP Code?") }}
							<button type="submit" class="text-blue-500 hover:text-blue-700 no-underline" href="{{ route('register') }}">
								{{ __('Resend') }}
							</button>
						</form>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection