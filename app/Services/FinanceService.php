<?php

namespace App\Services;

use App\Actions\ApproveQuote;
use App\Actions\CreateModel;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;

class FinanceService
{
    protected $createModelAction;

    protected $approveQuoteAction;

    public function __construct(CreateModel $createModelAction, ApproveQuote $approveQuoteAction)
    {
        $this->createModelAction = $createModelAction;
        $this->approveQuoteAction = $approveQuoteAction;
    }

    public function createInvoice($request)
    {
        try {
            return $this->createModelAction->handle(new Invoice, $request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function addProduct($request)
    {
        try {
            return $this->createModelAction->handle(new Product, $request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approveQuote($request)
    {
        try {
            $quote = Quote::where('id', $request->deal_id)->first();

            return $this->approveQuoteAction->handle($quote, Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createPayment($request)
    {
        try {
            return $this->createModelAction->handle(new Payment, $request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getInvoices()
    {
        try {
            return Invoice::where('owner_id', Auth::id())->with(['deal','client'])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
