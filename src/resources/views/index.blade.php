@extends('vendor.otp.base')

@section('content')
	<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
		<div class="max-w-md w-full">
			<div>
				<h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
					Verify One-Time Password
				</h2>
				@if(session('success') || $errors->has('otp'))
					<p class="mt-8 text-center text-sm leading-5 text-gray-600">
						@error('otp')
							<span class="font-medium text-red-600 hover:text-red-500 focus:outline-none focus:underline transition ease-in-out duration-150">
								{{ $message }}
							</span>
						@enderror
						@if(session('success'))
							<span class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
								{{ session('success') }}
							</span>
						@endif
					</p>
				@endif
			</div>
			<form class="mt-8" action="#" method="POST" action="{{ route('otp.verify') }}">
				@csrf
				<div class="rounded-md shadow-sm">
					<div>
						<input aria-label="OTP Code" name="otp" type="text" value="{{ old('otp') }}" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="OTP Code" />
					</div>
				</div>

				<div class="mt-6 flex items-center justify-between">
					<div class="flex items-center">
						<span class="ml-2 block text-sm leading-5 text-gray-900">
							<a href="{{ url('/') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">Back to Landing Page</a>
						</span>
					</div>

					<div class="text-sm leading-5">
						<a href="javascript:void(0);" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150" onclick="event.preventDefault(); document.getElementById('resend-otp-code-form').submit();">
							Resend OTP Code
						</a>
					</div>
				</div>

				<div class="mt-6">
					<button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
						<span class="absolute left-0 inset-y-0 flex items-center pl-3">
							<svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
							</svg>
						</span>
						Verify
					</button>
				</div>
			</form>
		</div>
	</div>

	<form id="resend-otp-code-form" method="post" action="{{ route('otp.resend') }}" class="hidden">
		@csrf
	</form>
@endsection