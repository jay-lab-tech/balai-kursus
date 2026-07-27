<x-guest-layout>
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3.5 text-sm leading-5 text-amber-900">
        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-white">+</span>
        <p>Akun ini digunakan untuk mengakses program dan aktivitas belajar sebagai peserta.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="username" value="Nama pengguna" class="!font-semibold !text-slate-700" />
            <x-text-input id="username" class="mt-2 block w-full !rounded-xl !border-slate-200 !px-4 !py-3.5 !text-sm focus:!border-sky-500 focus:!ring-sky-500/20" type="text" name="username" :value="old('username', old('name'))" required autofocus autocomplete="nickname" placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat email" class="!font-semibold !text-slate-700" />
            <x-text-input id="email" class="mt-2 block w-full !rounded-xl !border-slate-200 !px-4 !py-3.5 !text-sm focus:!border-sky-500 focus:!ring-sky-500/20" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" class="!font-semibold !text-slate-700" />
            <x-text-input id="password" class="mt-2 block w-full !rounded-xl !border-slate-200 !px-4 !py-3.5 !text-sm focus:!border-sky-500 focus:!ring-sky-500/20" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Ulangi password" class="!font-semibold !text-slate-700" />
            <x-text-input id="password_confirmation" class="mt-2 block w-full !rounded-xl !border-slate-200 !px-4 !py-3.5 !text-sm focus:!border-sky-500 focus:!ring-sky-500/20" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center !rounded-xl !bg-[#102a43] !py-3.5 text-sm hover:!bg-[#173f5f]">
            Buat akun peserta
        </x-primary-button>
    </form>

    <div class="mt-7 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a class="font-bold text-sky-700 hover:text-sky-900" href="{{ route('login') }}">
            Masuk di sini
        </a>
    </div>
</x-guest-layout>
