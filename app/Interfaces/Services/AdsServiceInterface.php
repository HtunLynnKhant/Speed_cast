<?php

namespace App\Interfaces\Services;

use App\Models\Ads;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdsServiceInterface
{
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?Ads;
    public function findById(string $id): ?Ads;
    public function update(Request $request, int $id): bool;
    public function delete(string $id): bool;
    public function getAllActive(): Collection;
    public function getAdWithPayments(int $id): Ads;
    public function storePayment(Request $request, int $id);
    public function updatePayment(Request $request, int $paymentId): ?Payment;
}
