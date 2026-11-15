<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;
Route::post('/webhook/bank', [WebhookController::class, 'handleBankWebhook']);