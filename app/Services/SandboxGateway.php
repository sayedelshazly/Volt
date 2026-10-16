<?php
namespace App\Services;

/**
 * بوابة دفع تجريبية (Sandbox) تعمل بدون مفاتيح خارجية.
 * 4242 4242 4242 4242 => نجاح | 4000 0000 0000 0002 => رفض | 4000 0000 0000 9995 => رصيد غير كافٍ
 * يمكن استبدالها بـ Stripe/Paymob بنفس الواجهة charge() دون تغيير باقي الكود.
 */
class SandboxGateway {
    public function charge(string $card, float $amount): array {
        $n = preg_replace('/\D/', '', $card);
        if (!$this->luhn($n)) return ['ok' => false, 'msg' => __('Invalid card number')];
        return match ($n) {
            '4000000000000002' => ['ok' => false, 'msg' => __('Card declined')],
            '4000000000009995' => ['ok' => false, 'msg' => __('Insufficient funds')],
            default => ['ok' => true, 'txn' => 'sbx_' . bin2hex(random_bytes(8)), 'msg' => 'approved'],
        };
    }
    private function luhn(string $n): bool {
        $s = 0; $alt = false;
        for ($i = strlen($n) - 1; $i >= 0; $i--) {
            $d = (int) $n[$i];
            if ($alt) { $d *= 2; if ($d > 9) $d -= 9; }
            $s += $d; $alt = !$alt;
        }
        return strlen($n) >= 13 && $s % 10 === 0;
    }
}
