<?php

use App\Common\Traits\FlashMessageException;
use App\Domains\User\UseCases\EnableTwoFactorUseCase\EnableTwoFactorUseCase;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.dashboard')] #[Title('Two-Factor Authentication')] class extends Component
{
    use FlashMessageException;

    protected EnableTwoFactorUseCase $enableTwoFactorUseCase;

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    public function boot(
        EnableTwoFactorUseCase $enableTwoFactorUseCase
    ): void {
        $this->enableTwoFactorUseCase = $enableTwoFactorUseCase;
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function user()
    {
        return auth()->user();
    }

    #[Computed]
    public function isTwoFactorEnabled(): bool
    {
        return $this->user->hasEnabledTwoFactorAuthentication();
    }

    #[Computed]
    public function isTwoFactorPending(): bool
    {
        return !empty($this->user->two_factor_secret)
            && !$this->isTwoFactorEnabled;
    }

    #[Computed]
    public function qrCode(): ?string
    {
        if (!$this->isTwoFactorPending) {
            return null;
        }

        return $this->user->twoFactorQrCodeSvg();
    }

    /*
    |--------------------------------------------------------------------------
    | Enable Two-Factor Authentication
    |--------------------------------------------------------------------------
    */

    public function enableTwoFactor(): void
    {
        try {

            $this->enableTwoFactorUseCase->execute();

            unset(
                $this->user,
                $this->isTwoFactorEnabled,
                $this->isTwoFactorPending,
                $this->qrCode
            );

            session()->flash(
                'success',
                'Two-factor authentication setup started successfully.'
            );
        } catch (\Throwable $exception) {

            $this->handleException($exception);
        }
    }
};
?>

<div>

    <!-- ============================================================= -->
    <!-- Page Header -->
    <!-- ============================================================= -->

    <div class="mb-8">

        <div class="flex items-center gap-3">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-6 w-6 text-blue-600">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75a2.25 2.25 0 01-2.25-2.25v-6a2.25 2.25 0 012.25-2.25z" />

                </svg>

            </div>

            <div>

                <h1 class="text-3xl font-bold text-slate-800">
                    Two-Factor Authentication
                </h1>

                <p class="mt-1 text-slate-500">
                    Manage your account security and authentication settings.
                </p>

            </div>

        </div>

    </div>


    <!-- ============================================================= -->
    <!-- Flash Messages -->
    <!-- ============================================================= -->

    @if (session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">

        <div class="flex items-center gap-3">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="h-5 w-5 text-green-600">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

            </svg>

            <p class="text-sm font-medium text-green-700">
                {{ session('success') }}
            </p>

            @if (session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="text-sm font-medium text-red-700">
                    {{ session('error') }}
                </p>
            </div>
            @endif

        </div>

    </div>

    @endif


    <!-- ============================================================= -->
    <!-- Security Overview -->
    <!-- ============================================================= -->

    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl
                    {{ $this->isTwoFactorEnabled ? 'bg-green-100' : 'bg-slate-100' }}">

                    @if ($this->isTwoFactorEnabled)

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6 text-green-600">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                    @else

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6 text-slate-500">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                    @endif

                </div>

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Account Protection
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Add another layer of security to your account.
                    </p>

                </div>

            </div>


            <div>

                @if ($this->isTwoFactorEnabled)

                <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">

                    <span class="h-2 w-2 rounded-full bg-green-500"></span>

                    Enabled

                </span>

                @elseif ($this->isTwoFactorPending)

                <span class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-700">

                    <span class="h-2 w-2 rounded-full bg-yellow-500"></span>

                    Pending Confirmation

                </span>

                @else

                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600">

                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>

                    Not Enabled

                </span>

                @endif

            </div>

        </div>

    </div>


    <!-- ============================================================= -->
    <!-- Main Content -->
    <!-- ============================================================= -->

    <div class="grid gap-6 lg:grid-cols-3">


        <!-- ========================================================= -->
        <!-- Left Side: Information -->
        <!-- ========================================================= -->

        <div class="space-y-6 lg:col-span-2">


            <!-- Two Factor Authentication Card -->

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-800">
                    Two-Factor Authentication
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Two-factor authentication protects your account by
                    requiring an additional verification code when signing in.
                </p>


                @if ($this->isTwoFactorEnabled)

                <!-- Enabled State -->

                <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-5">

                    <div class="flex items-start gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="mt-0.5 h-6 w-6 shrink-0 text-green-600">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                        <div>

                            <h3 class="font-semibold text-green-800">
                                Your account is protected
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-green-700">
                                Two-factor authentication is currently
                                enabled. You will need an authentication
                                code in addition to your password when signing in.
                            </p>

                        </div>

                    </div>

                </div>


                @elseif ($this->isTwoFactorPending)

                <!-- Pending Confirmation State -->

                <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-5">

                    <div class="flex items-start gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="mt-0.5 h-6 w-6 shrink-0 text-yellow-600">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m0 3h.008M21 12a9 9 0 11-18 0 9 9 0 01-18 0z" />

                        </svg>

                        <div>

                            <h3 class="font-semibold text-yellow-800">
                                Setup is not completed
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-yellow-700">
                                Your authenticator has not been confirmed yet.
                                Scan the QR code below and complete verification.
                            </p>

                        </div>

                    </div>

                </div>


                @else

                <!-- Disabled State -->

                <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5">

                    <h3 class="font-semibold text-slate-800">
                        Protect your account
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Two-factor authentication is currently disabled.
                        Enable it to make unauthorized access more difficult.
                    </p>

                    <form wire:submit="enableTwoFactor">

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="enableTwoFactor"
                            class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">

                            <span wire:loading.remove wire:target="enableTwoFactor">
                                Enable Two-Factor Authentication
                            </span>

                            <span wire:loading wire:target="enableTwoFactor">
                                Enabling...
                            </span>

                        </button>

                    </form>

                </div>

                @endif

            </div>


            <!-- QR Code Section -->

            @if ($this->isTwoFactorPending)

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-6">

                    <h2 class="text-lg font-semibold text-slate-800">
                        Set Up Authenticator App
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Follow the instructions below to connect your
                        authenticator application.
                    </p>

                </div>


                <!-- Steps -->

                <div class="mb-6 space-y-4">

                    <div class="flex items-start gap-3">

                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                            1
                        </span>

                        <p class="pt-1 text-sm text-slate-600">
                            Open an authenticator app on your phone,
                            such as Google Authenticator or Microsoft Authenticator.
                        </p>

                    </div>

                    <div class="flex items-start gap-3">

                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                            2
                        </span>

                        <p class="pt-1 text-sm text-slate-600">
                            Choose the option to add a new account
                            and scan the QR code below.
                        </p>

                    </div>

                    <div class="flex items-start gap-3">

                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                            3
                        </span>

                        <p class="pt-1 text-sm text-slate-600">
                            Your authenticator app will generate
                            a six-digit verification code.
                        </p>

                    </div>

                </div>


                <!-- QR Code -->

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-6">

                    <div class="text-center">

                        <h3 class="font-semibold text-slate-800">
                            Scan this QR Code
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            Open your authenticator app and scan the code.
                        </p>

                    </div>

                    <div class="mx-auto mt-6 flex min-h-56 w-full max-w-72 items-center justify-center rounded-xl border border-slate-200 bg-white p-5">

                        {!! $this->qrCode !!}

                    </div>

                    <p class="mt-5 text-center text-xs leading-5 text-slate-500">
                        Keep this QR code private. Do not share it with anyone.
                    </p>

                </div>


                <!-- Confirmation Link -->

                <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-5">

                    <h3 class="font-semibold text-blue-900">
                        Almost there!
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-blue-800">
                        After scanning the QR code, continue to verify
                        your authenticator app and complete the setup.
                    </p>

                    <a
                        href="{{ route('confirm-two-factor-authentication') }}"
                        class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Continue to Confirmation

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                </div>

            </div>

            @endif

        </div>


        <!-- ========================================================= -->
        <!-- Right Side: Security Information -->
        <!-- ========================================================= -->

        <div class="space-y-6">


            <!-- Benefits Card -->

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-800">
                    Why use 2FA?
                </h2>

                <div class="mt-5 space-y-5">

                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5 text-blue-600">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 15v2.25m-6.364-3.136A2.25 2.25 0 017.886 12h8.228a2.25 2.25 0 012.25 2.25v4.5A2.25 2.25 0 0116.114 21H7.886a2.25 2.25 0 01-2.25-2.25v-4.5zM8.25 12V8.25a3.75 3.75 0 117.5 0V12" />

                            </svg>

                        </div>

                        <div>

                            <h3 class="text-sm font-semibold text-slate-800">
                                Extra Security
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Your password alone will not be enough
                                to access your account.
                            </p>

                        </div>

                    </div>


                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-100">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5 text-purple-600">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div>

                            <h3 class="text-sm font-semibold text-slate-800">
                                Time-Based Codes
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Verification codes change periodically
                                for additional protection.
                            </p>

                        </div>

                    </div>


                    <div class="flex items-start gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-100">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5 text-green-600">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div>

                            <h3 class="text-sm font-semibold text-slate-800">
                                Better Account Protection
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-slate-500">
                                Helps protect your account even if
                                your password is compromised.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Important Information -->

            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-6">

                <div class="flex items-center gap-2">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5 text-yellow-700">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                    <h2 class="font-semibold text-yellow-900">
                        Important
                    </h2>

                </div>

                <ul class="mt-4 space-y-3 text-sm leading-6 text-yellow-800">

                    <li>
                        • Never share your authentication codes with anyone.
                    </li>

                    <li>
                        • Keep access to your authenticator app secure.
                    </li>

                    <li>
                        • Make sure you can access your authenticator
                        before completing the setup.
                    </li>

                </ul>

            </div>

        </div>

    </div>

</div>