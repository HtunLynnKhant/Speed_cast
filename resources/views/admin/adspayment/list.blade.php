@extends ('admin.layout.master')

@section('title')
payment
@endsection

@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list')}}">Ads</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ads-Payment</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                            Add Payment
                        </button>
                    </div>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addPaymentModalLabel">Add New Payment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.payment.store' : 'superadmin.ads.payment.store', $ads->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <!-- Payment Amount -->
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Payment Amount <small style="color:red">*</small></label>
                                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" required placeholder="Enter Payment Amount" value="{{ old('amount') }}">
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Payment Type -->
                                        <div class="mb-3">
                                            <label for="payment_type" class="form-label">Payment Type <small style="color:red">*</small></label>
                                            <select name="payment_type" id="payment_type" class="form-control @error('payment_type') is-invalid @enderror" required>
                                                <option value="" disabled {{ old('payment_type') ? '' : 'selected' }}>Select a Payment Type</option>
                                                <option value="{{ App\Enums\PaymentTypes::FULL->value }}" {{ old('payment_type') == App\Enums\PaymentTypes::FULL->value ? 'selected' : '' }}>FULL</option>
                                                <option value="{{ App\Enums\PaymentTypes::PARTIAL->value }}" {{ old('payment_type') == App\Enums\PaymentTypes::PARTIAL->value ? 'selected' : '' }}>PARTIAL</option>
                                            </select>
                                            @error('payment_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Currency Selection -->
                                        <div class="mb-3">
                                            <label for="currency_id" class="form-label">Currency <small style="color:red">*</small></label>
                                            <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror" required>
                                                <option value="">Select Currency</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->code }}</option>
                                                @endforeach
                                            </select>
                                            @error('currency_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Payment Date -->
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Payment Date <small style="color:red">*</small></label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}">
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Fully Paid Checkbox -->
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="isFullyPaid" name="is_fully_paid" value="1" {{ old('is_fully_paid') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isFullyPaid">Fully Paid</label>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Payment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-custom table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">Amount</th>
                                    <th scope="col" style="width: 10%;">Payment Type</th>
                                    <th scope="col" style="width: 10%;">Currency</th>
                                    <th scope="col" style="width: 10%;">Payment Date</th>
                                    <th scope="col" style="width: 10%;">Is Fully Paid</th>
                                    <th scope="col" style="width:  10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ads->payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ $payment->payment_type ==1 ? 'FULL' : 'PARTIAL' }}</td>
                                    <td>{{ $payment->currency->code }}</td>
                                    <td>{{ date('m-d-Y', strtotime($payment->date)) }}</td>
                                    <td class="text-capitalize">{{ $payment->is_fully_paid == 1 ? 'Yes' : 'No' }}</td>
                                    <td>
                                    <a class="btn-icon-edit" data-bs-toggle="modal" data-bs-target="#editPaymentModal"  title="Edit">
                                         <i data-feather="edit"></i>
                                    </a>
                                    
                                    </td>
                                    <!-- Edit Payment Modal -->
                                    <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.payment.update' : 'superadmin.ads.payment.update', ['id' => $ads->id, 'paymentId' => $payment->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT') <!-- Use PUT method for updating the payment -->
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="amount" class="form-label">Payment Amount</label>
                                                            <small style="color:red">*</small>
                                                            <input type="text" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required>
                                                            @error('amount')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="paymentType" class="form-label">Payment Type</label>
                                                            <small style="color:red">*</small>
                                                            <select name="payment_type" id="payment_type" class="form-control @error('payment_type') is-invalid @enderror" required>
                                                                <option value="" disabled {{ old('payment_type', $payment->payment_type) === null ? 'selected' : '' }}>Select a Payment Type</option>
                                                                <option value="{{ App\Enums\PaymentTypes::FULL->value }}" 
                                                                    {{ old('payment_type', $payment->payment_type) == App\Enums\PaymentTypes::FULL->value ? 'selected' : '' }}>FULL</option>
                                                                <option value="{{ App\Enums\PaymentTypes::PARTIAL->value }}" 
                                                                    {{ old('payment_type', $payment->payment_type) == App\Enums\PaymentTypes::PARTIAL->value ? 'selected' : '' }}>PARTIAL</option>
                                                            </select>
                                                            @error('payment_type')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="currency" class="form-label">Currency</label>
                                                            <select name="currency_id" id="currency" class="form-control @error('currency_id') is-invalid @enderror" required>
                                                                <option value="">Select Currency</option>
                                                                @foreach($currencies as $currency)
                                                                    <option value="{{ $currency->id }}" {{ old('currency_id', $payment->currency_id) == $currency->id ? 'selected' : '' }}>
                                                                        {{ $currency->code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('currency_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Status -->
                                                        <!-- <div class="col-lg-6 col-md-12 mb-3">
                                                            <label class="form-label">Status</label>
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::ACTIVE->value }}" {{ $payment->status === 'active' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Active</label>
                                                            </div>
                                                            <div class="form-check form-check-sm">
                                                                <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::INACTIVE->value }}" {{ $payment->status === 'inactive' ? 'checked' : '' }}>
                                                                <label class="form-check-label">Inactive</label>
                                                            </div>
                                                        </div> -->

                                                        <!-- Payment Date -->
                                                        <div class="mb-3">
                                                            <label for="date" class="form-label">Payment Date</label>
                                                            <small style="color:red">*</small>
                                                            <input type="date" 
                                                                class="form-control @error('date') is-invalid @enderror" 
                                                                id="date" 
                                                                name="date" 
                                                                value="{{ old('date') ?: ($payment->date ? \Carbon\Carbon::parse($payment->date)->format('Y-m-d') : '') }}" 
                                                                required>
                                                            @error('date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3 form-check">
                                                            <input type="checkbox" class="form-check-input" id="isFullyPaid" name="is_fully_paid" {{ old('is_fully_paid', $payment->is_fully_paid) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="isFullyPaid">Fully Paid</label>
                                                            <small style="color:red">*</small>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Payment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
