<?php

use App\Domains\User\DTOs\User\ConfirmTwoFactorDTO;
use App\Domains\User\Exceptions\User\TwoFactorAuthenticationAlreadyEnabledException;
use App\Domains\User\UseCases\ConfirmTwoFactorUseCase\ConfirmTwoFactorUseCase;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.dashboard')] #[Title('Confirm Two-Factor Authentication')] class extends Component
{
    public string $code = '';

    protected ConfirmTwoFactorUseCase $confirmTwoFactorUseCase;

    public function boot(ConfirmTwoFactorUseCase $confirmTwoFactorUseCase): void
    {
        $this->confirmTwoFactorUseCase = $confirmTwoFactorUseCase;
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ];
    }

    public function confirm(): void
    {
        try {
            $validated = $this->validate();

            $dto = new ConfirmTwoFactorDTO(
                code: $validated['code'],
            );

            $this->confirmTwoFactorUseCase->execute($dto);

            $this->reset('code');

            session()->flash(
                'success',
                'Two-factor authentication confirmed successfully.'
            );

            $this->redirectRoute(
                'users.two-factor-authentication',
                ['id' => auth()->id()]
            );
        } catch (TwoFactorAuthenticationAlreadyEnabledException $exception) {

            session()->flash(
                'error',
                'Two-factor authentication is already enabled for your account.'
            );

            $this->redirectRoute(
                'users.two-factor-authentication',
                ['id' => auth()->id()]
            );
        }
    }
};
?>

<div class="mx-auto max-w-xl space-y-6">

    <div>
        <h1 class="text-2xl font-semibold">
            Confirm Two-Factor Authentication
        </h1>

        <p class="mt-2 text-sm text-gray-500">
            Open your authenticator app and enter the 6-digit verification code
            to complete the setup.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 space-y-5">

        <div>
            <label for="code" class="mb-2 block text-sm font-medium">
                Authentication Code
            </label>

            <input
                id="code"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                maxlength="6"
                wire:model="code"
                placeholder="000000"
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-center text-xl tracking-[0.5em]" />

            @error('code')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
            @enderror
        </div>

        <button
            type="button"
            wire:click="confirm"
            wire:loading.attr="disabled"
            class="w-full rounded-lg bg-indigo-600 px-4 py-3 font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
            <span wire:loading.remove wire:target="confirm">
                Confirm & Enable
            </span>

            <span wire:loading wire:target="confirm">
                Verifying...
            </span>
        </button>

    </div>

    <p class="text-center text-sm text-gray-500">
        Make sure your phone's date and time are synchronized.
    </p>

</div>